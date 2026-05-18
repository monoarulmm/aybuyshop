<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UpgradeRequest;
use Illuminate\Support\Facades\Storage; // এটি অবশ্যই উপরে ইমপোর্ট করবেন

use Illuminate\Support\Str;

class ProfileController extends Controller
{
    // প্রোফাইল এডিট পেজ দেখানো
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'nid_number'    => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'nid_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:5024',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5024',
        ]);

        // ১. প্রোফাইল ইমেজ আপলোড (Storage ব্যবহার করে)
        if ($request->hasFile('profile_image')) {
            // পুরনো ছবি মুছে ফেলা (storage/app/public/profiles থেকে)
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // নতুন ফাইল সেভ করা: storage/app/public/profiles ফোল্ডারে
            $profilePath = $request->file('profile_image')->store('profiles', 'public');

            // ডাটাবেসে পাথ সেভ করা (যেমন: profiles/filename.jpg)
            $user->profile_image = $profilePath;
        }

        // ২. NID ইমেজ আপলোড (Storage ব্যবহার করে)
        if ($request->hasFile('nid_image')) {
            // পুরনো NID মুছে ফেলা
            if ($user->nid_image) {
                Storage::disk('public')->delete($user->nid_image);
            }

            // নতুন ফাইল সেভ করা: storage/app/public/nids ফোল্ডারে
            $nidPath = $request->file('nid_image')->store('nids', 'public');

            $user->nid_image = $nidPath;
        }

        // ৩. বাকি তথ্য আপডেট
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nid_number = $request->nid_number;
        $user->address = $request->address;
        $user->save();

        return back()->with('success', 'আপনার প্রোফাইল সফলভাবে আপডেট করা হয়েছে!');
    }
    public function showUpgradePage()
    {
        return view('profile.upgrade');
    }
    public function upgrade(Request $request)
    {
        $user = Auth::user();

        // ভ্যালিডেশন: 'basic' অপশন যোগ করা হয়েছে
        $request->validate([
            'target_package' => 'required|in:basic,premium,premium_pro',
            'payment_type' => 'required|in:bkash,nagad,rocket',
            'transaction_id' => 'required|string|unique:upgrade_requests,transaction_id',
        ]);

        // প্যাকেজের ফুল প্রাইস লিস্ট
        $prices = [
            'basic' => 1000,
            'premium' => 2000,
            'premium_pro' => 5000
        ];

        $targetPrice = $prices[$request->target_package];
        $currentPaid = (float) $user->paid_amount;

        // বাকি কত টাকা দিতে হবে তার হিসাব
        $amountToPay = $targetPrice - $currentPaid;

        // যদি ইউজার অলরেডি সেই প্যাকেজে থাকে বা তার চেয়ে বেশি টাকা পেইড থাকে
        if ($amountToPay <= 0) {
            return back()->with('error', 'You are already on this or a higher package!');
        }

        // রিকোয়েস্ট ডাটাবেজে সেভ করা
        UpgradeRequest::create([
            'user_id' => $user->id,
            'target_package' => $request->target_package,
            'amount_to_pay' => $amountToPay,
            'payment_type' => $request->payment_type,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
        ]);

        return back()->with('success', '৳' . number_format($amountToPay) . ' টাকার পেমেন্ট রিকোয়েস্ট পাঠানো হয়েছে। ভেরিফিকেশন শেষে আপনার প্যাকেজ আপডেট হবে।');
    }

    // public function upgrade(Request $request)
    // {
    //     $user = Auth::user();

    //     // ভ্যালিডেশন
    //     $request->validate([
    //         'target_package' => 'required|in:premium,premium_pro',
    //         'payment_type' => 'required|in:bkash,nagad,rocket',
    //         'transaction_id' => 'required|string|unique:upgrade_requests,transaction_id',
    //     ]);

    //     // প্যাকেজের ফুল প্রাইস লিস্ট (আপনার প্রয়োজন অনুযায়ী পরিবর্তন করুন)
    //     $prices = [
    //         'basic' => 1000,
    //         'premium' => 2000,
    //         'premium_pro' => 5000
    //     ];

    //     $targetPrice = $prices[$request->target_package];
    //     $currentPaid = $user->paid_amount; // ইউজারের আগের পেইড অ্যামাউন্ট

    //     // বাকি কত টাকা দিতে হবে তার হিসাব
    //     $amountToPay = $targetPrice - $currentPaid;

    //     // যদি ইউজার অলরেডি সেই প্যাকেজে থাকে বা তার চেয়ে বেশি টাকা পেইড থাকে
    //     if ($amountToPay <= 0) {
    //         return back()->with('error', 'You are already on this or a higher package!');
    //     }

    //     // রিকোয়েস্ট ডাটাবেজে সেভ করা
    //     UpgradeRequest::create([
    //         'user_id' => $user->id,
    //         'target_package' => $request->target_package,
    //         'amount_to_pay' => $amountToPay,
    //         'payment_type' => $request->payment_type,
    //         'transaction_id' => $request->transaction_id,
    //         'status' => 'pending',
    //     ]);

    //     return back()->with('success', '৳' . number_format($amountToPay) . ' টাকার পেমেন্ট রিকোয়েস্ট পাঠানো হয়েছে। ভেরিফিকেশন শেষে আপনার প্যাকেজ আপডেট হবে।');
    // }
}
