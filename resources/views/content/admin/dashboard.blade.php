@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen bg-[#f8fafc] text-gray-800 font-sans">
        <main class="flex-1 p-6 md:p-10">

            {{-- Top Navbar Section --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase text-gray-900">Admin <span
                            class="text-amber-500">Panel</span></h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
                </div>

                <div class="flex items-center gap-6">
                    {{-- Notification Bell Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        @php
                            $pendingRegCount = \App\Models\User::where('status', 'pending')->count();
                            $pendingWithdrawCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                            $totalNotif = $pendingRegCount + $pendingWithdrawCount;
                        @endphp

                        <button @click="open = !open"
                            class="relative p-3 bg-white rounded-2xl border border-gray-200/80 hover:border-amber-500/50 hover:shadow-sm transition-all group focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-400 group-hover:text-amber-500 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            @if ($totalNotif > 0)
                                <span class="absolute top-2 right-2 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-white"></span>
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="absolute right-0 mt-4 w-80 bg-white border border-gray-200 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.08)] z-50 overflow-hidden"
                            x-cloak>

                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h4 class="font-black text-[10px] uppercase tracking-widest text-amber-600">Alerts
                                    Dashboard</h4>
                                <span
                                    class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded-full font-black">{{ $totalNotif }}
                                    Total</span>
                            </div>

                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                {{-- উইথড্র রিকোয়েস্ট সেকশন --}}
                                @if ($pendingWithdrawCount > 0)
                                    <div
                                        class="px-5 py-2 bg-gray-50 text-[9px] font-black uppercase tracking-tighter text-gray-400 flex justify-between border-b border-gray-100">
                                        <span>Withdraw Requests</span>
                                        <span class="text-emerald-600">{{ $pendingWithdrawCount }}</span>
                                    </div>
                                    @foreach (\App\Models\Withdrawal::where('status', 'pending')->latest()->limit(3)->get() as $withdraw)
                                        <a href="{{ url('/admin/withdraw') }}"
                                            class="block p-4 border-b border-gray-100 hover:bg-gray-50/80 transition-all group">
                                            <div class="flex gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-black text-xs border border-emerald-100/50">
                                                    ৳</div>
                                                <div>
                                                    <p
                                                        class="text-xs font-bold text-gray-700 group-hover:text-amber-600 transition-colors">
                                                        টাকা উইথড্র আবেদন: ৳{{ $withdraw->amount }}</p>
                                                    <p class="text-[9px] text-gray-400 mt-1 uppercase font-medium">
                                                        {{ $withdraw->payment_method }} • {{ $withdraw->account_number }}
                                                    </p>
                                                    <p class="text-[8px] text-gray-400 mt-1 italic">
                                                        {{ $withdraw->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                {{-- নতুন রেজিস্ট্রেশন সেকশন --}}
                                @if ($pendingRegCount > 0)
                                    <div
                                        class="px-5 py-2 bg-gray-50 text-[9px] font-black uppercase tracking-tighter text-gray-400 flex justify-between border-b border-gray-100">
                                        <span>Registration Requests</span>
                                        <span class="text-amber-600">{{ $pendingRegCount }}</span>
                                    </div>
                                    @foreach (\App\Models\User::where('status', 'pending')->latest()->limit(3)->get() as $reg)
                                        <a href="{{ route('admin.users.pending') }}"
                                            class="block p-4 border-b border-gray-100 hover:bg-gray-50/80 transition-all group">
                                            <div class="flex gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 font-black text-[9px] border border-amber-100/50">
                                                    USER</div>
                                                <div>
                                                    <p
                                                        class="text-xs font-bold text-gray-700 group-hover:text-amber-600 transition-colors">
                                                        নতুন রেজিস্ট্রেশন আবেদন</p>
                                                    <p class="text-[9px] text-gray-400 mt-1 font-medium">{{ $reg->phone }}</p>
                                                    <p class="text-[8px] text-gray-400 mt-1 italic">
                                                        {{ $reg->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                @if ($totalNotif == 0)
                                    <div
                                        class="p-8 text-center text-gray-400 uppercase text-[10px] font-black tracking-widest">
                                        ✨ No pending actions
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 border-t border-gray-100 bg-gray-50/30">
                                <a href="{{ route('admin.users.pending') }}"
                                    class="py-4 text-center text-[9px] font-bold text-gray-600 uppercase border-r border-gray-100 hover:bg-amber-500 hover:text-white transition-all">Users
                                    List</a>
                                <a href="{{ url('/admin/withdraw') }}"
                                    class="py-4 text-center text-[9px] font-bold text-gray-600 uppercase hover:bg-emerald-600 hover:text-white transition-all">Withdraw
                                    List</a>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Identity --}}
                    <div class="flex items-center gap-3 bg-white p-1.5 pr-5 rounded-2xl border border-gray-200/80 shadow-sm">
                        <div
                            class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-amber-600 rounded-xl flex items-center justify-center font-black text-white text-xs shadow-sm">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="text-[10px] font-black uppercase tracking-tight leading-none text-gray-800">
                                {{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[9px] text-emerald-600 font-bold uppercase leading-none mt-1">Online</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div
                    class="bg-white p-6 rounded-[2.5rem] border border-gray-200/80 relative overflow-hidden group hover:border-gray-400 transition-all shadow-sm">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Users</p>
                    <h3 class="text-3xl font-black mt-2 text-gray-900">{{ \App\Models\User::count() }}</h3>
                    <div class="mt-4 w-12 h-1 bg-gray-200 rounded-full"></div>
                </div>

                <div
                    class="bg-white p-6 rounded-[2.5rem] border border-gray-200/80 relative overflow-hidden group hover:border-amber-500/30 transition-all shadow-sm">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Pending Reg.</p>
                    <h3 class="text-3xl font-black mt-2 text-amber-500">{{ $pendingRegCount }}</h3>
                    <div class="mt-4 w-12 h-1 bg-amber-500/40 rounded-full"></div>
                </div>

                <div
                    class="bg-white p-6 rounded-[2.5rem] border border-gray-200/80 relative overflow-hidden group hover:border-emerald-500/30 transition-all shadow-sm">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Pending Withdraw</p>
                    <h3 class="text-3xl font-black mt-2 text-emerald-600">{{ $pendingWithdrawCount }}</h3>
                    <div class="mt-4 w-12 h-1 bg-emerald-500/40 rounded-full"></div>
                </div>

                <div
                    class="bg-white p-6 rounded-[2.5rem] border border-gray-200/80 relative overflow-hidden group hover:border-blue-500/30 transition-all shadow-sm">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Revenue</p>
                    <h3 class="text-3xl font-black mt-2 text-blue-600">
                        ৳{{ number_format(\App\Models\User::sum('paid_amount'), 0) }}</h3>
                    <div class="mt-4 w-12 h-1 bg-blue-500/40 rounded-full"></div>
                </div>
            </div>

            {{-- Bottom Section (Table & Sidebar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Recent Registration Table --}}
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-gray-200/80 shadow-sm">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="font-black text-lg uppercase italic text-gray-900 tracking-tight">নতুন রেজিস্ট্রেশন
                                আবেদন</h4>
                            <div class="h-1 w-10 bg-amber-500 mt-1 rounded-full"></div>
                        </div>
                        <a href="{{ route('admin.users.pending') }}"
                            class="px-4 py-2 bg-gray-50 hover:bg-amber-500 hover:text-white transition-all rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-200/50 text-gray-600">View
                            All</a>
                    </div>

                    <div class="space-y-4">
                        @forelse(\App\Models\User::where('status', 'pending')->latest()->limit(5)->get() as $user)
                            <div
                                class="flex items-center justify-between p-5 bg-gray-50/40 rounded-[1.5rem] border border-gray-100 hover:bg-gray-50 hover:border-gray-200 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl flex items-center justify-center font-black text-white shadow-sm shadow-amber-500/10">
                                        {{ substr($user->phone, -2) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-bold text-gray-800 group-hover:text-amber-600 transition-colors">
                                            {{ $user->phone }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5 font-medium">
                                            <span
                                                class="text-gray-600 font-bold">{{ strtoupper($user->payment_type) }}</span>
                                            • <span>{{ $user->transaction_id }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right text-[9px] text-gray-400 font-bold">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-20 bg-gray-50/30 rounded-[2.5rem] border border-dashed border-gray-200">
                                <p class="text-gray-400 text-sm font-medium">কোনো পেন্ডিং আবেদন নেই।</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Admin Quick Links --}}
                <div
                    class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-md">
                    <h4 class="font-black text-xl mb-6 italic tracking-tight uppercase">Quick <span
                            class="text-blue-200">Links</span></h4>
                    <div class="space-y-3 relative z-10">
                        @php
                            $links = [
                                ['label' => 'User Management', 'url' => '/admin/users', 'icon' => '👥'],
                                ['label' => 'Withdraw Requests', 'url' => '/admin/withdraw', 'icon' => '💰'],
                                ['label' => 'Pending Subscription Update', 'url' => '/admin/upgrades', 'icon' => '⏳'],
                                ['label' => 'Site Add', 'url' => '/admin/videos', 'icon' => '🎬'],
                            ];
                        @endphp
                        @foreach ($links as $link)
                            <a href="{{ $link['url'] }}"
                                class="flex justify-between items-center bg-white/10 p-3.5 rounded-xl hover:bg-white/20 transition-all border border-white/5">
                                <span class="text-xs font-semibold">{{ $link['icon'] }} &nbsp;{{ $link['label'] }}</span>
                                <span class="text-[10px] opacity-80">↗</span>
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
            background: rgba(245, 158, 11, 0.2);
            border-radius: 10px;
        }
    </style>
@endsection