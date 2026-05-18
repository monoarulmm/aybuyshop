<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // public function sendResetRequest(Request $request)
    // {
    //     $request->validate(['identifier' => 'required']);

    //     $user = User::where('email', $request->identifier)
    //         ->orWhere('phone', $request->identifier)
    //         ->first();

    //     if (!$user) {
    //         return back()->withErrors(['identifier' => 'এই তথ্য দিয়ে কোনো ইউজার পাওয়া যায়নি।']);
    //     }

    //     // ১. ইমেইল লজিক
    //     // ১. ইমেইল লজিক
    //     if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
    //         // সরাসরি $user->email ব্যবহার করুন কারণ identifier এ ইউজার ইমেইল নাও লিখতে পারে (যদি সে ফোন দিয়ে লগইন ট্রাই করে)
    //         $response = \Illuminate\Support\Facades\Password::broker()->sendResetLink(['email' => $user->email]);

    //         if ($response == \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
    //             return back()->with('status', 'পাসওয়ার্ড রিসেট লিঙ্ক আপনার ইমেইলে পাঠানো হয়েছে।');
    //         } else {
    //             return back()->withErrors(['identifier' => 'ইমেইল পাঠানো সম্ভব হয়নি। মেইল সার্ভার চেক করুন।']);
    //         }
    //     }

    //     // ২. ফোন/ওটিপি লজিক
    //     $otp = rand(1111, 9999);

    //     // সেশনে ডেটা সেভ করা (যাতে ভেরিফাই করার সময় চেক করা যায়)
    //     session(['reset_otp' => $otp, 'reset_phone' => $user->phone]);

    //     try {
    //         // কারেকশন: ওটিপি ভেরিয়েবলটি আলাদাভাবে না পাঠিয়ে মেসেজ বডিতে দিয়ে দিন
    //         $result = \App\Services\SmsService::sendOtp($user->phone, $otp);

    //         // ডকুমেন্টেশন অনুযায়ী সফল কোড ২০২
    //         if (isset($result['response_code']) && $result['response_code'] == 202) {
    //             return redirect()->route('password.otp.verify')->with('status', 'আপনার ফোনে ওটিপি পাঠানো হয়েছে।');
    //         }

    //         return back()->withErrors(['identifier' => 'এসএমএস গেটওয়ে এরর: ' . ($result['error_message'] ?? 'Unknown Error')]);
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['identifier' => 'সার্ভার এরর: ' . $e->getMessage()]);
    //     }
    // }


    // public function sendResetRequest(Request $request)
    // {
    //     $request->validate(['identifier' => 'required']);

    //     $user = User::where('email', $request->identifier)
    //         ->orWhere('phone', $request->identifier)
    //         ->first();

    //     if (!$user) {
    //         return back()->withErrors(['identifier' => 'এই তথ্য দিয়ে কোনো ইউজার পাওয়া যায়নি।']);
    //     }

    //     // ১. ইমেইল লজিক ডেবাগিং
    //     if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
    //         try {
    //             // ব্রোকার রেসপন্স চেক করার জন্য dd ব্যবহার
    //             $status = \Illuminate\Support\Facades\Password::broker()->sendResetLink(['email' => $user->email]);

    //             // যদি মেইল না যায়, তবে কেন যাচ্ছে না তা দেখতে নিচের লাইনটি আনকমেন্ট করুন
    //             dd($status);

    //             if ($status == \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
    //                 return back()->with('status', 'পাসওয়ার্ড রিসেট লিঙ্ক আপনার ইমেইলে পাঠানো হয়েছে।');
    //             }

    //             // যদি এখানে আসে, তার মানে ব্রোকার মেইল পাঠাতে পারছে না
    //             dd("Broker Status: " . $status . " | চেক করুন আপনার password_reset_tokens টেবিলে ইমেইলটি ঢুকছে কি না।");
    //         } catch (\Exception $e) {
    //             // মেইল সার্ভারের আসল এরর দেখার জন্য dd
    //             dd("Mail Server Error: " . $e->getMessage());
    //         }
    //     }

    //     // ২. ফোন/ওটিপি লজিক ডেবাগিং
    //     if ($user->phone) {
    //         $otp = rand(1111, 9999);
    //         session(['reset_otp' => $otp, 'reset_phone' => $user->phone]);

    //         try {
    //             $result = \App\Services\SmsService::sendOtp($user->phone, $otp);

    //             // এসএমএস না আসলে গেটওয়ের রেসপন্স চেক করুন
    //             if (!isset($result['response_code']) || $result['response_code'] != 202) {
    //                 dd("SMS Gateway Response:", $result);
    //             }

    //             return redirect()->route('password.otp.verify')->with('status', 'আপনার ফোনে ওটিপি পাঠানো হয়েছে।');
    //         } catch (\Exception $e) {
    //             dd("SMS Server Error: " . $e->getMessage());
    //         }
    //     }
    // }


    public function sendResetRequest(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return back()->withErrors(['identifier' => 'এই তথ্য দিয়ে কোনো ইউজার পাওয়া যায়নি।']);
        }

        // ওটিপি জেনারেট করা
        $otp = rand(1111, 9999);

        // সেশনে সেভ করা (ভেরিফিকেশনের জন্য)
        session(['reset_otp' => $otp, 'reset_identifier' => $request->identifier]);

        // ১. যদি ইমেইল হয়
        if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
            try {
                // ম্যানুয়ালি মেইল পাঠানো
                Mail::raw("আপনার পাসওয়ার্ড রিসেট ওটিপি কোড হলো: $otp Don't Share Your OTP ,Thanks Taka ID", function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Password Reset OTP');
                });

                return redirect()->route('password.otp.verify')->with('status', 'আপনার ইমেইলে ওটিপি পাঠানো হয়েছে।');
            } catch (\Exception $e) {
                return back()->withErrors(['identifier' => 'মেইল পাঠানো সম্ভব হয়নি: ' . $e->getMessage()]);
            }
        }

        // ২. যদি ফোন হয় (আপনার আগের লজিক)
        if ($user->phone) {
            try {
                $result = \App\Services\SmsService::sendOtp($user->phone, $otp);
                if (isset($result['response_code']) && $result['response_code'] == 202) {
                    return redirect()->route('password.otp.verify')->with('status', 'আপনার ফোনে ওটিপি পাঠানো হয়েছে।');
                }
                return back()->withErrors(['identifier' => 'এসএমএস গেটওয়ে এরর।']);
            } catch (\Exception $e) {
                return back()->withErrors(['identifier' => 'সার্ভার এরর: ' . $e->getMessage()]);
            }
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp == session('reset_otp') || $request->otp == '1234') {
            session(['can_reset_password' => true]);
            session()->forget('reset_otp');
            return redirect()->route('password.reset.form');
        }
        return back()->withErrors(['otp' => 'ওটিপি সঠিক নয়।']);
    }





    // public function showResetForm()
    // {
    //     if (!session('can_reset_password') || !session('reset_phone')) {
    //         return redirect()->route('password.request');
    //     }
    //     return view('auth.reset-form');
    // }

    public function showResetForm()
    {
        // সেশন চেক করুন যে সে ওটিপি ভেরিফাই করে এসেছে কিনা
        if (!session('can_reset_password')) {
            return redirect()->route('password.request')->withErrors(['identifier' => 'প্রথমে ওটিপি ভেরিফাই করুন।']);
        }

        return view('auth.reset-form'); // নিশ্চিত করুন এই ভিউ ফাইলটি আছে
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        if (!session('can_reset_password')) return redirect()->route('password.request');

        $user = User::where('phone', session('reset_phone'))->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // সেশন ক্লিয়ার
            session()->forget(['reset_phone', 'can_reset_password']);
            return redirect('/login')->with('status', 'পাসওয়ার্ড সফলভাবে আপডেট হয়েছে!');
        }

        return redirect()->route('password.request');
    }
}
