@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 bg-[#0f111a]">
        <div class="max-w-md w-full bg-[#161925] border border-white/10 p-8 rounded-[2.5rem] shadow-2xl">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white italic uppercase tracking-widest">Reset <span
                        class="text-yellow-500">Password</span></h2>
                <p class="text-gray-500 text-[10px] font-bold uppercase mt-2">আপনার ইমেইল বা ফোন নম্বর দিন</p>
            </div>

            @if (session('status'))
                <div
                    class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-500 text-[11px] p-4 rounded-2xl mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.request') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] text-gray-500 font-black uppercase ml-1">Email or Phone</label>
                    <input type="text" name="identifier" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none transition-all"
                        placeholder="example@mail.com or 017XXXXXXXX">
                    @error('identifier')
                        <p class="text-red-500 text-[9px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-black font-black py-4 rounded-2xl hover:shadow-lg transition-all uppercase text-xs">
                    Send Reset Request
                </button>
            </form>
        </div>
    </div>
@endsection
