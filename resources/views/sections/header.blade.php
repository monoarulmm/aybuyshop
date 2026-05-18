<header x-data="{ profileOpen: false, mobileSearch: false, userMenu: false }"
    class="sticky top-0 z-40 w-full backdrop-blur-md bg-white/90 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20 gap-4">

            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-extrabold tracking-tight flex items-center gap-1.5 group">
                    <span
                        class="bg-primary-600 text-white px-2 py-0.5 rounded-lg group-hover:rotate-6 transition-transform">IT</span>
                    <span class="text-slate-800 dark:text-white">Tech</span>
                </a>
            </div>

            <div class="hidden lg:flex flex-1 max-w-md mx-4">
                <div class="relative w-full group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-primary-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm transition-all"
                        placeholder="কোর্স সার্চ করুন...">
                </div>
            </div>

            <nav class="hidden md:flex items-center space-x-6 xl:space-x-8">
                <a href="/"
                    class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition">কোর্সসমূহ</a>
                <a href="bootcamp"
                    class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition">বুটক্যাম্প</a>

                <button @click="darkMode = !darkMode"
                    class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-yellow-400 transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-cloak>
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z">
                        </path>
                    </svg>
                </button>

                <div class="h-5 w-px bg-slate-200 dark:bg-slate-700"></div>

                @auth
                    <div class="relative">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                            class="flex items-center gap-3 focus:outline-none">
                            <div
                                class="w-10 h-10 rounded-full border-2 border-primary-500/20 p-0.5 hover:border-primary-500 transition">
                                <img class="w-full h-full rounded-full object-cover"
                                    src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=2563eb&color=fff">
                            </div>
                        </button>
                        <div x-show="profileOpen" x-cloak x-transition
                            class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 py-2">
                            <a href="/dashboard"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">ড্যাশবোর্ড</a>
                            <form method="POST" action="/logout">@csrf
                                <button
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition">লগআউট</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4">
                        <a href="/login"
                            class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-primary-600 transition">লগইন</a>
                        <a href="/register"
                            class="bg-primary-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-primary-500/25 active:scale-95 transition">জয়েন
                            করুন</a>
                    </div>
                @endauth
            </nav>

            <div class="flex items-center space-x-2 md:hidden">
                <button @click="mobileSearch = !mobileSearch"
                    class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                @auth
                    <button @click="userMenu = !userMenu" class="w-9 h-9 rounded-full border-2 border-primary-500/20 p-0.5">
                        <img class="w-full h-full rounded-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=2563eb&color=fff">
                    </button>
                @else
                    <a href="/login" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-bold">লগইন</a>
                @endauth
            </div>
        </div>

        <div x-show="mobileSearch" x-cloak x-transition class="md:hidden pb-4">
            <div class="relative">
                <input type="text" autofocus
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm"
                    placeholder="সার্চ করুন...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center"><svg class="h-4 w-4 text-slate-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg></div>
            </div>
        </div>

        <div x-show="userMenu" x-cloak x-transition
            class="md:hidden absolute right-4 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 py-2">
            <a href="/dashboard" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300">ড্যাশবোর্ড</a>
            <form method="POST" action="/logout">@csrf
                <button class="w-full text-left px-4 py-2 text-sm text-red-500">লগআউট</button>
            </form>
        </div>
    </div>
</header>
