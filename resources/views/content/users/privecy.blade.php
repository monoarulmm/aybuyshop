@extends('layouts.app')

@section('content')
    <div class="pb-20">
        {{-- Header Section --}}
        <div class="relative py-16 mb-12 overflow-hidden rounded-[3rem] bg-[#161925] border border-white/5">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 rounded-full blur-[100px]"></div>
            <div class="relative z-10 text-center px-6">
                <h1 class="text-4xl md:text-6xl font-black italic tracking-tighter text-white uppercase mb-4">
                    Privacy <span class="text-indigo-400">Policy</span>
                </h1>
                <p class="text-gray-400 font-medium max-w-2xl mx-auto text-sm md:text-base">
                    Your privacy is our priority. Learn how we handle your data and ensure a secure earning environment.
                </p>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="grid grid-cols-1 gap-8 max-w-5xl mx-auto px-4">

            {{-- 1. Information We Collect --}}
            <div class="bg-[#161925] rounded-[2.5rem] p-8 md:p-12 border border-gray-800 shadow-xl">
                <div class="flex items-center gap-4 mb-6">
                    <span
                        class="w-10 h-10 bg-indigo-600/20 text-indigo-400 rounded-xl flex items-center justify-center font-black">01</span>
                    <h2 class="text-xl md:text-2xl font-bold text-white uppercase italic">Information Collection</h2>
                </div>
                <div class="space-y-4 text-gray-400 leading-relaxed text-sm md:text-base font-medium">
                    <p>When you register on our platform, we collect essential information such as your name, email address,
                        and payment credentials (e.g., Bkash/Nagad number) to facilitate secure withdrawals.</p>
                    <p>We also track task completion data (video watch time, logs) to ensure fair play and prevent
                        fraudulent activities.</p>
                </div>
            </div>

            {{-- 2. How We Use Data --}}
            <div class="bg-[#161925] rounded-[2.5rem] p-8 md:p-12 border border-gray-800 shadow-xl">
                <div class="flex items-center gap-4 mb-6">
                    <span
                        class="w-10 h-10 bg-purple-600/20 text-purple-400 rounded-xl flex items-center justify-center font-black">02</span>
                    <h2 class="text-xl md:text-2xl font-bold text-white uppercase italic">Usage of Data</h2>
                </div>
                <ul class="space-y-4 text-gray-400 text-sm md:text-base font-medium">
                    <li class="flex items-start gap-3">
                        <span class="text-indigo-500 mt-1">✔</span>
                        <span>To verify and process your earning rewards accurately.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-indigo-500 mt-1">✔</span>
                        <span>To prevent bot activities and multiple account misuse.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-indigo-500 mt-1">✔</span>
                        <span>To communicate important updates regarding your account or withdrawals.</span>
                    </li>
                </ul>
            </div>

            {{-- 3. Security & Cookies --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-[#161925] rounded-[2.5rem] p-8 border border-gray-800 shadow-xl">
                    <h3 class="text-white font-bold text-lg mb-4 uppercase italic tracking-wider flex items-center gap-2">
                        <span class="text-xl">🔒</span> Data Security
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        We use industry-standard encryption to protect your sensitive data. Your passwords are hashed and
                        never stored in plain text.
                    </p>
                </div>
                <div class="bg-[#161925] rounded-[2.5rem] p-8 border border-gray-800 shadow-xl">
                    <h3 class="text-white font-bold text-lg mb-4 uppercase italic tracking-wider flex items-center gap-2">
                        <span class="text-xl">🍪</span> Cookies
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        We use cookies to maintain your login session and personalize your dashboard experience. You can
                        disable them in browser settings.
                    </p>
                </div>
            </div>

            {{-- 4. Third-Party Links --}}
            <div
                class="bg-gradient-to-r from-indigo-900/20 to-purple-900/20 rounded-[2.5rem] p-8 md:p-12 border border-indigo-500/10 text-center">
                <h2 class="text-white font-black text-xl md:text-2xl uppercase italic mb-4">Third-Party Services</h2>
                <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto mb-6">
                    Our platform includes videos from Facebook and YouTube. By watching these, you also agree to the
                    respective privacy policies of these third-party platforms.
                </p>
                <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.3em]">Last Updated:
                    {{ date('F d, Y') }}</p>
            </div>

        </div>

        {{-- Footer Call to action --}}
        <div class="mt-16 text-center">
            <a href="{{ url('/') }}"
                class="inline-block px-10 py-4 bg-white text-black rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:shadow-2xl hover:shadow-white/10 transition-all hover:-translate-y-1">
                Accept & Return Home
            </a>
        </div>
    </div>
@endsection
