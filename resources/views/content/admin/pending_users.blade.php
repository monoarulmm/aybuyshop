@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] text-slate-700 font-sans selection:bg-amber-200 selection:text-amber-900">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            {{-- Top Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6 bg-white p-6 rounded-[2rem] border border-slate-200/80 shadow-sm backdrop-blur-sm">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase text-slate-800">
                        Admin <span class="text-amber-500">Panel</span>
                    </h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Notification Bell --}}
                    <div class="relative" x-data="{ open: false }">
                        @php
                            // Note: Performance অপ্টিমাইজেশনের জন্য এই কুয়েরিগুলো Controller থেকে পাঠানোই সবচেয়ে উত্তম।
                            $pendingRegCount = \App\Models\User::where('status', 'pending')->count();
                            $pendingWithdrawCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                            $totalNotif = $pendingRegCount + $pendingWithdrawCount;
                        @endphp

                        <button @click="open = !open"
                            class="relative p-3 bg-slate-50 rounded-2xl border border-slate-200 hover:border-amber-500/50 hover:bg-white transition-all group shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-slate-500 group-hover:text-amber-500 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($totalNotif > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 items-center justify-center text-[8px] font-black text-white border-2 border-slate-50">{{ $totalNotif }}</span>
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-4 w-80 bg-white border border-slate-200 rounded-3xl shadow-xl z-50 overflow-hidden"
                            x-cloak>
                            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                <h4 class="font-black text-[10px] uppercase tracking-widest text-slate-700">Alerts Dashboard</h4>
                            </div>
                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                @if ($totalNotif == 0)
                                    <div class="p-8 text-center text-slate-400 text-[10px] font-black uppercase">✨ No pending actions</div>
                                @else
                                    <div class="p-2 space-y-1">
                                        @if($pendingRegCount > 0)
                                            <div class="p-3 bg-amber-50/50 hover:bg-amber-50 rounded-xl transition-colors">
                                                <p class="text-xs text-slate-700 font-bold">📝 User Registrations</p>
                                                <p class="text-[10px] text-amber-600 mt-1">{{ $pendingRegCount }} applications waiting review.</p>
                                            </div>
                                        @endif
                                        @if($pendingWithdrawCount > 0)
                                            <div class="p-3 bg-red-50/50 hover:bg-red-50 rounded-xl transition-colors">
                                                <p class="text-xs text-slate-700 font-bold">💰 Pending Withdrawals</p>
                                                <p class="text-[10px] text-red-600 mt-1">{{ $pendingWithdrawCount }} requests need approval.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Admin Identity --}}
                    <div class="flex items-center gap-3 bg-slate-50 p-1.5 pr-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center font-black text-white shadow-md shadow-amber-500/10">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-[10px] font-black uppercase text-slate-800 tracking-tight leading-none">
                                {{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[8px] text-emerald-600 font-bold uppercase mt-1 flex items-center gap-1">
                                <span class="h-1 w-1 bg-emerald-500 rounded-full"></span> Online
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Title --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 px-2 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 italic uppercase tracking-tighter">
                        Pending <span class="text-amber-500">Applications</span>
                    </h2>
                    <p class="text-slate-400 text-[11px] font-medium mt-1">Review and verify new user registration requests</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-amber-600 uppercase transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-2xl mb-6 text-xs font-bold animate-fade-in-down shadow-sm">
                    <span class="mr-2">✅</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-2xl mb-6 text-xs font-bold animate-fade-in-down shadow-sm">
                    <span class="mr-2">❌</span> {{ session('error') }}
                </div>
            @endif

            {{-- Table Container --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">
                                <th class="p-6">User Profile</th>
                                <th class="p-6">Payment Details</th>
                                <th class="p-6">Identity Docs</th>
                                <th class="p-6 text-center">Verification Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    {{-- User Info --}}
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}"
                                                    alt="Profile"
                                                    class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-200 group-hover:border-amber-500/50 transition-all duration-500 shadow-sm">
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                    <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="font-black text-sm tracking-tight text-slate-800">{{ $user->phone }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium">{{ $user->email ?? 'no-email@system' }}</p>
                                                @if($user->address)
                                                    <div class="flex items-center gap-1 mt-1">
                                                        <span class="text-[9px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded italic" title="{{ $user->address }}">
                                                            {{ Str::limit($user->address, 25) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Payment Info --}}
                                    <td class="p-6">
                                        <div class="flex flex-col gap-1.5">
                                            <span class="w-fit px-2 py-0.5 bg-amber-100 text-amber-800 rounded text-[9px] font-black uppercase tracking-tighter">
                                                {{ $user->type ?? 'General' }}
                                            </span>
                                            <p class="text-xs font-bold text-slate-700">
                                                {{ strtoupper($user->payment_type ?? 'N/A') }}
                                                <span class="text-slate-300 mx-1">|</span>
                                                <span class="text-emerald-600">৳{{ number_format($user->paid_amount ?? 0) }}</span>
                                            </p>
                                            <div class="flex items-center gap-1 text-[10px] text-slate-500 bg-slate-50 w-fit px-2 py-0.5 rounded border border-slate-200">
                                                <span class="text-[8px] opacity-60">TXID:</span>
                                                <span class="font-mono tracking-tighter font-semibold text-slate-700">{{ $user->transaction_id ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Documents --}}
                                    <td class="p-6">
                                        <div class="space-y-2">
                                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5l-2-2z" />
                                                </svg>
                                                NID: <span class="text-slate-600">{{ $user->nid_number ?? 'N/A' }}</span>
                                            </p>
                                            @if($user->nid_image)
                                                <a href="{{ asset('storage/' . $user->nid_image) }}" target="_blank"
                                                    class="flex items-center gap-2 w-fit bg-slate-50 hover:bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all border border-slate-200 shadow-sm">
                                                    <span>👁️</span> View NID Card
                                                </a>
                                            @else
                                                <span class="text-[10px] text-slate-400 italic">No Document</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="p-6">
                                        <div class="flex items-center justify-center gap-3">
                                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Approve this user?')">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md shadow-emerald-500/20 transition-all hover:scale-105 active:scale-95">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Reject this application?')">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-transparent hover:bg-red-50 text-red-500 border border-slate-200 hover:border-red-500 px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-24 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-200">
                                                <span class="text-4xl">📥</span>
                                            </div>
                                            <p class="text-xs font-black uppercase tracking-[0.4em] text-slate-400">No pending requests found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Section --}}
            @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="mt-8 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom Scrollbar for Light Mode */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(234, 179, 8, 0.5);
        }

        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out forwards;
        }
    </style>
@endsection