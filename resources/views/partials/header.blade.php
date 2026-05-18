<header class="fixed w-full z-40 glass-nav border-b border-white/5 shadow-2xl">
    <div class="flex items-center justify-between h-16 px-4 lg:px-10">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true"
                class="p-2 text-gray-400 hover:text-white lg:hidden bg-white/5 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <a href="/">

                <img src="{{ asset('storage/' . $settings->logo) }}" class="h-10 md:h-12 object-contain" alt="Logo">

            </a>
        </div>

        <div class="flex items-center gap-4">
            @guest
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold text-gray-400 hover:text-white transition px-3">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-yellow-500 text-black text-xs font-black px-5 py-2.5 rounded-xl uppercase hover:scale-105 transition">Join
                        Now</a>
                </div>
            @endguest

            @auth
                @php
                    $userId = Auth::id();
                    $earnings = \DB::table('user_earnings')->where('user_id', $userId)->sum('amount');
                    $withdrawn = \DB::table('withdrawals')
                        ->where('user_id', $userId)
                        ->whereIn('status', ['pending', 'approved'])
                        ->sum('amount');
                    $currentBalance = $earnings - $withdrawn;
                @endphp

                <div class="hidden sm:flex items-center gap-2 bg-white/5 rounded-2xl px-4 py-2 border border-white/10">
                    <div
                        class="w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center text-[10px] text-black font-bold">
                        ৳</div>
                    <span
                        class="text-yellow-500 font-black text-xs tracking-wide">{{ number_format($currentBalance, 2) }}</span>
                </div>

                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false"
                        class="flex items-center gap-2 p-1 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition">
                        <img src="{{ Auth::user()->profile_images ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=EAB308&color=000' }}"
                            class="w-8 h-8 rounded-xl object-cover" alt="User">
                        <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="userMenuOpen" x-cloak
                        class="absolute right-0 mt-3 w-56 bg-[#161925] border border-white/10 rounded-[1.5rem] shadow-2xl py-2 z-50">
                        <div class="px-4 py-3 border-b border-white/5">
                            <p class="text-[10px] text-yellow-500 font-black uppercase tracking-widest">Available Balance
                            </p>
                            <p class="text-lg font-bold text-white">৳ {{ number_format($currentBalance, 2) }}</p>
                        </div>
                        <div class="p-2">
                            <a href="/profile"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white rounded-xl transition"><span>👤</span>
                                Profile</a>
                            <a href="/upgrade"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white rounded-xl transition"><span>💎</span>
                                Subscription</a>
                        </div>
                        <div class="p-2 border-t border-white/5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-400 hover:bg-red-500/10 rounded-xl transition">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
