@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen bg-[#0f111a] text-white font-sans">
        <main class="flex-1 p-6 md:p-10">

            {{-- Top Navbar Section --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase">Admin <span
                            class="text-yellow-500">Panel</span></h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
                </div>

                <div class="flex items-center gap-6">
                    {{-- Notification Bell Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        @php
                            // রেজিস্ট্রেশন এবং উইথড্র রিকোয়েস্টের সংখ্যা বের করা
                            $pendingRegCount = \App\Models\User::where('status', 'pending')->count();
                            $pendingWithdrawCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                            $totalNotif = $pendingRegCount + $pendingWithdrawCount;
                        @endphp

                        <button @click="open = !open"
                            class="relative p-3 bg-[#161925] rounded-2xl border border-white/5 hover:border-yellow-500/50 transition-all group focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-400 group-hover:text-yellow-500 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            @if ($totalNotif > 0)
                                <span class="absolute top-2 right-2 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-[#161925]"></span>
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="absolute right-0 mt-4 w-80 bg-[#161925] border border-white/10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-50 overflow-hidden"
                            x-cloak>

                            <div class="p-5 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                                <h4 class="font-black text-[10px] uppercase tracking-widest text-yellow-500">Alerts
                                    Dashboard</h4>
                                <span
                                    class="text-[9px] bg-yellow-500 text-black px-2 py-0.5 rounded-full font-black">{{ $totalNotif }}
                                    Total</span>
                            </div>

                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                {{-- উইথড্র রিকোয়েস্ট সেকশন --}}
                                @if ($pendingWithdrawCount > 0)
                                    <div
                                        class="px-5 py-2 bg-white/5 text-[9px] font-black uppercase tracking-tighter text-gray-400 flex justify-between">
                                        <span>Withdraw Requests</span>
                                        <span class="text-emerald-500">{{ $pendingWithdrawCount }}</span>
                                    </div>
                                    @foreach (\App\Models\Withdrawal::where('status', 'pending')->latest()->limit(3)->get() as $withdraw)
                                        <a href="{{ url('/admin/withdraw') }}"
                                            class="block p-4 border-b border-white/5 hover:bg-white/5 transition-all group">
                                            <div class="flex gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-black text-xs">
                                                    ৳</div>
                                                <div>
                                                    <p
                                                        class="text-xs font-bold text-gray-200 group-hover:text-yellow-500 transition-colors">
                                                        টাকা উইথড্র আবেদন: ৳{{ $withdraw->amount }}</p>
                                                    <p class="text-[9px] text-gray-500 mt-1 uppercase">
                                                        {{ $withdraw->payment_method }} • {{ $withdraw->account_number }}
                                                    </p>
                                                    <p class="text-[8px] text-gray-600 mt-1 italic">
                                                        {{ $withdraw->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                {{-- নতুন রেজিস্ট্রেশন সেকশন --}}
                                @if ($pendingRegCount > 0)
                                    <div
                                        class="px-5 py-2 bg-white/5 text-[9px] font-black uppercase tracking-tighter text-gray-400 flex justify-between">
                                        <span>Registration Requests</span>
                                        <span class="text-yellow-500">{{ $pendingRegCount }}</span>
                                    </div>
                                    @foreach (\App\Models\User::where('status', 'pending')->latest()->limit(3)->get() as $reg)
                                        <a href="{{ route('admin.users.pending') }}"
                                            class="block p-4 border-b border-white/5 hover:bg-white/5 transition-all group">
                                            <div class="flex gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-500 font-black text-[10px]">
                                                    USER</div>
                                                <div>
                                                    <p
                                                        class="text-xs font-bold text-gray-200 group-hover:text-yellow-500 transition-colors">
                                                        নতুন রেজিস্ট্রেশন আবেদন</p>
                                                    <p class="text-[9px] text-gray-500 mt-1">{{ $reg->phone }}</p>
                                                    <p class="text-[8px] text-gray-600 mt-1 italic">
                                                        {{ $reg->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                @if ($totalNotif == 0)
                                    <div
                                        class="p-8 text-center text-gray-600 uppercase text-[10px] font-black tracking-widest">
                                        ✨ No pending actions
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 border-t border-white/5">
                                <a href="{{ route('admin.users.pending') }}"
                                    class="py-4 text-center text-[8px] font-black uppercase border-r border-white/5 hover:bg-yellow-500 hover:text-black transition-all">Users
                                    List</a>
                                <a href="{{ url('/admin/withdraw') }}"
                                    class="py-4 text-center text-[8px] font-black uppercase hover:bg-emerald-500 hover:text-white transition-all">Withdraw
                                    List</a>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Identity --}}
                    <div class="flex items-center gap-3 bg-[#161925] p-1.5 pr-5 rounded-2xl border border-white/5">
                        <div
                            class="w-10 h-10 bg-gradient-to-tr from-yellow-500 to-yellow-700 rounded-xl border border-white/10 flex items-center justify-center font-black text-[#0f111a] text-xs">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="text-[10px] font-black uppercase tracking-tight leading-none text-white">
                                {{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[9px] text-emerald-500 font-bold uppercase leading-none mt-1">Online</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div
                    class="bg-[#161925] p-6 rounded-[2.5rem] border border-white/5 relative overflow-hidden group hover:border-white/30 transition-all shadow-xl">
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">Total Users</p>
                    <h3 class="text-3xl font-black mt-2 text-white">{{ \App\Models\User::count() }}</h3>
                    <div class="mt-4 w-12 h-1 bg-white rounded-full opacity-30"></div>
                </div>

                <div
                    class="bg-[#161925] p-6 rounded-[2.5rem] border border-white/5 relative overflow-hidden group hover:border-yellow-500/30 transition-all shadow-xl">
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">Pending Reg.</p>
                    <h3 class="text-3xl font-black mt-2 text-yellow-500">{{ $pendingRegCount }}</h3>
                    <div class="mt-4 w-12 h-1 bg-yellow-500 rounded-full opacity-30"></div>
                </div>

                <div
                    class="bg-[#161925] p-6 rounded-[2.5rem] border border-white/5 relative overflow-hidden group hover:border-emerald-500/30 transition-all shadow-xl">
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">Pending Withdraw</p>
                    <h3 class="text-3xl font-black mt-2 text-emerald-500">{{ $pendingWithdrawCount }}</h3>
                    <div class="mt-4 w-12 h-1 bg-emerald-500 rounded-full opacity-30"></div>
                </div>

                <div
                    class="bg-[#161925] p-6 rounded-[2.5rem] border border-white/5 relative overflow-hidden group hover:border-indigo-500/30 transition-all shadow-xl">
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">Total Revenue</p>
                    <h3 class="text-3xl font-black mt-2 text-indigo-500">
                        ৳{{ number_format(\App\Models\User::sum('paid_amount'), 0) }}</h3>
                    <div class="mt-4 w-12 h-1 bg-indigo-500 rounded-full opacity-30"></div>
                </div>
            </div>

            {{-- Bottom Section (Table & Sidebar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Recent Registration Table --}}
                <div class="lg:col-span-2 bg-[#161925] rounded-[2.5rem] p-8 border border-white/5 shadow-inner">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="font-black text-lg uppercase italic text-white tracking-tight">নতুন রেজিস্ট্রেশন
                                আবেদন</h4>
                            <div class="h-1 w-10 bg-yellow-500 mt-1 rounded-full"></div>
                        </div>
                        <a href="{{ route('admin.users.pending') }}"
                            class="px-4 py-2 bg-white/5 hover:bg-yellow-500 hover:text-black transition-all rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/5">View
                            All</a>
                    </div>

                    <div class="space-y-4">
                        @forelse(\App\Models\User::where('status', 'pending')->latest()->limit(5)->get() as $user)
                            <div
                                class="flex items-center justify-between p-5 bg-white/[0.02] rounded-[1.5rem] border border-white/5 hover:bg-white/5 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center font-black text-[#0f111a] shadow-lg shadow-yellow-500/20">
                                        {{ substr($user->phone, -2) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-bold text-white group-hover:text-yellow-500 transition-colors">
                                            {{ $user->phone }}</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5">
                                            <span
                                                class="text-gray-300 font-bold">{{ strtoupper($user->payment_type) }}</span>
                                            • <span>{{ $user->transaction_id }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right text-[9px] text-gray-600 font-bold">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-20 bg-white/[0.01] rounded-[2.5rem] border border-dashed border-white/5">
                                <p class="text-gray-500 text-sm">কোনো পেন্ডিং আবেদন নেই।</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Admin Quick Links --}}
                <div
                    class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 rounded-[2.5rem] p-8 text-white relative overflow-hidden group">
                    <h4 class="font-black text-xl mb-6 italic tracking-tight uppercase">Quick <span
                            class="text-indigo-300">Links</span></h4>
                    <div class="space-y-3 relative z-10">
                        @php
                            $links = [
                                ['label' => 'User Management', 'url' => '/admin/users', 'icon' => '👥'],
                                ['label' => 'Withdraw Requests', 'url' => '/admin/withdraw', 'icon' => '💰'],
                                ['label' => 'Pending Subscription Update', 'url' => '/admin/upgrades', 'icon' => '⏳'],
                                ['label' => 'Site Add', 'url' => '/admin/videos', 'icon' => 'videos'],
                            ];
                        @endphp
                        @foreach ($links as $link)
                            <a href="{{ $link['url'] }}"
                                class="flex justify-between items-center bg-white/10 p-3 rounded-xl hover:bg-white/20 transition-all">
                                <span class="text-xs">{{ $link['icon'] }} {{ $link['label'] }}</span>
                                <span class="text-[10px]">↗</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(234, 179, 8, 0.2);
            border-radius: 10px;
        }
    </style>
@endsection
