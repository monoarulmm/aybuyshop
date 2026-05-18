@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#0f111a]">
        <div class="max-w-md w-full">
            <div
                class="bg-[#161925] border border-white/10 shadow-2xl rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">

                <div class="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="text-center mb-10 relative">
                    <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">
                        TaKa ID<span class="text-yellow-500">24</span> LOGIN
                    </h2>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mt-2">আপনার অ্যাকাউন্ট অ্যাক্সেস
                        করুন</p>
                </div>

                @if (session('error'))
                    <div
                        class="bg-red-500/10 border border-red-500/50 text-red-500 text-[11px] font-bold p-4 rounded-2xl mb-6 flex items-center gap-3">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div
                        class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-500 text-[11px] font-bold p-4 rounded-2xl mb-6 flex items-center gap-3">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6 relative">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Email
                            or Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">👤</span>
                            <input type="text" name="login_identity" value="{{ old('login_identity') }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 pl-12 text-white focus:ring-2 focus:ring-yellow-500 outline-none transition-all placeholder:text-gray-700"
                                placeholder="example@mail.com or 017XXXXXXXX">
                        </div>
                        @error('login_identity')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Secure
                            Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">🔒</span>
                            <input type="password" name="password" required
                                class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 pl-12 text-white focus:ring-2 focus:ring-yellow-500 outline-none transition-all placeholder:text-gray-700"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-white/10 bg-white/5 text-yellow-500 focus:ring-0">
                            <span
                                class="text-[10px] font-black text-gray-500 uppercase group-hover:text-gray-300 transition">আমাকে
                                মনে রাখুন</span>
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-[10px] font-black text-yellow-500 uppercase hover:underline">পাসওয়ার্ড
                            ভুলে গেছেন?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-yellow-500 text-black font-black py-5 rounded-2xl hover:shadow-[0_0_25px_rgba(234,179,8,0.4)] transition-all uppercase tracking-widest text-xs active:scale-95">
                        Sign In Now
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-[10px] text-gray-600 font-black uppercase tracking-widest">
                        আপনার কি অ্যাকাউন্ট নেই?
                        <a href="{{ route('register') }}" class="text-yellow-500 hover:underline ml-1">রেজিস্ট্রেশন করুন</a>
                    </p>
                </div>
            </div>

            <p class="mt-8 text-center text-gray-600 text-[9px] font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} TaKa ID24 System • Secured Connection
            </p>
        </div>
    </div>
@endsection
