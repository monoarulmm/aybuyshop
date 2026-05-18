@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#0b0e14] text-gray-200 font-sans selection:bg-yellow-500/30 selection:text-yellow-500">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            {{-- Top Header Section --}}
            <div
                class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6 bg-[#161925]/50 p-6 rounded-[2rem] border border-white/5 backdrop-blur-sm">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase">
                        Admin <span class="text-yellow-500">Panel</span>
                    </h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Notification Bell --}}
                    <div class="relative" x-data="{ open: false }">
                        @php
                            $pendingRegCount = \App\Models\User::where('status', 'pending')->count();
                            $pendingWithdrawCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                            $totalNotif = $pendingRegCount + $pendingWithdrawCount;
                        @endphp

                        <button @click="open = !open"
                            class="relative p-3 bg-[#1c2030] rounded-2xl border border-white/5 hover:border-yellow-500/50 transition-all group shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-400 group-hover:text-yellow-500 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($totalNotif > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-red-600 items-center justify-center text-[8px] font-black text-white border-2 border-[#1c2030]">{{ $totalNotif }}</span>
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-4 w-80 bg-[#161925] border border-white/10 rounded-3xl shadow-2xl z-50 overflow-hidden backdrop-blur-xl"
                            x-cloak>
                            <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                                <h4 class="font-black text-[10px] uppercase tracking-widest text-yellow-500">Alerts
                                    Dashboard</h4>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @if ($totalNotif == 0)
                                    <div class="p-8 text-center text-gray-600 text-[10px] font-black uppercase">✨ No pending
                                        actions</div>
                                @else
                                    {{-- Notifications loop content here (same logic as before) --}}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Admin Identity --}}
                    <div
                        class="flex items-center gap-3 bg-[#1c2030] p-1.5 pr-5 rounded-2xl border border-white/5 shadow-inner">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl flex items-center justify-center font-black text-[#0f111a] shadow-lg shadow-yellow-500/20">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-[10px] font-black uppercase text-white tracking-tight leading-none">
                                {{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[8px] text-emerald-500 font-bold uppercase mt-1 flex items-center gap-1">
                                <span class="h-1 w-1 bg-emerald-500 rounded-full"></span> Online
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Title --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 px-2 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-white italic uppercase tracking-tighter">
                        Pending <span class="text-yellow-500">Applications</span>
                    </h2>
                    <p class="text-gray-500 text-[11px] font-medium mt-1">Review and verify new user registration requests
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-yellow-500 uppercase transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div
                    class="bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-500 p-4 rounded-r-2xl mb-6 text-xs font-bold animate-fade-in-down">
                    <span class="mr-2">✅</span> {{ session('success') }}
                </div>
            @endif

            {{-- Table Container --}}
            <div class="bg-[#161925] rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl shadow-black/50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-white/[0.03] text-[10px] font-black text-gray-500 uppercase tracking-widest border-b border-white/5">
                                <th class="p-6">User Profile</th>
                                <th class="p-6">Payment Details</th>
                                <th class="p-6">Identity Docs</th>
                                <th class="p-6 text-center">Verification Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($users as $user)
                                <tr class="hover:bg-white/[0.02] transition-colors group">
                                    {{-- User Info --}}
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                    class="w-14 h-14 rounded-2xl object-cover border-2 border-white/10 group-hover:border-yellow-500/50 transition-all duration-500 shadow-lg">
                                                <div
                                                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-[#161925] rounded-full flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="font-black text-sm tracking-tight text-white">{{ $user->phone }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 font-medium">
                                                    {{ $user->email ?? 'no-email@system' }}</p>
                                                <div class="flex items-center gap-1 mt-1">
                                                    <span
                                                        class="text-[9px] text-gray-600 bg-white/5 px-2 py-0.5 rounded italic">{{ Str::limit($user->address, 25) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Payment Info --}}
                                    <td class="p-6">
                                        <div class="flex flex-col gap-1.5">
                                            <span
                                                class="w-fit px-2 py-0.5 bg-yellow-500/10 text-yellow-500 rounded text-[9px] font-black uppercase tracking-tighter">
                                                {{ $user->type }}
                                            </span>
                                            <p class="text-xs font-bold text-gray-300">
                                                {{ strtoupper($user->payment_type) }}
                                                <span class="text-gray-600 mx-1">|</span>
                                                <span
                                                    class="text-emerald-500">৳{{ number_format($user->paid_amount) }}</span>
                                            </p>
                                            <div
                                                class="flex items-center gap-1 text-[10px] text-gray-500 bg-black/20 w-fit px-2 py-0.5 rounded border border-white/5">
                                                <span class="text-[8px] opacity-50">TXID:</span>
                                                <span class="font-mono tracking-tighter">{{ $user->transaction_id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Documents --}}
                                    <td class="p-6">
                                        <div class="space-y-2">
                                            <p
                                                class="text-[9px] text-gray-500 uppercase font-black tracking-widest flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5l-2-2z" />
                                                </svg>
                                                ID: {{ $user->nid_number }}
                                            </p>
                                            <a href="{{ asset('storage/' . $user->nid_image) }}" target="_blank"
                                                class="flex items-center gap-2 w-fit bg-white/5 hover:bg-indigo-500/20 text-indigo-400 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all border border-white/5">
                                                <span>👁️</span> View NID Card
                                            </a>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="p-6">
                                        <div class="flex items-center justify-center gap-3">
                                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Approve this user?')">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-black px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all hover:scale-105 active:scale-95">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Reject this application?')">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-transparent hover:bg-red-500/10 text-red-500 border border-red-500/20 hover:border-red-500/50 px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
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
                                            <div
                                                class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                                <span class="text-4xl">📥</span>
                                            </div>
                                            <p class="text-xs font-black uppercase tracking-[0.4em] text-gray-600">No
                                                pending requests found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            {{-- <div class="mt-8 custom-pagination">
                {{ $users->links() }}
            </div> --}}
        </div>
    </div>

    <style>
        /* Custom Scrollbar for better UI */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
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
            animation: fade-in-down 0.5s ease-out;
        }
    </style>
@endsection
