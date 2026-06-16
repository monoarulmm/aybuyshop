@extends('layouts.app') {{-- আপনার অ্যাডমিন লেআউট থাকলে সেটা দিন --}}

@section('content')

    <div class="min-h-screen bg-slate-50/50 py-10 px-4 md:px-10">
        <div class="max-w-7xl mx-auto">

            {{-- Top Navbar Section --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6 bg-white p-6 rounded-[2rem] border border-slate-200/80 shadow-sm backdrop-blur-sm">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase text-slate-800">Admin <span
                            class="text-amber-500">Panel</span></h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
                    </div>
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
                            class="relative p-3 bg-slate-50 rounded-2xl border border-slate-200 hover:border-amber-500/50 hover:bg-white transition-all group focus:outline-none shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-slate-500 group-hover:text-amber-500 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            @if ($totalNotif > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-red-500 items-center justify-center text-[8px] font-black text-white border-2 border-slate-50">{{ $totalNotif }}</span>
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="absolute right-0 mt-4 w-80 bg-white border border-slate-200 rounded-3xl shadow-xl z-50 overflow-hidden"
                            x-cloak>

                            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                <h4 class="font-black text-[10px] uppercase tracking-widest text-slate-700">Alerts Dashboard</h4>
                                <span
                                    class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded-full font-black">{{ $totalNotif }} Total</span>
                            </div>

                            <div class="max-h-80 overflow-y-auto custom-scrollbar p-2 space-y-1">
                                {{-- উইথড্র রিকোয়েস্ট সেকশন --}}
                                @if ($pendingWithdrawCount > 0)
                                    <div class="px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-slate-400 flex justify-between bg-slate-50/50 rounded-lg">
                                        <span>Withdraw Requests</span>
                                        <span class="text-emerald-600">{{ $pendingWithdrawCount }}</span>
                                    </div>
                                    @foreach (\App\Models\Withdrawal::where('status', 'pending')->latest()->limit(3)->get() as $withdraw)
                                        <a href="{{ url('/admin/withdraw') }}"
                                            class="block p-3 rounded-xl hover:bg-slate-50 transition-all group">
                                            <div class="flex gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-xs shadow-sm">
                                                    ৳</div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-700 group-hover:text-amber-600 transition-colors">
                                                        টাকা উইথড্র আবেদন: ৳{{ $withdraw->amount }}</p>
                                                    <p class="text-[9px] text-slate-400 mt-0.5 uppercase font-medium">
                                                        {{ $withdraw->payment_method }} • {{ $withdraw->account_number }}
                                                    </p>
                                                    <p class="text-[8px] text-slate-400 mt-0.5 italic">
                                                        {{ $withdraw->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                {{-- নতুন রেজিস্ট্রেশন সেকশন --}}
                                @if ($pendingRegCount > 0)
                                    <div class="px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-slate-400 flex justify-between bg-slate-50/50 rounded-lg mt-2">
                                        <span>Registration Requests</span>
                                        <span class="text-amber-600">{{ $pendingRegCount }}</span>
                                    </div>
                                    @foreach (\App\Models\User::where('status', 'pending')->latest()->limit(3)->get() as $reg)
                                        <a href="{{ route('admin.users.pending') }}"
                                            class="block p-3 rounded-xl hover:bg-slate-50 transition-all group">
                                            <div class="flex gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 font-black text-[9px] shadow-sm">
                                                    USER</div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-700 group-hover:text-amber-600 transition-colors">
                                                        নতুন রেজিস্ট্রেশন আবেদন</p>
                                                    <p class="text-[9px] text-slate-400 mt-0.5 font-medium">{{ $reg->phone }}</p>
                                                    <p class="text-[8px] text-slate-400 mt-0.5 italic">
                                                        {{ $reg->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                @if ($totalNotif == 0)
                                    <div class="p-8 text-center text-slate-400 uppercase text-[10px] font-black tracking-widest">
                                        ✨ No pending actions
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 border-t border-slate-100 bg-slate-50/50">
                                <a href="{{ route('admin.users.pending') }}"
                                    class="py-3.5 text-center text-[9px] font-black uppercase tracking-wider border-r border-slate-100 text-slate-500 hover:bg-amber-500 hover:text-white transition-all">Users List</a>
                                <a href="{{ url('/admin/withdraw') }}"
                                    class="py-3.5 text-center text-[9px] font-black uppercase tracking-wider text-slate-500 hover:bg-emerald-600 hover:text-white transition-all">Withdraw List</a>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Identity --}}
                    <div class="flex items-center gap-3 bg-slate-50 p-1.5 pr-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center font-black text-white text-xs shadow-sm">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div class="hidden md:block uppercase">
                            <p class="text-[10px] font-black text-slate-800 tracking-tight leading-none">
                                {{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[8px] text-emerald-600 font-bold uppercase mt-1 flex items-center gap-1 leading-none">
                                <span class="h-1 w-1 bg-emerald-500 rounded-full"></span> Online
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Title Header Section --}}
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase italic tracking-tight">Upgrade <span class="text-amber-500">Requests</span></h1>
                    <p class="text-slate-400 text-sm font-medium mt-1">ইউজারদের প্যাকেজ আপগ্রেড রিকোয়েস্টগুলো ভেরিফাই করুন।</p>
                </div>
                <div class="bg-amber-50 border border-amber-100 px-4 py-2 rounded-xl shadow-sm">
                    <span class="text-amber-600 font-black text-xs uppercase tracking-wider">Pending: {{ $requests->count() }}</span>
                </div>
            </div>

            {{-- Native Success Message Style --}}
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 p-4 rounded-2xl font-bold text-sm shadow-sm flex items-center gap-2">
                    <span>✅</span> <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Table Main Card --}}
            <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                                <th class="px-6 py-5">User Details</th>
                                <th class="px-6 py-5">Target Package</th>
                                <th class="px-6 py-5">Amount</th>
                                <th class="px-6 py-5">Payment Info</th>
                                <th class="px-6 py-5 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($requests as $req)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center text-slate-600 font-black text-sm shadow-sm group-hover:scale-105 transition-transform">
                                                {{ substr($req->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-slate-800 font-black text-sm tracking-tight">{{ $req->user->name }}</p>
                                                <p class="text-slate-400 text-xs font-medium mt-0.5">{{ $req->user->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[10px] font-black uppercase tracking-wide">
                                            {{ $req->target_package }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-amber-600 font-black text-base">
                                        ৳{{ number_format($req->amount_to_pay, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-slate-700 text-xs font-bold uppercase tracking-wide">{{ $req->payment_type }}</p>
                                        <p class="text-slate-400 text-[10px] font-mono mt-0.5">TRX: {{ $req->transaction_id }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-3">
                                            {{-- Approve Button --}}
                                            <form action="{{ route('admin.upgrades.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Are you sure to approve?')"
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all">
                                                    Approve
                                                </button>
                                            </form>

                                            {{-- Reject Button --}}
                                            <form action="{{ route('admin.upgrades.reject', $req->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Reject this request?')"
                                                    class="bg-transparent hover:bg-red-50 text-red-500 border border-slate-200 hover:border-red-500 px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest active:scale-95 transition-all">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                                <span class="text-3xl">📥</span>
                                            </div>
                                            <p class="text-slate-400 font-black uppercase tracking-[0.2em] text-[10px]">No pending upgrade requests found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Scrollbar CSS Style --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.08);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(245, 158, 11, 0.4);
        }
    </style>
@endsection