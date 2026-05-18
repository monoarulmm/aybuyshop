<!DOCTYPE html>
<html lang="bn">

@php
    // যদি লেআউট ফাইল থেকে $settings না আসে, তবে এখানে কোয়েরি করে নেওয়া ভালো
    $settings = $settings ?? \DB::table('site_settings')->first();
@endphp

<head>
    <meta charset="UTF-8">
    {{-- মোবাইল জুম এবং ডিজাইন ফিক্সড রাখার জন্য নিচের মেটা ট্যাগটি যুক্ত করা হয়েছে --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings->site_name ?? 'TaKa ID 24' }} | Watch & Earn</title>

    {{-- ফ্যাভিকন পাথ কারেকশন: সরাসরি public/uploads থেকে লোড হবে --}}
    @if ($settings && $settings->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Alpine.js x-cloak স্টাইল সহ যুক্ত করা হয়েছে --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            scroll-behavior: smooth;
            {{-- মোবাইলে ডানে-বামে নড়াচড়া বন্ধ করতে --}} overflow-x: hidden;
            touch-action: pan-x pan-y;
        }

        [x-cloak] {
            display: none !important;
        }

        .glass-nav {
            background: rgba(15, 17, 26, 0.95);
            backdrop-filter: blur(12px);
        }

        .premium-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .coin-shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(90deg, #facc15 0%, #eab308 50%, #facc15 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes shimmer {
            to {
                background-position: 200% center;
            }
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* স্মুথ স্ক্রলিং এর জন্য */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-[#0a0c14] text-gray-200" x-data="{ sidebarOpen: false, showAd: true }">

    {{-- @include('partials.promo-modal'); --}}

    <header class="fixed w-full z-40 glass-nav border-b border-white/5 shadow-2xl">
        <div class="flex items-center justify-between h-16 px-4 lg:px-10">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true"
                    class="p-2 text-gray-400 hover:text-white lg:hidden bg-white/5 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </button>
                <a href="/">

                    <img src="{{ asset('storage/' . $settings->logo) }}" class="h-10 md:h-12 object-contain"
                        alt="Logo">

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
                            <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="userMenuOpen" x-cloak
                            class="absolute right-0 mt-3 w-56 bg-[#161925] border border-white/10 rounded-[1.5rem] shadow-2xl py-2 z-50">
                            <div class="px-4 py-3 border-b border-white/5">
                                <p class="text-[10px] text-yellow-500 font-black uppercase tracking-widest">Available
                                    Balance
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

    <div class="flex pt-16 h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0f111a] border-r border-white/5 transition-transform duration-300 lg:translate-x-0 lg:static">

            <div class="flex items-center justify-between p-6 border-b border-white/5 lg:hidden">
                <span class="text-lg font-bold text-yellow-500 uppercase tracking-widest">Main Menu</span>
                <button @click="sidebarOpen = false" class="text-gray-400">✕</button>
            </div>

            @auth
                <div class="p-6">
                    <div class="premium-card rounded-3xl p-5 relative overflow-hidden">
                        <p class="text-[10px] text-indigo-300 uppercase font-black tracking-widest mb-1">
                            {{ Auth::user()->role === 'admin' ? 'Administrator' : Auth::user()->type ?? 'Basic' }}
                        </p>
                        <p class="text-lg font-bold text-white">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            @endauth

            <nav class="px-4 space-y-1 mt-4">
                {{-- সবার জন্য মেনু --}}
                <a href="/"
                    class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl {{ request()->is('/') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5' }} transition">
                    <span class="mr-4 opacity-80 text-lg">📺</span> Watch & Earn
                </a>

                @auth
                    <a href="/pakages"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl {{ request()->is('pakages') ? 'text-white bg-white/5' : 'text-gray-400 hover:bg-white/5' }} transition">
                        <span class="mr-4 opacity-80 text-lg"> 🏠</span> Dashboard
                    </a>
                    </a>

                    <a href="/withdraw"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl {{ request()->is('withdraw') ? 'text-white bg-white/5' : 'text-gray-400 hover:bg-white/5' }} transition">
                        <span class="mr-4 opacity-80 text-lg">💰</span> Withdraw
                    </a>
                    <!-- Shopping Store -->
                    <a href="/shop"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-emerald-500 border border-emerald-500/10 bg-emerald-500/5 hover:bg-emerald-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-emerald-500 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Shopping Store
                    </a>

                    <!-- Cart -->
                    <a href="/cart"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-orange-500 border border-orange-500/10 bg-orange-500/5 hover:bg-orange-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-orange-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        View My Cart
                    </a>

                    <!-- My Orders -->
                    <a href="/my-orders"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-purple-500 border border-purple-500/10 bg-purple-500/5 hover:bg-purple-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-purple-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        My Order History
                    </a>
                    {{-- অ্যাডমিন মেনু সেকশন --}}
                    @if (Auth::user()->role === 'admin')
                        <div class="pt-4 pb-2">
                            <p class="px-5 text-[10px] text-gray-500 uppercase font-black tracking-widest italic">Admin
                                Tools</p>
                        </div>

                        <a href="/admin/users"
                            class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-gray-400 hover:bg-indigo-600/20 hover:text-indigo-400 transition">
                            <span class="mr-4 opacity-80 text-lg">👥</span> User List
                        </a>

                        <a href="/admin/withdraw"
                            class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-gray-400 hover:bg-indigo-600/20 hover:text-indigo-400 transition">
                            <span class="mr-4 opacity-80 text-lg">⏳</span> Pending Withdraw
                        </a>



                        <a href="/admin/pending-users"
                            class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-gray-400 hover:bg-indigo-600/20 hover:text-indigo-400 transition">
                            <span class="mr-4 opacity-80 text-lg">⏳</span> Pending Users
                        </a>


                        <a href="/admin/settings"
                            class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-gray-400 hover:bg-indigo-600/20 hover:text-indigo-400 transition">
                            <span class="mr-4 opacity-80 text-lg">⚙️</span> Site Settings
                        </a>
                    @endif
                @endauth

                {{-- গেস্ট ইউজারদের জন্য লগইন বাটন (যদি প্রয়োজন মনে করেন) --}}
                @guest
                    <!-- Login Account -->
                    <a href="/login"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-blue-500 border border-blue-500/10 bg-blue-500/5 hover:bg-blue-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-blue-500 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Login to Account
                    </a>

                    <!-- Shopping Store -->
                    <a href="/shop"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-emerald-500 border border-emerald-500/10 bg-emerald-500/5 hover:bg-emerald-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-emerald-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Shopping Store
                    </a>

                    <!-- Cart -->
                    <a href="/cart"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-orange-500 border border-orange-500/10 bg-orange-500/5 hover:bg-orange-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-orange-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        View My Cart
                    </a>

                    <!-- My Orders -->
                    <a href="/my-orders"
                        class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl text-purple-500 border border-purple-500/10 bg-purple-500/5 hover:bg-purple-500/10 transition mt-4 group">
                        <svg class="w-6 h-6 mr-4 text-purple-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        My Order History
                    </a>
                @endguest
            </nav>
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-10 pb-28">
            {{-- @include('partials.hero') --}}

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-3">
                    @yield('content')
                </div>

                <div class="hidden lg:block space-y-6">
                    @php
                        $siteSetting = $setting ?? \App\Models\SiteSetting::first();

                        // ১. স্পনসর ইমেজ লিস্ট ডিকোড করা
                        $sponsors = [];
                        if ($siteSetting && $siteSetting->sponsor_banner) {
                            $decoded = json_decode($siteSetting->sponsor_banner, true);
                            $sponsors = is_array($decoded) ? $decoded : [];
                        }

                        // ২. ইউটিউব লিঙ্ক কনভার্ট লজিক
                        $youtubeUrl = $siteSetting->youtube_link ?? '';
                        $embedUrl = '';
                        if ($youtubeUrl) {
                            if (str_contains($youtubeUrl, 'watch?v=')) {
                                $videoId = explode('v=', $youtubeUrl)[1];
                                $videoId = explode('&', $videoId)[0];
                                $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                            } elseif (str_contains($youtubeUrl, 'youtu.be/')) {
                                $videoId = explode('youtu.be/', $youtubeUrl)[1];
                                $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                            } else {
                                // যদি সরাসরি এমবেড লিঙ্ক থাকে বা অন্য ফরম্যাট হয়
                                $embedUrl = $youtubeUrl;
                            }
                        }
                    @endphp

                    {{-- Alpine.js x-data initialization --}}
                    <div x-data="{ open: false, imgFull: '' }" class="w-full relative space-y-6">

                        {{-- ১. স্পনসর ব্যানার লুপ --}}
                        @if (count($sponsors) > 0)
                            @foreach ($sponsors as $banner)
                                @php
                                    // পাথে 'settings/' থাকলে সেটাকে ব্যবহার করবে, নাহলে যোগ করে নেবে
                                    $cleanPath = str_starts_with($banner, 'settings/')
                                        ? $banner
                                        : 'settings/' . $banner;
                                    $imagePath = asset('storage/' . $cleanPath);
                                @endphp

                                <div
                                    class="bg-[#161925] border border-white/5 rounded-[2rem] p-6 text-center shadow-2xl transition-all duration-500 hover:border-white/10">
                                    <div class="mb-4">
                                        <p
                                            class="text-[10px] text-gray-500 uppercase font-black tracking-widest italic">
                                            Sponsored Ad</p>
                                    </div>

                                    {{-- ইমেজে ক্লিক করলে পপআপ ওপেন হবে --}}
                                    <div @click="open = true; imgFull = '{{ $imagePath }}'"
                                        class="relative aspect-video bg-[#0a0c14] rounded-2xl overflow-hidden border border-white/10 group cursor-pointer shadow-inner">

                                        <img src="{{ $imagePath }}" alt="Sponsor Banner"
                                            class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100"
                                            onerror="this.src='https://via.placeholder.com/800x450?text=Image+Not+Found'">

                                        {{-- হোভার ইফেক্ট --}}
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                            <div
                                                class="bg-yellow-500 text-black px-4 py-2 rounded-full font-bold text-[10px] uppercase tracking-tighter shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                                View Full Image 🔍
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- ২. ইউটিউব ভিডিও স্লট (শুধুমাত্র লিঙ্ক থাকলে দেখাবে) --}}
                        @if ($embedUrl)
                            <div class="bg-[#161925] border border-white/5 rounded-[2rem] p-6 text-center shadow-2xl">
                                <div class="mb-4">
                                    <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest italic">
                                        Video Ad Slot</p>
                                </div>

                                <div
                                    class="relative aspect-video bg-black rounded-2xl overflow-hidden border border-white/10 shadow-inner">
                                    <iframe class="absolute top-0 left-0 w-full h-full"
                                        src="{{ $embedUrl }}?rel=0&modestbranding=1" title="YouTube video player"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                    </iframe>
                                </div>

                                <div class="mt-4">
                                    <p class="text-[11px] text-gray-400 leading-relaxed">
                                        আমাদের সাইটের সবকিছু এক ভিডিও তে দেখতে! <span
                                            class="text-yellow-500 font-bold">সাথে থাকুন</span>।
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- ৩. ইমেজ ফুল ভিউ মডাল (Popup) --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/95 backdrop-blur-sm"
                            @click="open = false" @keydown.escape.window="open = false" style="display: none;">

                            {{-- ক্লোজ বাটন --}}
                            <button
                                class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full transition-all hover:rotate-90">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            {{-- বড় ইমেজ কন্টেইনার --}}
                            <div class="max-w-5xl w-full" @click.stop>
                                <img :src="imgFull"
                                    class="w-full h-auto max-h-[85vh] rounded-xl shadow-2xl border border-white/10 object-contain mx-auto transition-all">
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <footer class="bg-[#0f111a] border-t border-white/5 pt-20 pb-10 pt-10">
                <div class="max-w-7xl mx-auto px-4 lg:px-10">
                    {{-- Main Footer Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">

                        {{-- Brand Section --}}
                        <div class="space-y-8">
                            <a href="/" class="inline-block">

                                <img src="{{ asset('storage/' . $settings->logo) }}"
                                    class="h-10 md:h-12 object-contain" alt="Logo">

                            </a>
                            <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                                {{ $settings->site_description ?? 'সহজ উপায়ে ভিডিও দেখে আয় করার নির্ভরযোগ্য প্ল্যাটফর্ম। আমাদের সাথে আপনার ফ্রিল্যান্সিং যাত্রা শুরু করুন আজই।' }}
                            </p>
                            {{-- Social Icons --}}
                            <div class="flex gap-3">
                                @if ($settings->fb_link)
                                    <a href="{{ $settings->fb_link }}" target="_blank"
                                        class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-500 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-lg shadow-blue-600/5">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if ($settings->whatsapp_link)
                                    <a href="{{ $settings->whatsapp_link }}" target="_blank"
                                        class="w-10 h-10 rounded-xl bg-emerald-600/10 flex items-center justify-center text-emerald-500 hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-lg shadow-emerald-600/5">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                @endif

                            </div>
                        </div>

                        {{-- Quick Links --}}
                        <div>
                            <h4 class="text-white font-black mb-8 text-xs uppercase tracking-[0.2em] italic">Quick
                                Navigation</h4>
                            <ul class="space-y-5">

                                <li><a href="{{ route('pakages') }}"
                                        class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                            class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                                        Premium Plans</a></li>
                                <li><a href="/about"
                                        class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                            class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                                        About Platform</a></li>
                            </ul>
                        </div>

                        {{-- Support & Legal --}}
                        <div>
                            <h4 class="text-white font-black mb-8 text-xs uppercase tracking-[0.2em] italic">Legal &
                                Support</h4>
                            <ul class="space-y-5">
                                <li><a href="/privacy-policy"
                                        class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                            class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                                        Privacy Policy</a></li>
                                <li><a href="/privacy-policy"
                                        class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                            class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                                        Terms of Service</a></li>

                            </ul>
                        </div>

                        {{-- Contact Info --}}
                        <div>
                            <h4 class="text-white font-black mb-8 text-xs uppercase tracking-[0.2em] italic">Get In
                                Touch</h4>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-indigo-400 border border-white/5">
                                        <i class="fas fa-envelope text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Email
                                            Us</p>
                                        <p class="text-gray-300 text-sm font-bold">
                                            {{ $settings->email ?? 'support@TaKa ID24.com' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-yellow-500 border border-white/5">
                                        <i class="fas fa-phone-alt text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Call
                                            Center</p>
                                        <p class="text-gray-300 text-sm font-bold">
                                            {{ $settings->phone_primary ?? '+880 1XXX XXXXXX' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Copyright & Developer Section --}}
                    <div
                        class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                        <p class="text-xs text-gray-500 font-medium">
                            &copy; {{ date('Y') }} <span
                                class="text-white font-black">{{ $settings->site_name ?? 'TaKa ID 24' }}</span>. Built
                            for earners.
                        </p>

                        {{-- Developed By Section --}}
                        <div class="flex items-center gap-3 bg-white/5 px-5 py-2.5 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Developed
                                By</span>
                            <a href="#"
                                class="text-xs font-black text-white hover:text-yellow-500 transition-all italic tracking-tighter">
                                Monoar <span class="text-indigo-400">IT</span> Solutions
                            </a>
                        </div>
                    </div>
                </div>
            </footer>

        </main>
    </div>

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M4 6h16M4 12h16M4 18h16">
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
    <script>
        let ytPlayers = {};
        let lastTime = {};
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        // --- FACEBOOK SDK INITIALIZATION ---
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v18.0'
            });
        };

        // --- FACEBOOK PLAY LOGIC ---
        // --- FACEBOOK PLAY LOGIC ---
        function playFBVideo(id) {


            const starter = document.getElementById('fb-starter-' + id);
            const timerStatus = document.getElementById('fb-timer-status-' + id);
            const overlay = document.getElementById('fb-overlay-' + id);

            // ২. UI পরিবর্তন
            starter.style.display = 'none';
            timerStatus.classList.remove('hidden');
            overlay.classList.remove('hidden');

            // টাইমার শুরুর আগেই টেক্সট সেট করুন
            let timeLeft = {{ $video->duration ?? 30 }}; // সেকেন্ডে (যেমন ৩০ সেকেন্ড)
            timerStatus.innerHTML = `⏳ <span id="timer-count-${id}">${timeLeft}</span>s remaining`;

            // ৩. Facebook Video Load and Play
            FB.XFBML.parse(document.getElementById('fb-container-' + id), function() {
                FB.Event.subscribe('xfbml.ready', function(msg) {
                    if (msg.type === 'video' && msg.id === 'fb-player-' + id) {
                        let myPlayer = msg.instance;
                        myPlayer.unmute();
                        myPlayer.play();
                        myPlayer.setVolume(1);
                    }
                });
            });

            // ৪. কাউন্টডাউন টাইমার লজিক
            let countdown = setInterval(() => {
                timeLeft--;
                let currentTimerSpan = document.getElementById('timer-count-' + id);

                if (currentTimerSpan) {
                    currentTimerSpan.innerText = timeLeft;
                }

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    overlay.classList.add('hidden'); // ভিডিওতে ক্লিক করার সুযোগ দিন

                    // সাকসেস স্টাইল
                    timerStatus.innerHTML = "✅ Task Finished!";
                    timerStatus.className =
                        "absolute top-4 left-4 z-40 bg-green-600 text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase";

                    // ৫. ডাটাবেসে টাকা অ্যাড করার ফাংশন কল করুন
                    finishTask(id);
                }
            }, 1000);
        }

        // --- YOUTUBE LOGIC ---
        function onYouTubeIframeAPIReady() {
            document.querySelectorAll('.yt-wrapper').forEach(w => {
                let tid = w.dataset.taskId;
                ytPlayers[tid] = new YT.Player('player-' + tid, {
                    videoId: w.dataset.videoId,
                    playerVars: {
                        'controls': 0,
                        'disablekb': 1,
                        'rel': 0,
                        'modestbranding': 1
                    },
                    events: {
                        'onStateChange': (e) => {
                            let ui = document.getElementById('yt-ui-' + tid);
                            if (e.data === 1) {
                                ui.style.opacity = '0';
                                startSecurityYT(tid);
                            } else {
                                ui.style.opacity = '1';
                            }
                            if (e.data === 0) finishTask(tid);
                        }
                    }
                });
            });
        }

        function toggleYT(id) {
            let state = ytPlayers[id].getPlayerState();
            state === 1 ? ytPlayers[id].pauseVideo() : ytPlayers[id].playVideo();
        }

        function startSecurityYT(id) {
            setInterval(() => {
                if (ytPlayers[id] && ytPlayers[id].getPlayerState() === 1) {
                    let curr = ytPlayers[id].getCurrentTime();
                    if (curr - (lastTime[id] || 0) > 2.0) ytPlayers[id].seekTo(lastTime[id] || 0);
                    else lastTime[id] = curr;
                }
            }, 500);
        }

        // --- LOCAL LOGIC ---
        function toggleLocal(id) {
            let v = document.getElementById('local-' + id);
            let ui = document.getElementById('local-ui-' + id);
            if (v.paused) {
                v.play();
                ui.style.opacity = '0';
                v.onended = () => finishTask(id);
            } else {
                v.pause();
                ui.style.opacity = '1';
            }
        }

        // --- API CALL ---
        // --- API CALL & LOGIN CHECK ---
        function finishTask(taskId) {
            // ১. ভিডিও শেষ হওয়ার পর চেক করা হচ্ছে ইউজার লগইন করা কি না
            if (!isLoggedIn) {
                // লগইন না থাকলে সরাসরি এরর মেসেজ দেখাবে
                showFinalMessage(taskId, false, "টাকা আয় করতে হলে আপনাকে আগে লগইন করতে হবে।");
                return;
            }

            // ২. ইউজার লগইন করা থাকলে সার্ভারে রিকোয়েস্ট পাঠানো হবে টাকা অ্যাড করার জন্য
            fetch("{{ route('complete.task') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        video_id: taskId
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showFinalMessage(taskId, true, data.message);
                    } else {
                        showFinalMessage(taskId, false, data.message);
                    }
                })
                .catch(() => showFinalMessage(taskId, false, "সার্ভার এরর! আবার চেষ্টা করুন।"));
        }

        // মেসেজ দেখানোর ফাংশন (ডিজাইন একটু আপডেট করা হয়েছে)
        function showFinalMessage(id, success, msg) {
            const overlay = document.getElementById('msg-overlay-' + id);
            const statusIcon = document.getElementById('status-icon-' + id);
            const statusTitle = document.getElementById('status-title-' + id);
            const statusText = document.getElementById('status-text-' + id);
            const actionBtn = overlay.querySelector('button');

            statusIcon.innerText = success ? "✅" : "❌";
            statusTitle.innerText = success ? "অভিনন্দন!" : "ব্যর্থ হয়েছে!";
            statusText.innerText = msg;

            // যদি লগইন না থাকার কারণে ব্যর্থ হয়, তবে বাটনের টেক্সট 'Login Now' করে দেওয়া যেতে পারে
            if (!isLoggedIn && !success) {
                actionBtn.innerText = "Login Now";
                actionBtn.onclick = () => window.location.href = "{{ route('login') }}";
            } else {
                actionBtn.innerText = "Collect & Next";
                actionBtn.onclick = () => location.reload();
            }

            overlay.classList.remove('hidden');
        }

        function showFinalMessage(id, success, msg) {
            const overlay = document.getElementById('msg-overlay-' + id);
            document.getElementById('status-icon-' + id).innerText = success ? "✅" : "❌";
            document.getElementById('status-title-' + id).innerText = success ? "Success!" : "Failed!";
            document.getElementById('status-text-' + id).innerText = msg;
            overlay.classList.remove('hidden');
        }
    </script>
    {{-- end of video watch section --}}



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Update User Function
        async function saveUserChanges(userId) {
            const role = document.getElementById(`role-${userId}`).value;
            const type = document.getElementById(`type-${userId}`).value;
            const amount = document.getElementById(`amount-${userId}`).value;
            const btn = document.getElementById(`btn-${userId}`);
            const loader = document.getElementById(`loader-${userId}`);

            btn.disabled = true;
            loader.classList.remove('hidden');

            try {
                const response = await fetch(`/admin/users/update-inline/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        role,
                        type,
                        paid_amount: amount
                    })
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Updated!',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#161925',
                        color: '#fff'
                    });
                    btn.classList.replace('bg-yellow-500', 'bg-emerald-500');
                    btn.querySelector('span').innerText = 'Done';
                    setTimeout(() => {
                        btn.classList.replace('bg-emerald-500', 'bg-yellow-500');
                        btn.querySelector('span').innerText = 'Save';
                        btn.disabled = false;
                    }, 2000);
                }
            } catch (error) {
                Swal.fire('Error', 'Update failed', 'error');
                btn.disabled = false;
            } finally {
                loader.classList.add('hidden');
            }
        }

        // Delete User Function
        async function deleteUser(userId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#eab308',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
                background: '#161925',
                color: '#fff'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/users/delete/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        const row = document.getElementById(`user-row-${userId}`);
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => row.remove(), 500);
                        Swal.fire('Deleted!', data.message, 'success');
                    } else {
                        Swal.fire('Error', data.message || 'Error occurred', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network or Server Error', 'error');
                }
            }
        }
    </script>

    {{-- end user list of admin --}}

</body>

</html>
