@extends('layouts.app')

@section('content')
    {{-- মূল কন্টেইনার: যা আপনার অ্যাপ লেআউটের সাইজ ঠিক রাখবে এবং ডিজাইন ভাঙবে না --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-20 w-full overflow-x-hidden">
        
        {{-- Header Section (Light Mode) --}}
        <div class="relative py-16 mb-12 overflow-hidden rounded-[2.5rem] bg-white border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-50 rounded-full blur-[100px]"></div>
            <div class="relative z-10 text-center px-6">
                <h1 class="text-4xl md:text-5xl font-black tracking-tight text-gray-900 uppercase mb-4">
                    Privacy <span class="text-indigo-600">Policy</span>
                </h1>
                <p class="text-gray-500 font-medium max-w-2xl mx-auto text-xs sm:text-sm md:text-base leading-relaxed">
                    Your privacy is our priority. Learn how we handle your data and ensure a secure earning environment.
                </p>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="grid grid-cols-1 gap-8">

            {{-- 1. Information We Collect --}}
            <div class="bg-white rounded-[2.5rem] p-6 md:p-10 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center gap-4 mb-6">
                    <span class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-sm">
                        01
                    </span>
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 uppercase tracking-tight">Information Collection</h2>
                </div>
                <div class="space-y-4 text-gray-500 leading-relaxed text-xs sm:text-sm md:text-base font-medium">
                    <p>When you register on our platform, we collect essential information such as your name, email address,
                        and payment credentials (e.g., Bkash/Nagad number) to facilitate secure withdrawals.</p>
                    <p>We also track task completion data (video watch time, logs) to ensure fair play and prevent
                        fraudulent activities.</p>
                </div>
            </div>

            {{-- 2. How We Use Data --}}
            <div class="bg-white rounded-[2.5rem] p-6 md:p-10 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center gap-4 mb-6">
                    <span class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center font-black text-sm">
                        02
                    </span>
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 uppercase tracking-tight">Usage of Data</h2>
                </div>
                <ul class="space-y-3.5 text-gray-500 text-xs sm:text-sm md:text-base font-medium">
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✔</span>
                        <span>To verify and process your earning rewards accurately.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✔</span>
                        <span>To prevent bot activities and multiple account misuse.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✔</span>
                        <span>To communicate important updates regarding your account or withdrawals.</span>
                    </li>
                </ul>
            </div>

            {{-- 3. Security & Cookies --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-[2.5rem] p-6 md:p-8 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                    <h3 class="text-gray-900 font-bold text-base mb-3 uppercase tracking-wider flex items-center gap-2">
                        <span class="text-lg">🔒</span> Data Security
                    </h3>
                    <p class="text-gray-500 text-xs sm:text-sm leading-relaxed font-medium">
                        We use industry-standard encryption to protect your sensitive data. Your passwords are hashed and
                        never stored in plain text.
                    </p>
                </div>
                <div class="bg-white rounded-[2.5rem] p-6 md:p-8 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                    <h3 class="text-gray-900 font-bold text-base mb-3 uppercase tracking-wider flex items-center gap-2">
                        <span class="text-lg">🍪</span> Cookies
                    </h3>
                    <p class="text-gray-500 text-xs sm:text-sm leading-relaxed font-medium">
                        We use cookies to maintain your login session and personalize your dashboard experience. You can
                        disable them in browser settings.
                    </p>
                </div>
            </div>

            {{-- 4. Third-Party Links --}}
            <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 rounded-[2.5rem] p-6 md:p-10 border border-gray-200/60 shadow-sm text-center">
                <h2 class="text-gray-900 font-black text-lg md:text-xl uppercase tracking-tight mb-3">Third-Party Services</h2>
                <p class="text-gray-500 text-xs sm:text-sm md:text-base max-w-2xl mx-auto mb-6 leading-relaxed font-medium">
                    Our platform includes videos from Facebook and YouTube. By watching these, you also agree to the
                    respective privacy policies of these third-party platforms.
                </p>
                <p class="text-[9px] sm:text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">
                    Last Updated: {{ date('F d, Y') }}
                </p>
            </div>

        </div>

        {{-- Footer Call to action --}}
        <div class="mt-12 text-center">
            <a href="{{ url('/') }}"
                class="inline-block px-10 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition-all active:scale-95">
                Accept & Return Home
            </a>
        </div>
    </div>
@endsection