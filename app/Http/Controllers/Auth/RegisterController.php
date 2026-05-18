<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewUserRegistration;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'required|unique:users,phone|digits:11',
            'transaction_id' => 'required|unique:users,transaction_id',
            'payment_type'   => 'required|in:bkash,nagad,rocket',
            'nid_image'      => 'required|image|mimes:jpeg,png,jpg|max:5024',
            'profile_image'  => 'required|image|mimes:jpeg,png,jpg|max:5024',
            'type'           => 'required|in:basic,premium,premium_pro',
            'nid_number'     => 'required|string',
            'address'        => 'required|string',
        ]);

        // ২. ফাইল আপলোড (Storage Disk: public ব্যবহার করে)

        $nidPath = null;
        if ($request->hasFile('nid_image')) {
            // storage/app/public/nids ফোল্ডারে সেভ হবে
            $nidPath = $request->file('nid_image')->store('nids', 'public');
        }

        $profilePath = null;
        if ($request->hasFile('profile_image')) {
            // storage/app/public/profiles ফোল্ডারে সেভ হবে
            $profilePath = $request->file('profile_image')->store('profiles', 'public');
        }

        // ৩. ইউজার ডাটা সেভ করা
        $user = User::create([
            'name'           => $request->name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'transaction_id' => $request->transaction_id,
            'payment_type'   => $request->payment_type,
            'nid_number'     => $request->nid_number,
            'address'        => $request->address,
            'nid_image'      => $nidPath,       // ডাটাবেসে সেভ হবে: nids/filename.jpg
            'profile_image'  => $profilePath,   // ডাটাবেসে সেভ হবে: profiles/filename.jpg
            'type'           => $request->type,
            'status'         => 'pending',
            'paid_amount'    => $this->getInitialPrice($request->type),
        ]);

        // ৪. অ্যাডমিনকে মেইল পাঠানো
        try {
            $adminEmail = 'monoarulislam.cse@gmail.com';
            Notification::route('mail', $adminEmail)->notify(new NewUserRegistration($user));
        } catch (\Exception $e) {
            Log::error("Admin Notification Failed: " . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'আবেদন সফল হয়েছে! অ্যাডমিন চেক করে SMS এ পাসওয়ার্ড পাঠাবে।');
    }

    // প্যাকেজের প্রাথমিক দাম বের করার ফাংশন
    private function getInitialPrice($type)
    {
        $prices = [
            'basic'       => 1000,
            'premium'     => 2000,
            'premium_pro' => 5000
        ];
        return $prices[$type] ?? 0;
    }
}
