@extends('layouts.app')

@section('content')
    {{-- SweetAlert2 CDN --}}

    <div class="min-h-screen bg-[#0f111a] py-10 px-4 md:px-10">

        {{-- Top Navbar Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div>
                <h2 class="text-3xl font-black italic tracking-tighter uppercase text-white">Admin <span
                        class="text-yellow-500">Panel</span></h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
            </div>
            <a href="{{ route('users.export') }}"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all shadow-md">
                <i class="fa fa-file-excel-o"></i>
                Export User List (Excel)
            </a>

            <div class="flex items-center gap-6">
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
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-4 w-80 bg-[#161925] border border-white/10 rounded-[2rem] shadow-2xl z-50 overflow-hidden">
                        <div class="p-5 border-b border-white/5 bg-white/[0.02]">
                            <h4 class="font-black text-[10px] uppercase tracking-widest text-yellow-500">Alerts Dashboard
                            </h4>
                        </div>
                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                            {{-- Simple links in dropdown for brevity --}}
                            <a href="{{ url('/admin/withdraw') }}"
                                class="block p-4 border-b border-white/5 hover:bg-white/5 text-xs text-gray-300">Withdraw
                                Requests ({{ $pendingWithdrawCount }})</a>
                            <a href="{{ route('admin.users.pending') }}"
                                class="block p-4 border-b border-white/5 hover:bg-white/5 text-xs text-gray-300">User
                                Registrations ({{ $pendingRegCount }})</a>
                        </div>
                    </div>
                </div>

                {{-- Admin Identity --}}
                <div class="flex items-center gap-3 bg-[#161925] p-1.5 pr-5 rounded-2xl border border-white/5">
                    <div
                        class="w-10 h-10 bg-gradient-to-tr from-yellow-500 to-yellow-700 rounded-xl flex items-center justify-center font-black text-[#0f111a] text-xs">
                        {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <div class="hidden md:block">
                        <p class="text-[10px] font-black uppercase text-white leading-none">
                            {{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[9px] text-emerald-500 font-bold uppercase mt-1">Online</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-white italic uppercase tracking-widest">
                        Pending <span class="text-yellow-500">Applications</span>
                    </h2>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">নতুন রেজিস্ট্রেশন
                        রিকোয়েস্টগুলো চেক করুন</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-[10px] font-black text-gray-400 hover:text-white uppercase border border-white/10 px-4 py-2 rounded-xl transition">
                    Back to Dashboard
                </a>
            </div>

            <div class="bg-[#161925] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/5 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">
                            <tr>
                                <th class="p-6">User Details</th>
                                <th class="p-6">Payment Info</th>
                                <th class="p-6">Documents</th>
                                <th class="p-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white divide-y divide-white/5">
                            @forelse($users as $user)
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                class="w-14 h-14 rounded-2xl object-cover border border-white/10">
                                            <div>
                                                <p class="font-black text-sm">{{ $user->phone }}</p>
                                                <p class="text-[10px] text-gray-500 mt-1">{{ $user->email ?? 'No Email' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="space-y-1">
                                            <span
                                                class="px-2 py-0.5 bg-yellow-500/10 text-yellow-500 rounded text-[9px] font-black uppercase tracking-tighter">
                                                {{ $user->type }}
                                            </span>
                                            <p class="text-xs font-bold text-gray-300">
                                                {{ strtoupper($user->payment_type) }} • <span
                                                    class="text-emerald-500">৳{{ number_format($user->paid_amount) }}</span>
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-mono tracking-tight">
                                                {{ $user->transaction_id }}</p>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <a href="{{ asset('storage/' . $user->nid_image) }}" target="_blank"
                                            class="inline-flex items-center gap-2 text-[10px] font-bold text-indigo-400 hover:text-indigo-300 transition uppercase">
                                            <span>📂</span> View NID Card
                                        </a>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center justify-center gap-3">
                                            {{-- Approve Form --}}
                                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST"
                                                class="approve-form">
                                                @csrf
                                                <button type="button"
                                                    class="approve-btn bg-emerald-500 text-black px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all">
                                                    Approve
                                                </button>
                                            </form>

                                            {{-- Reject Form --}}
                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST"
                                                class="reject-form">
                                                @csrf
                                                <button type="button"
                                                    class="reject-btn bg-red-500/10 text-red-500 border border-red-500/20 px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-20 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-20">
                                            <span class="text-6xl mb-4">📥</span>
                                            <p class="text-sm font-black uppercase tracking-[0.3em]">কোনো পেন্ডিং আবেদন নেই
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- <div class="mt-6">
                {{ $users->links() }}
            </div> --}}
        </div>
    </div>

    <script>
        // SweetAlert Dark Theme Configuration
        const AdminAlert = Swal.mixin({
            background: '#161925',
            color: '#fff',
            confirmButtonColor: '#eab308', // yellow-500
            cancelButtonColor: '#ef4444', // red-500
            customClass: {
                popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                title: 'italic font-black text-yellow-500 uppercase tracking-tighter',
                htmlContainer: 'text-gray-400 text-sm font-bold',
                confirmButton: 'rounded-xl px-6 py-3 font-black uppercase text-xs tracking-widest',
                cancelButton: 'rounded-xl px-6 py-3 font-black uppercase text-xs tracking-widest'
            }
        });

        // Approve Confirmation
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.approve-form');
                AdminAlert.fire({
                    title: 'Approve User?',
                    text: "আপনি কি নিশ্চিত যে এই ইউজারকে অনুমোদন দিতে চান?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES, APPROVE',
                    cancelButtonText: 'CANCEL'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Reject Confirmation
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.reject-form');
                AdminAlert.fire({
                    title: 'Reject Application?',
                    text: "আপনি কি নিশ্চিত যে এই আবেদনটি বাতিল করতে চান?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YES, REJECT',
                    cancelButtonText: 'NO'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Toast Messages for Success/Error
        @if (session('success'))
            AdminAlert.fire({
                icon: 'success',
                title: 'SUCCESS',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            AdminAlert.fire({
                icon: 'error',
                title: 'ERROR',
                text: "{{ session('error') }}",
                timer: 4000
            });
        @endif
    </script>
@endsection
