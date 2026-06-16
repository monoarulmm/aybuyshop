@extends('layouts.app')

@section('content')
    {{-- SweetAlert2 CDN --}}

    <div class="min-h-screen bg-slate-50/50 py-10 px-4 md:px-10">

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
            
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('users.export') }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-md hover:shadow-emerald-600/20 active:scale-95 text-xs uppercase">
                    <i class="fa fa-file-excel-o"></i>
                    <span>Export User List (Excel)</span>
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
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-4 w-80 bg-white border border-slate-200 rounded-3xl shadow-xl z-50 overflow-hidden">
                        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h4 class="font-black text-[10px] uppercase tracking-widest text-slate-700">Alerts Dashboard</h4>
                            <span class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded-full font-black">{{ $totalNotif }}</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto custom-scrollbar p-2 space-y-1">
                            <a href="{{ url('/admin/withdraw') }}"
                                class="block p-3 rounded-xl hover:bg-slate-50 text-xs text-slate-600 font-bold transition-colors">
                                💰 Withdraw Requests ({{ $pendingWithdrawCount }})
                            </a>
                            <a href="{{ route('admin.users.pending') }}"
                                class="block p-3 rounded-xl hover:bg-slate-50 text-xs text-slate-600 font-bold transition-colors">
                                📝 User Registrations ({{ $pendingRegCount }})
                            </a>
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
                            {{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[8px] text-emerald-600 font-bold uppercase mt-1 flex items-center gap-1">
                            <span class="h-1 w-1 bg-emerald-500 rounded-full"></span> Online
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4 px-2">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 italic uppercase tracking-widest">
                        Pending <span class="text-amber-500">Applications</span>
                    </h2>
                    <p class="text-slate-400 text-xs font-medium mt-1">নতুন রেজিস্ট্রেশন রিকোয়েস্টগুলো চেক করুন</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-[10px] font-black text-slate-500 hover:text-slate-800 uppercase border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 rounded-xl transition shadow-sm">
                    Back to Dashboard
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-200">
                            <tr>
                                <th class="p-6">User Details</th>
                                <th class="p-6">Payment Info</th>
                                <th class="p-6">Documents</th>
                                <th class="p-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 divide-y divide-slate-100">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 transition group">
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm group-hover:scale-105 transition-transform">
                                            <div>
                                                <p class="font-black text-sm text-slate-800 tracking-tight">{{ $user->phone }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $user->email ?? 'No Email' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="space-y-1">
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded text-[9px] font-black uppercase tracking-wide">
                                                {{ $user->type }}
                                            </span>
                                            <p class="text-xs font-bold text-slate-600">
                                                {{ strtoupper($user->payment_type) }} • <span
                                                    class="text-emerald-600 font-black">৳{{ number_format($user->paid_amount) }}</span>
                                            </p>
                                            <p class="text-[10px] text-slate-400 font-mono tracking-tight">
                                                {{ $user->transaction_id }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <a href="{{ asset('storage/' . $user->nid_image) }}" target="_blank"
                                            class="inline-flex items-center gap-2 text-[10px] font-black text-indigo-600 hover:text-indigo-700 border border-indigo-100 hover:border-indigo-200 bg-indigo-50/50 px-3 py-1.5 rounded-lg transition uppercase tracking-wider">
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
                                                    class="approve-btn bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all">
                                                    Approve
                                                </button>
                                            </form>

                                            {{-- Reject Form --}}
                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST"
                                                class="reject-form">
                                                @csrf
                                                <button type="button"
                                                    class="reject-btn bg-transparent hover:bg-red-50 text-red-500 border border-slate-200 hover:border-red-500 px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest active:scale-95 transition-all">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                                <span class="text-3xl">📥</span>
                                            </div>
                                            <p class="text-slate-400 font-black uppercase tracking-[0.2em] text-[10px]">কোনো পেন্ডিং আবেদন নেই</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="mt-6 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    {{ $users->links() }}
                </div>
            @endif --}}
        </div>
    </div>

    <script>
        // SweetAlert Light Theme Configuration
        const AdminAlert = Swal.mixin({
            background: '#ffffff',
            color: '#1e293b',
            confirmButtonColor: '#f59e0b', // amber-500
            cancelButtonColor: '#ef4444', // red-500
            customClass: {
                popup: 'rounded-[2rem] border border-slate-100 shadow-2xl',
                title: 'italic font-black text-amber-500 uppercase tracking-tighter',
                htmlContainer: 'text-slate-500 text-sm font-bold',
                confirmButton: 'rounded-xl px-6 py-3 font-black uppercase text-xs tracking-widest text-white',
                cancelButton: 'rounded-xl px-6 py-3 font-black uppercase text-xs tracking-widest text-white'
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
                    cancelButtonText: 'CANCEL',
                    cancelButtonColor: '#64748b'
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
                    cancelButtonText: 'NO',
                    cancelButtonColor: '#64748b'
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