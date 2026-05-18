<div x-data="{ sidebarOpen: false, searchOpen: false }">

    <template x-teleport="body">
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-[100]">

            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            <div class="absolute inset-y-0 left-0 flex max-w-full">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="w-80 bg-white dark:bg-[#0f172a] h-full shadow-2xl flex flex-col border-r dark:border-slate-800">

                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-black text-xl">L</span>
                            </div>
                            <span class="text-xl font-black dark:text-white tracking-tighter">LMS Platform</span>
                        </div>
                        <button @click="sidebarOpen = false"
                            class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @auth
                        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/20">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff"
                                    class="w-12 h-12 rounded-2xl border-2 border-white dark:border-slate-700 shadow-sm">
                                <div class="overflow-hidden">
                                    <p class="font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">মেইন মেনু
                        </p>

                        <a href="/"
                            class="flex items-center gap-4 px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-primary-50 dark:hover:bg-primary-500/10 hover:text-primary-600 rounded-2xl transition-all group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="font-bold">হোম পেজ</span>
                        </a>

                        <a href="/courses"
                            class="flex items-center gap-4 px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-primary-50 dark:hover:bg-primary-500/10 hover:text-primary-600 rounded-2xl transition-all group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13" />
                            </svg>
                            <span class="font-bold">সকল কোর্স</span>
                        </a>

                        <div class="pt-4">
                            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">অন্যান্য
                            </p>
                            <a href="/support"
                                class="block px-4 py-2 text-slate-500 dark:text-slate-400 font-bold">সাপোর্ট</a>
                            <a href="/blog"
                                class="block px-4 py-2 text-slate-500 dark:text-slate-400 font-bold">ব্লগ</a>
                        </div>
                    </nav>

                    <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-4 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-2xl font-bold flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    লগআউট
                                </button>
                            </form>
                        @else
                            <a href="/login"
                                class="w-full py-4 bg-primary-600 text-white rounded-2xl font-bold text-center block shadow-lg shadow-primary-500/30">লগইন
                                করুন</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="searchOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-110" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            class="fixed inset-0 z-[110] bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl p-6">

            <div class="flex items-center justify-between mb-10">
                <h2 class="text-2xl font-black dark:text-white">সার্চ করুন</h2>
                <button @click="searchOpen = false" class="p-3 bg-slate-100 dark:bg-slate-800 rounded-2xl">
                    <svg class="w-6 h-6 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="/search" method="GET" class="relative max-w-2xl mx-auto">
                <input type="text" name="query" autofocus placeholder="কী শিখতে চান?"
                    class="w-full pl-6 pr-16 py-5 bg-slate-100 dark:bg-slate-800 rounded-[2rem] border-none focus:ring-2 focus:ring-primary-600 text-lg dark:text-white outline-none">
                <button type="submit" class="absolute right-3 top-3 p-3 bg-primary-600 text-white rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </template>

    <div class="fixed bottom-0 left-0 right-0 z-[50] md:hidden px-4 pb-6">
        <nav
            class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl border border-white/20 dark:border-slate-800 shadow-2xl rounded-[2.5rem] h-20">
            <div class="flex justify-around items-center h-full">

                <button @click="sidebarOpen = true"
                    class="flex flex-col items-center justify-center flex-1 text-slate-400 dark:text-slate-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="text-[10px] font-black mt-1 uppercase">মেনু</span>
                </button>

                <a href="/courses"
                    class="flex flex-col items-center justify-center flex-1 {{ request()->is('courses*') ? 'text-primary-600' : 'text-slate-400 dark:text-slate-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13" />
                    </svg>
                    <span class="text-[10px] font-black mt-1 uppercase">কোর্স</span>
                </a>

                <div class="flex-1 flex justify-center -mt-10">
                    <button @click="searchOpen = true"
                        class="bg-primary-600 text-white p-5 rounded-full shadow-xl shadow-primary-500/40 ring-8 ring-[#f8fafc] dark:ring-dark-body active:scale-95 transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>

                <button @click="darkMode = !darkMode"
                    class="flex flex-col items-center justify-center flex-1 text-slate-400 dark:text-slate-500">
                    <div class="relative w-6 h-6">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-6 h-6 text-yellow-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black mt-1 uppercase">মোড</span>
                </button>

                <div class="flex-1">
                    @auth
                        <a href="/dashboard" class="flex flex-col items-center justify-center w-full">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff"
                                class="w-7 h-7 rounded-full border border-slate-200 dark:border-slate-700">
                            <span class="text-[10px] font-black mt-1 uppercase text-slate-400">প্রোফাইল</span>
                        </a>
                    @else
                        <a href="/login" class="flex flex-col items-center justify-center w-full text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span class="text-[10px] font-black mt-1 uppercase">লগইন</span>
                        </a>
                    @endauth
                </div>

            </div>
        </nav>
    </div>
</div>
