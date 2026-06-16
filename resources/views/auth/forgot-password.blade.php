@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#f4f6fa]">
        <div class="max-w-md w-full">
            <div class="bg-white border border-gray-200/80 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">

                <div class="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="text-center mb-8 relative">
                    <h2 class="text-3xl font-black text-black italic uppercase tracking-tighter">
                        Reset <span class="text-yellow-600">Password</span>
                    </h2>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mt-2">
                        আপনার ইমেইল বা ফোন নম্বর দিন
                    </p>
                </div>

                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 text-[11px] font-bold p-4 rounded-2xl mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.request') }}" method="POST" class="space-y-6 relative">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">
                            Email or Phone
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">👤</span>
                            <input type="text" name="identifier" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="example@mail.com or 017XXXXXXXX">
                        </div>
                        @error('identifier')
                            <p class="text-red-500 text-[9px] mt-1 ml-1 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-black text-white font-black py-5 rounded-2xl hover:bg-gray-900 hover:shadow-[0_10px_20px_rgba(0,0,0,0.15)] transition-all uppercase tracking-widest text-xs active:scale-95">
                        Send Reset Request
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection