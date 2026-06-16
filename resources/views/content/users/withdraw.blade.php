@extends('layouts.app')

@section('content')
    <div class="container mx-auto pt-6 pb-20 px-4 max-w-7xl w-full overflow-x-hidden">

        {{-- Main Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Left Column: Withdraw Form (4 Columns) --}}
            <div class="lg:col-span-4">
                <div class="bg-white border border-gray-100 rounded-[2.5rem] p-6 sm:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.02)] sticky top-10">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-6 text-center">
                        Withdraw <span class="text-indigo-600">Money</span>
                    </h2>

                    {{-- Balance Display Card (Light Accent) --}}
                    <div class="bg-indigo-50/50 p-5 rounded-3xl border border-indigo-100/80 mb-6 text-center">
                        <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Available Balance</p>
                        <p class="text-gray-900 text-3xl font-black mt-1">৳{{ number_format($availableBalance, 2) }}</p>
                    </div>

                    {{-- Status Messages --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase rounded-2xl text-center">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-[10px] font-black uppercase rounded-2xl text-center">
                            ❌ {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl">
                            <ul class="list-disc list-inside text-red-600 text-[9px] font-bold uppercase tracking-tighter space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Starts --}}
                    <form action="{{ route('withdraw.store') }}" method="POST" class="space-y-5" id="withdrawForm">
                        @csrf

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-2 mb-2 block">
                                Select Method
                            </label>
                            <div class="relative">
                                <select name="payment_method"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-gray-900 outline-none focus:border-indigo-600 focus:bg-white transition-all appearance-none cursor-pointer text-sm font-bold">
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-2 mb-2 block">
                                Account Number
                            </label>
                            <input type="text" name="account_number" placeholder="017XXXXXXXX" required
                                value="{{ old('account_number') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-gray-900 outline-none focus:border-indigo-600 focus:bg-white transition-all font-mono text-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-2 mb-2 block">
                                Amount
                            </label>
                            <input type="number" name="amount" id="withdrawAmount" placeholder="Min: 100" required
                                value="{{ old('amount') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-gray-900 outline-none focus:border-indigo-600 focus:bg-white transition-all text-sm font-bold">

                            {{-- Live JS Error Message --}}
                            <div id="jsErrorContainer" class="hidden mt-3 flex items-center gap-2 text-red-600 animate-pulse">
                                <svg class="w-3. h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="balanceError" class="text-[9px] font-black uppercase tracking-wider">Insufficient Balance!</span>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="group relative w-full bg-indigo-600 hover:bg-indigo-700 py-4 rounded-2xl text-white font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all transform active:scale-95 overflow-hidden text-xs">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Submit Request
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: History Table (8 Columns) --}}
            <div class="lg:col-span-8">
                <div class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                    <div class="p-6 sm:p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">
                            Withdraw <span class="text-indigo-600">History</span>
                        </h3>
                        <div class="flex flex-col items-end">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Transaction Log</span>
                            <span class="text-indigo-600 font-mono text-xs font-bold">{{ $withdrawals->count() }} Records</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-5 text-[10px] font-black uppercase text-gray-400 tracking-widest">Request Details</th>
                                    <th class="p-5 text-[10px] font-black uppercase text-gray-400 tracking-widest">Amount</th>
                                    <th class="p-5 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($withdrawals as $withdraw)
                                    <tr class="hover:bg-gray-50/50 transition-all">
                                        <td class="p-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center font-black uppercase text-sm shadow-2xs">
                                                    @if ($withdraw->payment_method == 'bkash')
                                                        <span class="text-pink-600">b</span>
                                                    @elseif($withdraw->payment_method == 'nagad')
                                                        <span class="text-orange-600">n</span>
                                                    @else
                                                        <span class="text-blue-600">r</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-gray-900 text-xs font-black uppercase">
                                                        {{ $withdraw->payment_method }} - <span class="text-gray-500 font-mono font-medium">{{ $withdraw->account_number }}</span>
                                                    </p>
                                                    <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">
                                                        {{ $withdraw->created_at->format('d M, Y | h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <p class="text-gray-900 font-black text-sm tracking-tight">
                                                ৳{{ number_format($withdraw->amount, 2) }}
                                            </p>
                                        </td>
                                        <td class="p-5 text-center">
                                            @if ($withdraw->status == 'pending')
                                                <span class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-700 text-[9px] font-black Scaled px-3 py-1.5 rounded-full uppercase border border-orange-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                    Pending
                                                </span>
                                            @elseif($withdraw->status == 'approved')
                                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-[9px] font-black px-3 py-1.5 rounded-full uppercase border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Success
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-[9px] font-black px-3 py-1.5 rounded-full uppercase border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-20 text-center">
                                            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em]">No Transactions Found</p>
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
                    errorContainer.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-200', 'text-gray-400');
                    submitBtn.classList.remove('bg-indigo-600', 'text-white');
                    this.classList.add('border-red-500', 'text-red-600', 'bg-red-50/30');
                } else {
                    errorContainer.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-200', 'text-gray-400');
                    submitBtn.classList.add('bg-indigo-600', 'text-white');
                    this.classList.remove('border-red-500', 'text-red-600', 'bg-red-50/30');
                }
            });

            // Prevention for minimal withdrawal (100)
            document.getElementById('withdrawForm').addEventListener('submit', function(e) {
                const val = parseFloat(amountInput.value);
                if (val < 100) {
                    alert('Minimum withdrawal amount is ৳100');
                    e.preventDefault();
                }
            });
        });
    </script>

    <style>
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection