@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4">

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

            <div class="flex items-center gap-4">
                {{-- Excel Download Button --}}
                <a href="{{ route('withdraw.export', ['status' => $status]) }}"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-md hover:shadow-emerald-600/20 active:scale-95 text-xs uppercase">
                    <i class="fa fa-file-excel-o"></i>
                    <span>Export Excel</span>
                </a>

                {{-- Notification Bell --}}
                <div class="relative" x-data="{ open: false }">
                    @php
                        $pendingRegCount = $pendingRegCount ?? \App\Models\User::where('status', 'pending')->count();
                        $pendingWithdrawCount = $pendingWithdrawCount ?? \App\Models\Withdrawal::where('status', 'pending')->count();
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
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 items-center justify-center text-[8px] font-black text-white border-2 border-slate-50">{{ $totalNotif }}</span>
                            </span>
                        @endif
                    </button>

                    {{-- Notification Dropdown --}}
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute right-0 mt-4 w-80 bg-white border border-slate-200 rounded-3xl shadow-xl z-50 overflow-hidden"
                        x-cloak>
                        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h4 class="font-black text-[10px] uppercase tracking-widest text-slate-700">Alerts Dashboard</h4>
                            <span class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded-full font-black">{{ $totalNotif }}</span>
                        </div>
                        {{-- Dropdown Content --}}
                        <div class="max-h-80 overflow-y-auto custom-scrollbar p-2 space-y-1">
                            @if ($totalNotif == 0)
                                <div class="p-8 text-center text-slate-400 text-[10px] font-black uppercase">✨ No pending actions</div>
                            @else
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
                            @endif
                        </div>
                    </div>
                </div>

                {{-- User Profile --}}
                <div class="flex items-center gap-3 bg-slate-50 p-1.5 pr-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center font-black text-white text-xs shadow-sm">
                        {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <div class="hidden md:block uppercase">
                        <p class="text-[10px] font-black text-slate-800 tracking-tight leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[8px] text-emerald-600 font-bold uppercase mt-1 flex items-center gap-1">
                            <span class="h-1 w-1 bg-emerald-500 rounded-full"></span> Online
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
            <div>
                <h1 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter">Withdraw <span class="text-amber-500">Requests</span></h1>
                <p class="text-slate-400 text-xs font-medium mt-1">ইউজারদের টাকা উত্তোলনের আবেদনগুলো পর্যালোচনা করুন।</p>
            </div>
            <div class="flex gap-2 bg-slate-100 p-1 rounded-2xl border border-slate-200/60 shadow-inner">
                <a href="?status=pending"
                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ $status == 'pending' ? 'bg-white text-amber-600 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800' }}">Pending</a>
                <a href="?status=approved"
                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ $status == 'approved' ? 'bg-white text-emerald-600 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800' }}">Approved</a>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-6 py-5">User Details</th>
                            <th class="px-6 py-5">Method</th>
                            <th class="px-6 py-5">Amount</th>
                            <th class="px-6 py-5">Account No</th>
                            <th class="px-6 py-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($withdrawals as $req)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center text-slate-700 border border-slate-200/60 font-black text-sm shadow-sm group-hover:scale-105 transition-transform">
                                            {{ substr($req->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-slate-800 font-black text-sm tracking-tight">{{ $req->user->name ?? 'Unknown' }}</p>
                                            <p class="text-slate-400 text-[10px] font-medium mt-0.5">{{ $req->user->phone ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] font-black uppercase tracking-wide">
                                        {{ $req->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-amber-600 font-black text-sm">
                                    ৳{{ number_format($req->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-700 text-xs font-bold font-mono tracking-tight">{{ $req->account_number }}</p>
                                    <p class="text-slate-400 text-[9px] font-medium mt-1 italic">{{ $req->created_at->format('d M Y, h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($req->status == 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('admin.withdraw.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <button type="button"
                                                    class="approve-btn bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-emerald-500/20 transition-all hover:scale-105 active:scale-95">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.withdraw.reject', $req->id) }}" method="POST">
                                                @csrf
                                                <button type="button"
                                                    class="reject-btn bg-transparent hover:bg-red-50 text-red-500 border border-slate-200 hover:border-red-500 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider
                                                {{ $req->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                                                {{ $req->status }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                            <span class="text-3xl">📥</span>
                                        </div>
                                        <p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">No withdrawal requests found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($withdrawals, 'hasPages') && $withdrawals->hasPages())
            <div class="mt-6 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                background: '#ffffff',
                color: '#1e293b',
                confirmButtonColor: '#f59e0b'
            });
        @endif

        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Confirm Payment?',
                    text: "Have you sent the money to the user?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Yes, Paid!',
                    cancelButtonColor: '#64748b',
                    background: '#ffffff',
                    color: '#1e293b'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Reject Request?',
                    text: "User's balance will be refunded.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, Reject!',
                    cancelButtonColor: '#64748b',
                    background: '#ffffff',
                    color: '#1e293b'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>

    <style>
        .swal2-popup {
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-radius: 2rem !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
        }

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