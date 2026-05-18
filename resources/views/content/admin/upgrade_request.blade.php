@extends('layouts.app') {{-- আপনার অ্যাডমিন লেআউট থাকলে সেটা দিন --}}

@section('content')

    <div class="max-w-7xl mx-auto py-10 px-4">

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
                                <div class="p-8 text-center text-gray-600 uppercase text-[10px] font-black tracking-widest">
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
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-white uppercase italic">Upgrade Requests</h1>
                <p class="text-gray-500 text-sm">ইউজারদের প্যাকেজ আপগ্রেড রিকোয়েস্টগুলো ভেরিফাই করুন।</p>
            </div>
            <div class="bg-yellow-500/10 border border-yellow-500/20 px-4 py-2 rounded-xl">
                <span class="text-yellow-500 font-bold text-sm">Pending: {{ $requests->count() }}</span>
            </div>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-2xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#161925] border border-gray-800 rounded-[2rem] overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="bg-gray-900/50 border-b border-gray-800 text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Target Package</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payment Info</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($requests as $req)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($req->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-sm">{{ $req->user->name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $req->user->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded-full text-[10px] font-black uppercase">
                                    {{ $req->target_package }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-yellow-500 font-bold">
                                ৳{{ number_format($req->amount_to_pay, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white text-xs font-bold uppercase">{{ $req->payment_type }}</p>
                                <p class="text-gray-500 text-[10px] font-mono mt-1">TRX: {{ $req->transaction_id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Approve Button --}}
                                    <form action="{{ route('admin.upgrades.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure to approve?')"
                                            class="bg-emerald-500 hover:bg-emerald-400 text-black px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all transform active:scale-95">
                                            Approve
                                        </button>
                                    </form>

                                    {{-- Reject Button --}}
                                    <form action="{{ route('admin.upgrades.reject', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Reject this request?')"
                                            class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <p class="text-gray-600 font-bold uppercase tracking-widest">No pending upgrade requests
                                    found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
