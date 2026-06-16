@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#f4f6fa]">
        <div class="max-w-md w-full">
            <div class="bg-white border border-gray-200/80 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">

                <!-- Subtle Premium Light Glow Effects -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="text-center mb-10 relative">
                    <h2 class="text-3xl font-black text-black italic uppercase tracking-tighter">
                        AyBuyShop<span class="text-yellow-600"></span> LOGIN
                    </h2>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mt-2">
                        আপনার অ্যাকাউন্ট অ্যাক্সেস করুন
                    </p>
                </div>

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 text-[11px] font-bold p-4 rounded-2xl mb-6 flex items-center gap-3">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 text-[11px] font-bold p-4 rounded-2xl mb-6 flex items-center gap-3">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6 relative">
                    @csrf

                    <!-- Email/Phone Input -->
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Email or Phone Number
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">👤</span>
                            <input type="text" name="login_identity" value="{{ old('login_identity') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="example@mail.com or 017XXXXXXXX">
                        </div>
                        @error('login_identity')
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
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-gray-300 bg-gray-50 text-yellow-600 focus:ring-0 focus:ring-offset-0">
                            <span class="text-[10px] font-black text-gray-500 uppercase group-hover:text-black transition">
                                আমাকে মনে রাখুন
                            </span>
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-[10px] font-black text-yellow-600 uppercase hover:text-yellow-700 hover:underline transition">
                            পাসওয়ার্ড ভুলে গেছেন?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-black text-white font-black py-5 rounded-2xl hover:bg-gray-900 hover:shadow-[0_10px_20px_rgba(0,0,0,0.15)] transition-all uppercase tracking-widest text-xs active:scale-95">
                        Sign In Now
                    </button>
                </form>

                <!-- Footer Link -->
                <div class="mt-10 text-center">
                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                        আপনার কি অ্যাকাউন্ট নেই?
                        <a href="{{ route('register') }}" class="text-yellow-600 hover:text-yellow-700 hover:underline ml-1 transition">
                            রেজিস্ট্রেশন করুন
                        </a>
                    </p>
                </div>
            </div>

            <!-- Copyright Notice -->
            <p class="mt-8 text-center text-gray-400 text-[9px] font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} Aybuyshop System • Secured Connection
            </p>
        </div>
    </div>
@endsection