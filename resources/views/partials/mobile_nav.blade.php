<div class="lg:hidden fixed bottom-6 left-4 right-4 z-50">
    <div
        class="bg-[#161925]/95 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] h-20 flex items-center justify-between px-6 shadow-[0_20px_50px_rgba(0,0,0,0.5)]">

        {{-- 1. Home --}}
        <a href="/"
            class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->is('/') ? 'text-yellow-500 scale-110' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a2 2 0 002 2h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a2 2 0 002-2v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span class="text-[8px] font-black uppercase tracking-tighter">Home</span>
        </a>

        {{-- 2. Shop --}}
        <a href="/shop"
            class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->is('shop*') ? 'text-yellow-500 scale-110' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="text-[8px] font-black uppercase tracking-tighter">Shop</span>
        </a>

        {{-- 3. Center Menu Button (Sidebar Trigger) --}}
        <div class="relative -mt-14">
            <button @click="sidebarOpen = true"
                class="w-16 h-16 bg-gradient-to-tr from-yellow-600 to-yellow-400 rounded-full flex items-center justify-center border-[6px] border-[#0d0f1a] shadow-[0_10px_20px_rgba(202,138,4,0.3)] active:scale-90 transition-transform">
                <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        {{-- 4. My Orders --}}
        <a href="/my-orders"
            class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->is('my-orders*') ? 'text-yellow-500 scale-110' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                </path>
            </svg>
            <span class="text-[8px] font-black uppercase tracking-tighter">Orders</span>
        </a>

        {{-- 5. Wallet/Withdraw --}}
        <a href="/withdraw"
            class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->is('withdraw*') ? 'text-yellow-500 scale-110' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-[8px] font-black uppercase tracking-tighter">Wallet</span>
        </a>

    </div>
</div>
