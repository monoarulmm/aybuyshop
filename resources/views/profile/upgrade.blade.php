@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4 animate-fade-in">

        {{-- ১. বর্তমান প্যাকেজের বিস্তারিত --}}
        <div
            class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#1a1c2c] to-[#0f111a] border border-white/5 shadow-2xl mb-8">
            <div class="absolute top-0 right-0 p-6">
                <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-4 py-1.5 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span
                        class="text-emerald-500 text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->status }}</span>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <p class="text-yellow-500 text-xs font-black uppercase tracking-[0.2em] mb-2">Current Membership</p>
                <h2 class="text-4xl md:text-5xl font-black text-white italic uppercase tracking-tighter mb-6">
                    {{ Auth::user()->type }} <span class="text-gray-600 text-2xl not-italic font-light">Package</span>
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 border-t border-white/5 pt-8">
                    <div>
                        <p class="text-gray-500 text-[10px] font-black uppercase mb-1">Total Paid</p>
                        <p class="text-white font-bold text-lg">৳{{ number_format(Auth::user()->paid_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-[10px] font-black uppercase mb-1">Account ID</p>
                        <p class="text-white font-bold text-lg">#{{ Auth::user()->id + 1000 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- ২. সুবিধা সমূহ --}}
            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-white font-black uppercase italic tracking-widest text-sm mb-4">Upgrade Benefits</h3>
                <div class="bg-[#161925] p-5 rounded-2xl border border-gray-800 flex items-start gap-4">
                    <div class="bg-yellow-500/10 p-3 rounded-xl text-yellow-500 text-sm font-bold italic">PRO</div>
                    <div>
                        <h4 class="text-white font-bold text-sm uppercase">Extra Tasks</h4>
                        <p class="text-gray-500 text-xs mt-1">প্যাকেজ বাড়ালে প্রতিদিনের ইনকাম লিমিট বাড়বে।</p>
                    </div>
                </div>
            </div>

            {{-- ৩. আপগ্রেড ফর্ম --}}
            <div class="lg:col-span-3">
                <div class="bg-[#161925] border border-gray-800 rounded-[2.5rem] p-8 shadow-2xl relative">

                    {{-- ডাইনামিক পেমেন্ট নোটিশ বক্স --}}
                    <div id="payment_instruction_box"
                        class="mb-8 p-6 bg-blue-500/10 border border-blue-500/20 rounded-3xl text-center">
                        <p class="text-blue-400 text-[10px] font-black uppercase tracking-widest mb-1">Merchant Payment</p>
                        <h3 class="text-white text-lg font-bold">
                            বাকি <span id="payable_amount" class="text-yellow-500">০</span> টাকা
                            <span id="method_label" class="text-blue-400 italic">bKash</span> পেমেন্ট করুন
                        </h3>
                        <div
                            class="mt-3 inline-flex flex-col items-center bg-white/5 px-8 py-2 rounded-2xl border border-white/10">
                            <span class="text-[9px] text-gray-500 uppercase font-bold mb-1">Account Number</span>
                            <span id="merchant_number"
                                class="text-xl font-black text-white tracking-widest">Loading...</span>
                        </div>
                        <p id="method_hint" class="text-gray-500 text-[9px] mt-3 uppercase font-bold italic">অপেক্ষা করুন...
                        </p>
                    </div>

                    <form action="{{ route('profile.upgrade') }}" method="POST" class="space-y-6">
                        @csrf
                        @if (session('success'))
                            <div
                                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-2xl text-xs font-bold mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Target Package --}}
                        {{-- Target Package Select Box --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-1">
                                Select Upgrade Package
                            </label>
                            <select name="target_package" id="target_package" required
                                class="w-full bg-black/50 border border-gray-800 rounded-2xl px-5 py-4 text-white focus:border-yellow-500 outline-none appearance-none font-bold">

                                {{-- ১. Basic Package: শুধুমাত্র n_user অথবা যাদের পেইড অ্যামাউন্ট ১০০০ এর কম তারা দেখবে --}}
                                @if (Auth::user()->type == 'n_user' || Auth::user()->paid_amount < 1000)
                                    <option value="basic" data-price="1000">
                                        Basic Package (Total ৳1,000)
                                    </option>
                                @endif

                                {{-- ২. Premium Package: যাদের বর্তমান টাইপ প্রিমিয়াম বা প্রো নয় --}}
                                <option value="premium" data-price="2000"
                                    {{ Auth::user()->type == 'premium' || Auth::user()->type == 'premium_pro' ? 'disabled' : '' }}>
                                    Premium Package (Total ৳2,000)
                                </option>

                                {{-- ৩. Premium Pro Package: যাদের বর্তমান টাইপ প্রো নয় --}}
                                <option value="premium_pro" data-price="5000"
                                    {{ Auth::user()->type == 'premium_pro' ? 'disabled' : '' }}>
                                    Premium Pro Package (Total ৳5,000)
                                </option>
                            </select>
                        </div>

                        {{-- Payment Type --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-1">Select
                                Payment Method</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_type" value="bkash"
                                        class="hidden peer method-radio" checked>
                                    <div
                                        class="py-3 border border-gray-800 rounded-2xl bg-black/30 text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-500/5 transition-all">
                                        <span
                                            class="text-xs text-gray-400 peer-checked:text-yellow-500 font-black uppercase tracking-widest">bKash</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_type" value="nagad"
                                        class="hidden peer method-radio">
                                    <div
                                        class="py-3 border border-gray-800 rounded-2xl bg-black/30 text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-500/5 transition-all">
                                        <span
                                            class="text-xs text-gray-400 peer-checked:text-yellow-500 font-black uppercase tracking-widest">Nagad</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_type" value="rocket"
                                        class="hidden peer method-radio">
                                    <div
                                        class="py-3 border border-gray-800 rounded-2xl bg-black/30 text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-500/5 transition-all">
                                        <span
                                            class="text-xs text-gray-400 peer-checked:text-yellow-500 font-black uppercase tracking-widest">Rocket</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- TRX ID --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-1">Transaction
                                ID (TRX)</label>
                            <input type="text" name="transaction_id" placeholder="Enter TRX ID" required
                                class="w-full bg-black/50 border border-gray-800 rounded-2xl px-6 py-4 text-white font-mono text-lg focus:border-yellow-500 outline-none transition-all shadow-inner">
                        </div>

                        <button type="submit"
                            class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black uppercase py-5 rounded-2xl transition-all active:scale-[0.98]">
                            Submit Upgrade Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- জাভাস্ক্রিপ্ট কারেকশন --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const merchantNumbers = {
                bkash: {
                    num: "017XXXXXXXX",
                    hint: "বিকাশ অ্যাপ থেকে 'Payment' করুন"
                },
                nagad: {
                    num: "018XXXXXXXX",
                    hint: "নগদ অ্যাপ থেকে 'Merchant Pay' করুন"
                },
                rocket: {
                    num: "019XXXXXXXX",
                    hint: "রকেট 'Merchant Pay' ব্যবহার করুন"
                }
            };

            const currentPaidAmount = parseFloat("{{ Auth::user()->paid_amount }}") || 0;
            const packageSelect = document.getElementById('target_package');
            const amountDisplay = document.getElementById('payable_amount');
            const numberDisplay = document.getElementById('merchant_number');
            const methodLabel = document.getElementById('method_label');
            const methodHint = document.getElementById('method_hint');

            function updatePaymentInfo() {
                // ১. প্যাকেজ এর দাম এবং হিসাব
                const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                const targetPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const diff = targetPrice - currentPaidAmount;

                // টাকা প্রদর্শন (০ এর নিচে হলে ০ দেখাবে)
                amountDisplay.innerText = diff > 0 ? diff.toLocaleString() : "০";

                // ২. রেডিও বাটন চেক করে পেমেন্ট মেথড আপডেট
                const selectedMethodInput = document.querySelector('input[name="payment_type"]:checked');
                if (selectedMethodInput) {
                    const method = selectedMethodInput.value;
                    methodLabel.innerText = method.charAt(0).toUpperCase() + method.slice(1);
                    numberDisplay.innerText = merchantNumbers[method].num;
                    methodHint.innerText = merchantNumbers[method].hint;
                }
            }

            // ইভেন্ট লিসেনার সেট করা
            packageSelect.addEventListener('change', updatePaymentInfo);

            // রেডিও বাটনের জন্য ইভেন্ট ডেলিগেশন ব্যবহার করা যাতে মিস না হয়
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('method-radio')) {
                    updatePaymentInfo();
                }
            });

            // শুরুতে একবার রান করা
            updatePaymentInfo();
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.25rem center;
            background-size: 1.25rem;
        }
    </style>
@endsection
