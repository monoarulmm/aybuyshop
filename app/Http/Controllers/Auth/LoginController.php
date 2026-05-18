<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_identity' => 'required|string',
            'password' => 'required|string',
        ]);

        // ইমেইল নাকি ফোন সেটি চেক করা
        $loginField = filter_var($request->login_identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginField => $request->login_identity,
            'password'  => $request->password
        ];

        // লগইন অ্যাটেম্পট
        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $user = Auth::user();

            // স্ট্যাটাস চেক (পেন্ডিং বা রিজেক্টেড থাকলে ঢুকতে দিবে না)
            if ($user->status !== 'active') {
                $statusMessage = $user->status === 'pending'
                    ? 'আপনার অ্যাকাউন্টটি এখনো পেন্ডিং আছে।'
                    : 'আপনার অ্যাকাউন্টটি স্থগিত করা হয়েছে।';

                Auth::logout();
                return back()->with('error', $statusMessage);
            }

            // ড্যাশবোর্ড বা হোমপেজে রিডাইরেক্ট
            return redirect()->intended('/')->with('success', 'লগইন সফল হয়েছে!');
        }

        // লগইন তথ্য ভুল হলে
        return back()->with('error', 'আপনার দেওয়া ইমেইল/ফোন নম্বর বা পাসওয়ার্ডটি ভুল।')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'লগআউট সফল হয়েছে।');
    }
}
