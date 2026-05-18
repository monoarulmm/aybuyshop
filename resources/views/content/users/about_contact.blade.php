@extends('layouts.app')

@section('content')
    <div class="pb-20">
        {{-- Hero Section --}}
        <div class="relative py-20 mb-16 overflow-hidden rounded-[3.5rem] bg-[#161925] border border-white/5 shadow-2xl">
            {{-- Glow Effects --}}
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>

            <div class="relative z-10 text-center px-6">
                <div class="inline-block px-4 py-1.5 mb-6 rounded-full bg-indigo-500/10 border border-indigo-500/20">
                    <span class="text-[10px] text-indigo-400 font-black uppercase tracking-[0.3em]">Who We Are</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black italic tracking-tighter text-white uppercase mb-6 leading-none">
                    About <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">{{ $settings->site_name ?? 'Our Platform' }}</span>
                </h1>
                <p class="text-gray-400 font-medium max-w-3xl mx-auto text-sm md:text-lg leading-relaxed">
                    {{ $settings->about_short_description ?? 'We are dedicated to providing the best earning opportunities through digital tasks.' }}
                </p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 space-y-20">

            {{-- Main Mission Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <h2 class="text-3xl md:text-4xl font-black text-white uppercase italic tracking-tight">
                        Our <span class="text-indigo-500">Mission</span> & Vision
                    </h2>
                    <div class="text-gray-400 space-y-6 text-sm md:text-base leading-loose font-medium">
                        {!! $settings->about_long_description ?? 'Please update about description in site settings.' !!}
                    </div>

                    {{-- Quick Stats from DB (if available) or static --}}
                    <div class="grid grid-cols-2 gap-6 pt-6">
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5">
                            <p class="text-2xl font-black text-white mb-1">100%</p>
                            <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Secure Payments</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5">
                            <p class="text-2xl font-black text-white mb-1">24/7</p>
                            <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Active Support</p>
                        </div>
                    </div>
                </div>

                {{-- Decorative Image/Box --}}
                <div class="relative">
                    <div
                        class="aspect-square bg-gradient-to-br from-indigo-600/20 to-purple-800/20 rounded-[4rem] border border-white/10 flex items-center justify-center relative overflow-hidden group">
                        <div
                            class="text-[120px] filter grayscale opacity-20 group-hover:opacity-40 transition-opacity duration-700">
                            🚀</div>
                        {{-- Floating Badge --}}
                        <div
                            class="absolute bottom-10 left-10 bg-[#161925] border border-gray-800 p-6 rounded-3xl shadow-2xl animate-bounce">
                            <p class="text-emerald-400 font-black text-xl italic">Trusted By</p>
                            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Thousands of Users</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact/Footer Info from Settings --}}
            <div
                class="bg-gradient-to-r from-indigo-900/40 via-[#161925] to-purple-900/40 rounded-[3rem] p-10 md:p-16 border border-white/5 text-center">
                <h3 class="text-2xl md:text-3xl font-black text-white uppercase italic mb-6">Get in Touch</h3>
                <div class="flex flex-wrap justify-center gap-8 md:gap-16">
                    <div class="space-y-2">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Support Email</p>
                        <p class="text-white font-bold">{{ $settings->email ?? 'support@example.com' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Official phone</p>
                        <p class="text-white font-bold">{{ $settings->phone_primary ?? '+880 1XXX-XXXXXX' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Location</p>
                        <p class="text-white font-bold">{{ $settings->address ?? 'Dhaka, Bangladesh' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        body {
            background-color: #0d0f1a;
        }
    </style>
@endsection
