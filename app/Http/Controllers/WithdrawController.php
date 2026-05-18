<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\UserEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewWithdrawReq;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
    // ইউজারের বর্তমান অ্যাভেইলেবল ব্যালেন্স বের করার লজিক
    private function getAvailableBalance($userId)
    {
        $totalEarnings = UserEarning::where('user_id', $userId)->sum('amount');

        // পেন্ডিং এবং অ্যাপ্রুভড দুইটাই বিয়োগ হবে যাতে ইউজার টাকা তুলে নিলে সাথে সাথে আপডেট হয়
        $totalWithdrawn = Withdrawal::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        return $totalEarnings - $totalWithdrawn;
    }

    public function index()
    {
        $user = Auth::user();
        $availableBalance = $this->getAvailableBalance($user->id);

        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('content.users.withdraw', compact('availableBalance', 'withdrawals'));
    }

    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'amount' => 'required|numeric|min:5',
            'payment_method' => 'required',
            'account_number' => 'required'
        ], [
            'amount.min' => 'সর্বনিম্ন ১০০ টাকা উইথড্র করা যাবে।',
            'amount.required' => 'উইথড্র করার পরিমাণ লিখুন।',
            'account_number.required' => 'আপনার পেমেন্ট নম্বরটি দিন।'
        ]);

        try {
            $user = Auth::user();
            $requestedAmount = (float) $request->amount;
            $availableBalance = $this->getAvailableBalance($user->id);

            // ২. ব্যালেন্স চেক
            if ($availableBalance < $requestedAmount) {
                return back()->withInput()->with('error', 'দুঃখিত! আপনার পর্যাপ্ত ব্যালেন্স নেই।');
            }

            // ৩. ডাটাবেসে রিকোয়েস্ট সেভ করা
            $withdrawal = DB::transaction(function () use ($user, $requestedAmount, $request) {
                return Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $requestedAmount,
                    'payment_method' => $request->payment_method,
                    'account_number' => $request->account_number,
                    'status' => 'pending'
                ]);
            });

            // ৪. অ্যাডমিনকে ইমেইল পাঠানো
            try {
                $adminEmail = 'monoarulislam.cse@gmail.com';

                // Notification এর মাধ্যমে ইমেইল পাঠানো
                Notification::route('mail', $adminEmail)
                    ->notify(new NewWithdrawReq($user, $withdrawal));
            } catch (\Exception $e) {
                // ইমেইল না গেলে লগ করে রাখা যাতে ইউজার আটকে না যায়
                Log::error("Withdraw Email Notification Failed: " . $e->getMessage());
            }

            return back()->with('success', 'আপনার উইথড্র রিকোয়েস্টটি সফলভাবে জমা হয়েছে। অ্যাডমিন চেক করে পেমেন্ট করে দিবে।');
        } catch (\Exception $e) {
            Log::error("Withdrawal Store Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'কিছুর একটা সমস্যা হয়েছে! দয়া করে আবার চেষ্টা করুন।');
        }
    }
}
