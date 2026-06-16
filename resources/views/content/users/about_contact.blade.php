@extends('layouts.app')

@section('content')
    {{-- মূল কন্টেইনার: যা আপনার অ্যাপ লেআউটের সাইজ ঠিক রাখবে এবং ডিজাইন ভাঙবে না --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-20 w-full overflow-x-hidden">
        
        {{-- ১. হিরো সেকশন (Light Mode কনভার্টেড) --}}
        <div class="relative py-16 md:py-20 mb-16 overflow-hidden rounded-[2.5rem] bg-white border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
            {{-- লাইট গ্লো ইফেক্টস --}}
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-50 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-50 rounded-full blur-[100px]"></div>

            <div class="relative z-10 text-center px-4 sm:px-6">
                <div class="inline-block px-4 py-1.5 mb-5 rounded-full bg-indigo-50 border border-indigo-100">
                    <span class="text-[10px] text-indigo-600 font-black uppercase tracking-[0.2em]">Who We Are</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight text-gray-900 uppercase mb-5 leading-tight">
                    About <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">{{ $settings->site_name ?? 'Our Platform' }}</span>
                </h1>
                <p class="text-gray-500 font-medium max-w-2xl mx-auto text-xs sm:text-sm md:text-base leading-relaxed">
                    {{ $settings->about_short_description ?? 'We are dedicated to providing the best earning opportunities through digital tasks.' }}
                </p>
            </div>
        </div>

        <div class="space-y-16 md:space-y-24">

            {{-- ২. মেইন মিশন সেকশন --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6 sm:space-y-8 order-2 lg:order-1">
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 uppercase tracking-tight">
                        Our <span class="text-blue-600">Mission</span> & Vision
                    </h2>
                    <div class="text-gray-500 space-y-4 sm:space-y-5 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                        {!! $settings->about_long_description ?? 'Please update about description in site settings.' !!}
                    </div>

                    {{-- কুইক স্ট্যাটস কার্ড --}}
                    <div class="grid grid-cols-2 gap-4 sm:gap-6 pt-4">
                        <div class="p-5 sm:p-6 rounded-2xl bg-white border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                            <p class="text-xl sm:text-2xl font-black text-gray-900 mb-1">100%</p>
                            <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase font-black tracking-widest">Secure Payments</p>
                        </div>
                        <div class="p-5 sm:p-6 rounded-2xl bg-white border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                            <p class="text-xl sm:text-2xl font-black text-gray-900 mb-1">24/7</p>
                            <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase font-black tracking-widest">Active Support</p>
                        </div>
                    </div>
                </div>

                {{-- থ্রিডি ইফেক্ট ভিজ্যুয়াল বক্স --}}
                <div class="relative order-1 lg:order-2">
                    <div class="aspect-square bg-gradient-to-br from-blue-50 to-indigo-50 rounded-[2.5rem] sm:rounded-[3.5rem] border border-gray-100 flex items-center justify-center relative overflow-hidden group shadow-sm">
                        <div class="text-[100px] sm:text-[120px] opacity-40 group-hover:scale-110 transition-transform duration-500 select-none">
                            🚀
                        </div>
                        {{-- ফ্লোটিং ব্যাজ --}}
                        <div class="absolute bottom-6 left-6 sm:bottom-10 sm:left-10 bg-white border border-gray-100 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-lg shadow-gray-200/50 animate-bounce">
                            <p class="text-emerald-600 font-black text-lg sm:text-xl italic">Trusted By</p>
                            <p class="text-gray-400 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest">Thousands of Users</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ৩. কন্টাক্ট ইনফো সেকশন --}}
            <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 rounded-[2.5rem] p-8 md:p-12 border border-gray-200/60 shadow-sm text-center">
                <h3 class="text-xl md:text-2xl font-black text-gray-900 uppercase tracking-tight mb-8">Get in Touch</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 md:gap-12">
                    <div class="space-y-1 bg-white p-4 rounded-xl border border-gray-100 shadow-2xs">
                        <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase font-black tracking-widest">Support Email</p>
                        <p class="text-gray-800 font-bold text-xs sm:text-sm break-all">{{ $settings->email ?? 'support@example.com' }}</p>
                    </div>
                    <div class="space-y-1 bg-white p-4 rounded-xl border border-gray-100 shadow-2xs">
                        <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase font-black tracking-widest">Official phone</p>
                        <p class="text-gray-800 font-bold text-xs sm:text-sm">{{ $settings->phone_primary ?? '+880 1XXX-XXXXXX' }}</p>
                    </div>
                    <div class="space-y-1 bg-white p-4 rounded-xl border border-gray-100 shadow-2xs">
                        <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase font-black tracking-widest">Location</p>
                        <p class="text-gray-800 font-bold text-xs sm:text-sm">{{ $settings->address ?? 'Dhaka, Bangladesh' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection