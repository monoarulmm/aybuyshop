{{-- Hero Section Start --}}
<section
    class="relative w-full mb-12 overflow-hidden rounded-[2.5rem] bg-[#161925] border border-white/5 shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
    <div class="relative min-h-[350px] lg:min-h-[500px] flex items-center px-6 lg:px-16 overflow-hidden">

        {{-- Background Layer with Parallax Effect --}}
        <div class="absolute inset-0 z-0">

            <img src=""
                class="w-full h-full object-cover opacity-30 scale-105 transition-transform duration-[2s] hover:scale-100"
                alt="Banner">


            {{-- Professional Gradient Overlays --}}
            <div class="absolute inset-0 bg-gradient-to-r from-[#0f111a] via-[#0f111a]/90 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f111a] via-transparent to-transparent"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-3xl py-12">
            {{-- Badge --}}
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 mb-6 animate-fade-in">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-yellow-500">New Opportunities
                    Waiting</span>
            </div>

            <h1 class="text-4xl lg:text-7xl font-black text-white leading-[1.1] mb-6 tracking-tighter italic uppercase">
                Explore the <span class="text-yellow-500 drop-shadow-[0_0_15px_rgba(234,179,8,0.4)]">Next Gen</span>
                <br>
                Digital <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">Rewards</span>
            </h1>

            <p class="text-gray-400 text-sm lg:text-lg mb-10 leading-relaxed max-w-lg font-medium">
                আপনার পছন্দের কন্টেন্ট দেখুন, টাস্ক পূরণ করুন এবং জিতে নিন <span
                    class="text-white border-b border-yellow-500/50 italic">নিশ্চিত বোনাস কয়েন</span>। আজই শুরু করুন
                আপনার নতুন আয়ের যাত্রা।
            </p>

            <div class="flex flex-wrap gap-5">
                <a href="{{ route('register') }}"
                    class="group relative px-10 py-4 bg-yellow-500 text-black font-black rounded-2xl transition-all duration-300 hover:scale-105 active:scale-95 shadow-[0_10px_25px_rgba(234,179,8,0.3)] flex items-center gap-3">
                    <span>GET STARTED NOW</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>

                <a href="#how-it-works"
                    class="px-10 py-4 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl border border-white/10 transition-all backdrop-blur-md hover:border-white/20 flex items-center gap-2">
                    Learn More
                </a>
            </div>
        </div>

        {{-- Floating Decorative Elements --}}
        <div class="hidden lg:block absolute right-[-5%] top-1/2 -translate-y-1/2">
            <div class="relative w-[400px] h-[400px]">
                {{-- Glow Effect --}}
                <div class="absolute inset-0 bg-yellow-500/20 rounded-full blur-[120px] animate-pulse"></div>

                {{-- Abstract Glass Card --}}
                <div
                    class="absolute top-10 right-10 w-48 h-56 bg-white/5 backdrop-blur-2xl rounded-[3rem] border border-white/10 rotate-12 animate-float shadow-2xl flex flex-col items-center justify-center p-6">
                    <div
                        class="w-16 h-16 bg-yellow-500 rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-lg shadow-yellow-500/20">
                        💰</div>
                    <div class="h-2 w-20 bg-white/10 rounded-full mb-2"></div>
                    <div class="h-2 w-12 bg-white/5 rounded-full"></div>
                </div>

                <div
                    class="absolute bottom-10 left-0 w-40 h-40 bg-indigo-500/10 backdrop-blur-xl rounded-[2.5rem] border border-white/5 -rotate-12 animate-float-slow shadow-2xl flex flex-col items-center justify-center">
                    <div class="text-2xl">🚀</div>
                    <p class="text-[10px] font-black text-indigo-400 mt-2">FAST EARN</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(12deg);
        }

        50% {
            transform: translateY(-20px) rotate(15deg);
        }
    }

    @keyframes float-slow {

        0%,
        100% {
            transform: translateY(0px) rotate(-12deg);
        }

        50% {
            transform: translateY(15px) rotate(-10deg);
        }
    }

    .animate-float {
        animation: float 5s ease-in-out infinite;
    }

    .animate-float-slow {
        animation: float-slow 7s ease-in-out infinite;
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
{{-- Hero Section End --}}
