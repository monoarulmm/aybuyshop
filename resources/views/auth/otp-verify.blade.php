@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 bg-[#0f111a]">
        <div class="max-w-md w-full bg-[#161925] border border-white/10 p-8 rounded-[2.5rem]">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white italic uppercase tracking-widest">Verify <span
                        class="text-yellow-500">OTP</span></h2>
                <p class="text-gray-500 text-[10px] font-bold uppercase mt-2">
                    @if (filter_var(session('reset_identifier'), FILTER_VALIDATE_EMAIL))
                        আপনার ইমেইলে পাঠানো ৪ ডিজিটের কোডটি দিন
                    @else
                        আপনার ফোনে পাঠানো ৪ ডিজিটের কোডটি দিন
                    @endif
                </p>
            </div>

            {{-- মেইন ওটিপি ফর্ম --}}
            <form action="{{ route('password.otp.verify') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="text" name="otp" maxlength="4" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-center text-2xl font-black tracking-[1em] text-yellow-500 outline-none focus:border-yellow-500"
                        placeholder="0000" autofocus>
                    @error('otp')
                        <p class="text-red-500 text-center text-[9px] mt-2 font-bold uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-black font-black py-4 rounded-2xl uppercase text-xs hover:bg-yellow-400 transition-all shadow-lg shadow-yellow-500/20">
                    Verify OTP
                </button>
            </form>

            <div class="mt-8 text-center border-t border-white/5 pt-6">
                {{-- রিসেন্ড ফর্ম --}}
                <form action="{{ route('password.otp.resend') }}" method="POST" id="resend-form">
                    @csrf
                    <input type="hidden" name="identifier" value="{{ session('reset_identifier') }}">

                    <p id="timer-text" class="text-gray-600 text-[10px] font-bold uppercase tracking-widest">
                        কোড পাননি? পুনরায় পাঠান <span id="timer" class="text-yellow-500">03:00</span> মিনিটের মধ্যে
                    </p>

                    <button type="submit" id="resend-btn" disabled
                        class="mt-3 text-gray-500 font-black uppercase text-[11px] tracking-tighter disabled:opacity-30 disabled:cursor-not-allowed hover:text-yellow-500 transition-colors">
                        Resend New OTP
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- কাউন্টডাউন স্ক্রিপ্ট --}}
    <script>
        let timeLeft = 180; // ৫ মিনিট = ৩০০ সেকেন্ড
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resend-btn');

        const countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.innerText = "00:00";
                resendBtn.disabled = false;
                resendBtn.classList.remove('text-gray-500');
                resendBtn.classList.add('text-yellow-500');
            } else {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerElement.innerText =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timeLeft--;
            }
        }, 1000);
    </script>
@endsection
