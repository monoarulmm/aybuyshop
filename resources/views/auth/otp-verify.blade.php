@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#f4f6fa]">
        <div class="max-w-md w-full">
            <div class="bg-white border border-gray-200/80 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">

                <div class="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="text-center mb-8 relative">
                    <h2 class="text-3xl font-black text-black italic uppercase tracking-tighter">
                        Verify <span class="text-yellow-600">OTP</span>
                    </h2>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mt-2">
                        @if (filter_var(session('reset_identifier'), FILTER_VALIDATE_EMAIL))
                            আপনার ইমেইলে পাঠানো ৪ ডিজিটের কোডটি দিন
                        @else
                            আপনার ফোনে পাঠানো ৪ ডিজিটের কোডটি দিন
                        @endif
                    </p>
                </div>

                {{-- মেইন ওটিপি ফর্ম --}}
                <form action="{{ route('password.otp.verify') }}" method="POST" class="space-y-6 relative">
                    @csrf
                    <div>
                        <input type="text" name="otp" maxlength="4" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center text-2xl font-black tracking-[1em] text-black outline-none focus:bg-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/50 transition-all font-mono"
                            placeholder="0000" autofocus>
                        @error('otp')
                            <p class="text-red-500 text-center text-[9px] mt-2 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-black text-white font-black py-5 rounded-2xl hover:bg-gray-900 hover:shadow-[0_10px_20px_rgba(0,0,0,0.15)] transition-all uppercase tracking-widest text-xs active:scale-95">
                        Verify OTP
                    </button>
                </form>

                <div class="mt-8 text-center border-t border-gray-100 pt-6">
                    {{-- রিসেন্ড ফর্ম --}}
                    <form action="{{ route('password.otp.resend') }}" method="POST" id="resend-form">
                        @csrf
                        <input type="hidden" name="identifier" value="{{ session('reset_identifier') }}">

                        <p id="timer-text" class="text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            কোড পাননি? পুনরায় পাঠান <span id="timer" class="text-yellow-600">03:00</span> মিনিটের মধ্যে
                        </p>

                        <button type="submit" id="resend-btn" disabled
                            class="mt-3 text-gray-300 font-black uppercase text-[11px] tracking-tight disabled:opacity-50 disabled:cursor-not-allowed hover:text-yellow-600 transition-colors">
                            Resend New OTP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- কাউন্টডাউন স্ক্রিপ্ট (লাইট থিমের টেক্সট কালার সহ আপডেট করা) --}}
    <script>
        let timeLeft = 180; 
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resend-btn');

        const countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.innerText = "00:00";
                resendBtn.disabled = false;
                resendBtn.classList.remove('text-gray-300');
                resendBtn.classList.add('text-yellow-600', 'hover:text-yellow-700');
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