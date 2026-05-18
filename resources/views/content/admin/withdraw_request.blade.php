@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4">

        {{-- Top Navbar Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div>
                <h2 class="text-3xl font-black italic tracking-tighter uppercase text-white">Admin <span
                        class="text-yellow-500">Panel</span></h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
            </div>

            <div class="flex items-center gap-4">
                {{-- Excel Download Button --}}
                <a href="{{ route('withdraw.export', ['status' => $status]) }}"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-lg active:scale-95 text-xs uppercase">
                    <i class="fa fa-file-excel-o"></i>
                    <span>Export Excel</span>
                </a>

                {{-- Notification Bell --}}
                <div class="relative" x-data="{ open: false }">
                    @php
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

                    {{-- Notification Dropdown --}}
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute right-0 mt-4 w-80 bg-[#161925] border border-white/10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-50 overflow-hidden"
                        x-cloak>
                        <div class="p-5 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                            <h4 class="font-black text-[10px] uppercase tracking-widest text-yellow-500">Alerts Dashboard
                            </h4>
                            <span
                                class="text-[9px] bg-yellow-500 text-black px-2 py-0.5 rounded-full font-black">{{ $totalNotif }}</span>
                        </div>
                        {{-- Notifications content as in original... --}}
                    </div>
                </div>

                {{-- User Profile --}}
                <div class="flex items-center gap-3 bg-[#161925] p-1.5 pr-5 rounded-2xl border border-white/5">
                    <div
                        class="w-10 h-10 bg-gradient-to-tr from-yellow-500 to-yellow-700 rounded-xl border border-white/10 flex items-center justify-center font-black text-[#0f111a] text-xs">
                        {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <div class="hidden md:block text-white uppercase">
                        <p class="text-[10px] font-black tracking-tight leading-none">{{ auth()->user()->name ?? 'Admin' }}
                        </p>
                        <p class="text-[9px] text-emerald-500 font-bold leading-none mt-1">Online</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Page Header --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-white uppercase italic">Withdraw Requests</h1>
                <p class="text-gray-500 text-sm">ইউজারদের টাকা উত্তোলনের আবেদনগুলো পর্যালোচনা করুন।</p>
            </div>
            <div class="flex gap-2">
                <a href="?status=pending"
                    class="px-4 py-2 rounded-xl text-sm font-bold {{ $status == 'pending' ? 'bg-yellow-500 text-black' : 'bg-white/5 text-gray-400' }}">Pending</a>
                <a href="?status=approved"
                    class="px-4 py-2 rounded-xl text-sm font-bold {{ $status == 'approved' ? 'bg-emerald-500 text-white' : 'bg-white/5 text-gray-400' }}">Approved</a>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="bg-[#161925] border border-gray-800 rounded-[2rem] overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="bg-gray-900/50 border-b border-gray-800 text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Account No</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($withdrawals as $req)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($req->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-sm">{{ $req->user->name ?? 'Unknown' }}</p>
                                        <p class="text-gray-500 text-[10px]">{{ $req->user->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded-full text-[10px] font-black uppercase">
                                    {{ $req->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-yellow-500 font-bold">
                                ৳{{ number_format($req->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white text-xs font-bold">{{ $req->account_number }}</p>
                                <p class="text-gray-600 text-[9px] mt-1">{{ $req->created_at->format('d M Y, h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($req->status == 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.withdraw.approve', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="button"
                                                class="approve-btn bg-emerald-500 hover:bg-emerald-400 text-black px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95">
                                                Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.withdraw.reject', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="button"
                                                class="reject-btn bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span
                                            class="text-[10px] font-black uppercase {{ $req->status == 'approved' ? 'text-emerald-500' : 'text-red-500' }}">
                                            {{ $req->status }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <p class="text-gray-600 font-bold uppercase tracking-widest text-xs">No withdrawal requests
                                    found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $withdrawals->links() }}
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                background: '#161925',
                color: '#fff',
                confirmButtonColor: '#eab308'
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
                    background: '#161925',
                    color: '#fff'
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
                    background: '#161925',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>

    <style>
        .swal2-popup {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 2rem !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #eab308;
            border-radius: 10px;
        }
    </style>
@endsection
