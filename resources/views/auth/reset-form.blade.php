@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 bg-[#0f111a]">
        <div class="max-w-md w-full bg-[#161925] border border-white/10 p-8 rounded-[2.5rem]">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white italic uppercase tracking-widest">New <span
                        class="text-yellow-500">Password</span></h2>
                <p class="text-gray-500 text-[10px] font-bold uppercase mt-2">নতুন পাসওয়ার্ড সেট করুন</p>
            </div>

            <form action="{{ route('password.reset.new') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] text-gray-500 font-black uppercase ml-1">New Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none shadow-inner"
                        placeholder="••••••••">
                </div>

                <div>
                    <label class="text-[10px] text-gray-500 font-black uppercase ml-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-[9px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-black font-black py-4 rounded-2xl uppercase text-xs">
                    Update Password
                </button>
            </form>
        </div>
    </div>
@endsection
