@extends('layouts.app')
@php
    $settings = $settings ?? \DB::table('site_settings')->first();
@endphp

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#f4f6fa]">
        <div class="max-w-md w-full">
            <div class="bg-white border border-gray-200/80 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">

                <!-- Subtle Premium Light Glow Effects -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="text-center mb-8 relative">
                    <h2 class="text-3xl font-black text-black italic uppercase tracking-tighter">
                        AyBuyShop<span class="text-yellow-600"></span> REGISTER
                    </h2>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mt-2">
                        নতুন অ্যাকাউন্ট তৈরি করুন
                    </p>
                </div>

                <form action="{{ route('register.normal.submit') }}" method="POST" class="space-y-5 relative">
                    @csrf

                    <!-- Name Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Full Name
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📝</span>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="John Doe">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Phone Number
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📞</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="017XXXXXXXX">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Email Address
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">✉️</span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="example@mail.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Address
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📍</span>
                            <input type="text" name="address" value="{{ old('address') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="Your full address">
                        </div>
                        @error('address')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Secure Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔒</span>
                            <input type="password" name="password" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔒</span>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-black text-white font-black py-5 rounded-2xl hover:bg-gray-900 hover:shadow-[0_10px_20px_rgba(0,0,0,0.15)] transition-all uppercase tracking-widest text-xs active:scale-95 mt-2">
                        Register Account
                    </button>
                </form>

                <!-- Footer Link -->
                <div class="mt-8 text-center">
                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                        ইতিমধ্যে অ্যাকাউন্ট আছে?
                        <a href="{{ route('login') }}" class="text-yellow-600 hover:text-yellow-700 hover:underline ml-1 transition">
                            লগইন করুন
                        </a>
                    </p>
                </div>
            </div>

            <!-- Copyright Notice -->
            <p class="mt-8 text-center text-gray-400 text-[9px] font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} AyBuyShop System • Secured Connection
            </p>
        </div>
    </div>
@endsection