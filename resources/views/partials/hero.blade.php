{{-- Hero Section Start --}}
<section
    class="relative w-full mb-8 overflow-hidden rounded-[2.5rem] bg-white border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)]">
    <div class="relative min-h-[380px] lg:min-h-[480px] flex items-center px-6 lg:px-16 overflow-hidden">

        {{-- Background Layer with Parallax Effect --}}
        <div class="absolute inset-0 z-0 bg-[#f8fafc]">
            {{-- ব্যানার ইমেজ প্লেসহোল্ডার --}}
            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2426&auto=format&fit=cover"
                class="w-full h-full object-cover opacity-10 scale-105 transition-transform duration-[2s] hover:scale-100"
                alt="E-commerce Banner">

            {{-- Professional Soft Gradients for Light Mode --}}
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-2xl py-8">
            {{-- New Season Badge --}}
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 mb-5 animate-fade-in">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600">Exclusive Shopping Season</span>
            </div>

            {{-- Title with E-commerce Tone --}}
            <h1 class="text-3xl lg:text-6xl font-black text-gray-900 leading-[1.15] mb-5 tracking-tight uppercase">
                Discover the <span class="text-blue-600 drop-shadow-[0_0_15px_rgba(37,99,235,0.1)]">Best Deals</span>
                <br>
                Online <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-gray-700 to-gray-400">Shopping</span>
            </h1>

            {{-- Bengali E-commerce Description --}}
            <p class="text-gray-500 text-xs lg:text-sm mb-8 leading-relaxed max-w-md font-medium">
                আপনার পছন্দের সেরা ব্র্যান্ডের অথেন্টিক পণ্যগুলো খুঁজুন এক জায়গায়। প্রতিটি অর্ডারে পাচ্ছেন <span
                    class="text-blue-600 border-b border-blue-200 font-bold italic">দ্রুত ডেলিভারি ও নিশ্চিত ডিসকাউন্ট</span>। আজই শুরু হোক আপনার স্মার্ট শপিং।
            </p>

            {{-- Buttons --}}
            <div class="flex flex-wrap gap-4">
                <a href="#products-grid"
                    class="group relative px-8 py-3.5 bg-gray-950 text-white text-xs font-black rounded-2xl transition-all duration-300 hover:bg-blue-600 hover:scale-105 active:scale-95 shadow-[0_10px_25px_rgba(0,0,0,0.08)] flex items-center gap-3">
                    <span>SHOP NOW</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>

                <a href="#offers"
                    class="px-8 py-3.5 bg-white hover:bg-gray-50 text-gray-800 text-xs font-bold rounded-2xl border border-gray-200/80 transition-all shadow-sm flex items-center gap-2">
                    View Offers
                </a>
            </div>
        </div>

        {{-- Floating Decorative Elements (Shopping Themed) --}}
        <div class="hidden lg:block absolute right-[2%] top-1/2 -translate-y-1/2">
            <div class="relative w-[380px] h-[380px]">
                {{-- Light Soft Glow --}}
                <div class="absolute inset-0 bg-blue-400/10 rounded-full blur-[100px] animate-pulse"></div>

                {{-- Shopping Bag Glass Card --}}
                <div
                    class="absolute top-8 right-12 w-44 h-52 bg-white/70 backdrop-blur-md rounded-[2.5rem] border border-gray-200/50 rotate-12 animate-float shadow-[0_15px_35px_rgba(0,0,0,0.03)] flex flex-col items-center justify-center p-6">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 border border-blue-100 shadow-sm">
                        🛍️
                    </div>
                    <div class="h-2 w-20 bg-gray-200 rounded-full mb-2"></div>
                    <div class="h-2 w-12 bg-gray-100 rounded-full"></div>
                </div>

                {{-- Super Sale Glass Card --}}
                <div
                    class="absolute bottom-8 left-4 w-36 h-36 bg-white/70 backdrop-blur-md rounded-[2rem] border border-gray-200/40 -rotate-12 animate-float-slow shadow-[0_15px_35px_rgba(0,0,0,0.03)] flex flex-col items-center justify-center">
                    <div class="text-2xl">🔥</div>
                    <p class="text-[10px] font-black text-gray-900 mt-2 uppercase tracking-wider">BIG SALE</p>
                    <span class="text-[9px] font-bold text-blue-600 mt-0.5">Up to 50% Off</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(12deg);
        }
        50% {
            transform: translateY(-15px) rotate(14deg);
        }
    }

    @keyframes float-slow {
        0%, 100% {
            transform: translateY(0px) rotate(-12deg);
        }
        50% {
            transform: translateY(12px) rotate(-10deg);
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-float-slow {
        animation: float-slow 8s ease-in-out infinite;
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
{{-- Hero Section End --}}