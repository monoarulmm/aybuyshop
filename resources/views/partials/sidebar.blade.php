<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0f111a] border-r border-white/5 transition-transform duration-300 lg:translate-x-0 lg:static shadow-2xl flex flex-col">

    <!-- Logo Section -->
    <div class="flex items-center justify-between p-6 border-b border-white/5">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/20">
                <span class="text-white font-black text-xl italic">M</span>
            </div>
            <div class="flex flex-col">
                <span class="text-white font-black leading-none tracking-tighter uppercase italic text-lg">Main <span
                        class="text-yellow-500">Menu</span></span>
                <span class="text-[9px] text-gray-500 font-bold tracking-[0.2em] uppercase">Navigation Center</span>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-500 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-6 space-y-8">

        <!-- Profile Section -->
        @auth
            <div class="relative group px-2">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2rem] blur opacity-20 group-hover:opacity-40 transition duration-500">
                </div>
                <div class="relative bg-[#161926] rounded-[1.8rem] p-5 border border-white/5 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center font-bold text-indigo-400 shadow-inner">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-indigo-400 font-black uppercase tracking-widest leading-none mb-1">
                                {{ Auth::user()->role === 'admin' ? '🛡️ Admin' : Auth::user()->type ?? '💎 Member' }}
                            </p>
                            <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 w-2/3 shadow-[0_0_8px_#6366f1]"></div>
                    </div>
                </div>
            </div>
        @endauth

        <!-- Navigation Links -->
        <nav class="space-y-1.5 px-2">
            <p class="px-5 text-[10px] text-gray-600 uppercase font-black tracking-[0.2em] mb-3">Core Platform</p>

            <!-- Public / Common Links -->
            <a href="/"
                class="group flex items-center px-5 py-3.5 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->is('/') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="mr-4 text-xl group-hover:scale-110 transition-transform">📺</span>
                Watch & Earn
            </a>
            <a href="/cart"
                class="flex flex-col items-center justify-center p-4 rounded-2xl text-orange-500 bg-orange-500/5 border border-orange-500/10 hover:bg-orange-500/10 transition group">
                <span class="text-xl mb-1 group-hover:scale-110 transition-transform">🛒</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Cart</span>
            </a>
            @auth
                <!-- Admin Console Section -->
                <!-- Admin Console Section -->
                @if (Auth::user()->role === 'admin')
                    <div class="mt-8 pt-6 border-t border-white/5">
                        <p
                            class="px-5 text-[10px] text-red-500/70 uppercase font-black tracking-[0.2em] mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Administration
                        </p>

                        <div class="bg-red-500/5 rounded-[2rem] p-3 border border-red-500/10 space-y-1">

                            <a href="/"
                                class="group flex items-center px-5 py-3.5 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->is('/') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="mr-4 text-xl group-hover:scale-110 transition-transform"></span>
                                Dashboard
                            </a>
                            <!-- User & Payout Management -->
                            <a href="/admin/users"
                                class="flex items-center px-4 py-3 text-sm font-bold rounded-xl text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition group">
                                <span class="mr-3 opacity-80 group-hover:scale-125 transition-transform">👥</span> Users
                                List
                            </a>

                            <a href="/admin/withdraw"
                                class="flex items-center px-4 py-3 text-sm font-bold rounded-xl text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition group">
                                <span class="mr-3 opacity-80 group-hover:scale-125 transition-transform">💰</span> Payout
                                Requests
                            </a>

                            <!-- Order Management (আপনার কাঙ্ক্ষিত লিংক) -->
                            <a href="/orders"
                                class="flex items-center px-4 py-3 text-sm font-bold rounded-xl text-amber-500/80 bg-amber-500/5 border border-amber-500/10 hover:bg-amber-500/10 transition group">
                                <span class="mr-3 group-hover:animate-pulse">📥</span> Customer Orders
                            </a>

                            <div class="h-px bg-white/5 my-2 mx-4"></div>

                            <!-- Shop Management -->
                            <a href="/admin/products"
                                class="flex items-center px-4 py-3 text-sm font-bold rounded-xl text-emerald-500/70 hover:bg-emerald-500/10 hover:text-emerald-400 transition group">
                                <span class="mr-3 opacity-80 group-hover:rotate-12 transition-transform">📦</span> Inventory
                            </a>

                            <a href="/admin/categories"
                                class="flex items-center px-4 py-3 text-sm font-bold rounded-xl text-emerald-500/70 hover:bg-emerald-500/10 hover:text-emerald-400 transition group">
                                <span class="mr-3 opacity-80 group-hover:rotate-12 transition-transform">📂</span>
                                Categories
                            </a>

                            <!-- System Settings -->
                            <a href="/admin/settings"
                                class="flex items-center px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-white/5 hover:text-white transition group border-t border-white/5 mt-2 pt-3">
                                <span
                                    class="mr-3 opacity-80 group-hover:rotate-90 transition-transform duration-500">⚙️</span>
                                Settings
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Regular User Section -->
                @if (Auth::user()->role !== 'admin')
                    <div class="space-y-1.5">
                        <a href="/pakages"
                            class="group flex items-center px-5 py-3.5 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->is('pakages') ? 'bg-white/10 text-white shadow-inner' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <span class="mr-4 text-xl group-hover:rotate-12 transition-transform">🏠</span>
                            Packages
                        </a>

                        <a href="/withdraw"
                            class="group flex items-center px-5 py-3.5 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->is('withdraw') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <span class="mr-4 text-xl group-hover:animate-bounce">💵</span>
                            Withdraw Funds
                        </a>

                        <!-- Marketplace Group -->
                        <div class="mt-6 pt-4 border-t border-white/5">
                            <p class="px-5 text-[10px] text-gray-600 uppercase font-black tracking-[0.2em] mb-3">Shop &
                                Orders</p>

                            <a href="/shop"
                                class="flex items-center px-5 py-4 text-sm font-black rounded-2xl text-emerald-500 bg-emerald-500/5 border border-emerald-500/10 hover:bg-emerald-500/10 hover:border-emerald-500/30 transition-all mb-3 group">
                                <svg class="w-5 h-5 mr-4 group-hover:-rotate-12 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                কেনাকেটা
                            </a>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="/cart"
                                    class="flex flex-col items-center justify-center p-4 rounded-2xl text-orange-500 bg-orange-500/5 border border-orange-500/10 hover:bg-orange-500/10 transition group">
                                    <span class="text-xl mb-1 group-hover:scale-110 transition-transform">🛒</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Cart</span>
                                </a>
                                <a href="/my-orders"
                                    class="flex flex-col items-center justify-center p-4 rounded-2xl text-purple-500 bg-purple-500/5 border border-purple-500/10 hover:bg-purple-500/10 transition group">
                                    <span class="text-xl mb-1 group-hover:scale-110 transition-transform">📦</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Orders</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Guest Access Section -->
            @guest
                <div class="pt-6 space-y-3">
                    <a href="/login"
                        class="flex items-center justify-center w-full px-5 py-4 text-sm font-black rounded-2xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 transition shadow-lg shadow-blue-600/20 uppercase tracking-widest">
                        Sign In Now
                    </a>
                    <a href="/shop"
                        class="flex items-center justify-center w-full px-5 py-4 text-sm font-bold rounded-2xl text-gray-400 border border-white/5 hover:bg-white/5 transition">
                        কেনাকেটা
                    </a>
                </div>
            @endguest
        </nav>
    </div>

    <!-- Bottom Footer (Optional) -->
    <div class="p-6 border-t border-white/5">
        <p class="text-[10px] text-gray-600 font-bold text-center uppercase tracking-widest leading-relaxed">
            &copy; 2026 Pro Platform<br>
            <span class="font-medium">Version 4.0.2</span>
        </p>
    </div>

</aside>

<style>
    /* Custom Thin Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.1);
    }
</style>
