@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-10 px-4">

        {{-- Main Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Left Column: Withdraw Form (4 Columns) --}}
            <div class="lg:col-span-4">
                <div class="bg-[#161925] border border-gray-800 rounded-[2.5rem] p-8 shadow-2xl sticky top-10">
                    <h2 class="text-2xl font-black text-white uppercase italic mb-6 text-center">
                        Withdraw <span class="text-yellow-500">Money</span>
                    </h2>

                    {{-- Balance Display Card --}}
                    <div class="bg-yellow-500/10 p-5 rounded-3xl border border-yellow-500/20 mb-8 text-center">
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">Available Balance</p>
                        <p class="text-white text-3xl font-black mt-1">৳{{ number_format($availableBalance, 2) }}</p>
                    </div>

                    {{-- Status Messages --}}
                    @if (session('success'))
                        <div
                            class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-black uppercase rounded-2xl text-center animate-pulse">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 text-[10px] font-black uppercase rounded-2xl text-center">
                            ❌ {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                            <ul class="list-disc list-inside text-red-400 text-[9px] font-bold uppercase tracking-tighter">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Starts --}}
                    <form action="{{ route('withdraw.store') }}" method="POST" class="space-y-6" id="withdrawForm">
                        @csrf

                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-2 mb-2 block">Select
                                Method</label>
                            <select name="payment_method"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white outline-none focus:border-yellow-500 transition-all appearance-none cursor-pointer">
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-2 mb-2 block">Account
                                Number</label>
                            <input type="text" name="account_number" placeholder="017XXXXXXXX" required
                                value="{{ old('account_number') }}"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white outline-none focus:border-yellow-500 transition-all font-mono">
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-2 mb-2 block">Amount</label>
                            <input type="number" name="amount" id="withdrawAmount" placeholder="Min: 100" required
                                value="{{ old('amount') }}"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white outline-none focus:border-yellow-500 transition-all">

                            {{-- Live JS Error Message --}}
                            <div id="jsErrorContainer"
                                class="hidden mt-3 flex items-center gap-2 text-red-500 animate-bounce">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span id="balanceError" class="text-[9px] font-black uppercase">Insufficient Balance!</span>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="group relative w-full bg-yellow-500 hover:bg-yellow-400 py-5 rounded-2xl text-black font-black uppercase tracking-widest shadow-2xl shadow-yellow-500/20 transition-all transform active:scale-95 overflow-hidden">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Submit Request
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: History Table (8 Columns) --}}
            <div class="lg:col-span-8">
                <div class="bg-[#161925] border border-gray-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <div class="p-8 border-b border-gray-800 flex justify-between items-center bg-white/[0.01]">
                        <h3 class="text-xl font-black text-white uppercase italic tracking-tighter">
                            Withdraw <span class="text-yellow-500">History</span>
                        </h3>
                        <div class="flex flex-col items-end">
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Transaction
                                Log</span>
                            <span class="text-yellow-500 font-mono text-xs">{{ $withdrawals->count() }} Records</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-black/40">
                                    <th class="p-6 text-[10px] font-black uppercase text-gray-500 tracking-widest">Request
                                        Details</th>
                                    <th class="p-6 text-[10px] font-black uppercase text-gray-500 tracking-widest">Amount
                                    </th>
                                    <th
                                        class="p-6 text-[10px] font-black uppercase text-gray-500 tracking-widest text-center">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/40">
                                @forelse($withdrawals as $withdraw)
                                    <tr class="hover:bg-white/[0.02] transition-all">
                                        <td class="p-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-gray-400">
                                                    @if ($withdraw->payment_method == 'bkash')
                                                        <span class="text-pink-500 font-bold">b</span>
                                                    @elseif($withdraw->payment_method == 'nagad')
                                                        <span class="text-orange-500 font-bold">n</span>
                                                    @else
                                                        <span class="text-blue-500 font-bold">r</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-white text-xs font-black uppercase">
                                                        {{ $withdraw->payment_method }} - <span
                                                            class="text-gray-500 font-mono">{{ $withdraw->account_number }}</span>
                                                    </p>
                                                    <p class="text-[9px] text-gray-600 font-bold mt-1 uppercase italic">
                                                        {{ $withdraw->created_at->format('d M, Y | h:i A') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-6">
                                            <p class="text-white font-black text-sm tracking-tighter">
                                                ৳{{ number_format($withdraw->amount, 2) }}</p>
                                        </td>
                                        <td class="p-6 text-center">
                                            @if ($withdraw->status == 'pending')
                                                <span
                                                    class="inline-flex items-center gap-1.5 bg-orange-500/10 text-orange-500 text-[9px] font-black px-4 py-2 rounded-full uppercase tracking-tighter border border-orange-500/20">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                    Pending
                                                </span>
                                            @elseif($withdraw->status == 'approved')
                                                <span
                                                    class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-500 text-[9px] font-black px-4 py-2 rounded-full uppercase tracking-tighter border border-emerald-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Success
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-500 text-[9px] font-black px-4 py-2 rounded-full uppercase tracking-tighter border border-red-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-20 text-center">
                                            <p
                                                class="text-gray-600 italic text-[10px] font-black uppercase tracking-[0.3em]">
                                                No Transactions Found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dynamic Validation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('withdrawAmount');
            const errorContainer = document.getElementById('jsErrorContainer');
            const submitBtn = document.getElementById('submitBtn');
            const availableBalance = parseFloat("{{ $availableBalance }}");

            amountInput.addEventListener('input', function() {
                const enteredAmount = parseFloat(this.value) || 0;

                if (enteredAmount > availableBalance) {
                    // Show Error
                    errorContainer.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-800');
                    submitBtn.classList.remove('bg-yellow-500');
                    this.classList.add('border-red-500', 'text-red-500');
                } else {
                    // Hide Error
                    errorContainer.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-800');
                    submitBtn.classList.add('bg-yellow-500');
                    this.classList.remove('border-red-500', 'text-red-500');
                }
            });

            // Prevention for minimal withdrawal (100)
            document.getElementById('withdrawForm').addEventListener('submit', function(e) {
                const val = parseFloat(amountInput.value);
                if (val < 5) {
                    alert('Minimum withdrawal amount is ৳100');
                    e.preventDefault();
                }
            });
        });
    </script>

    <style>
        /* Smooth transition for hover effects */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Chrome, Safari, Edge, Opera - Remove arrows from number input */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection
