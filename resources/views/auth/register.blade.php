@extends('layouts.app')
@php
    $settings = $settings ?? \DB::table('site_settings')->first();
@endphp

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-10">
        <div class="bg-[#161925] border border-white/10 shadow-2xl rounded-[2.5rem] p-8 relative overflow-hidden">

            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-white italic uppercase">{{ $settings->site_name }}<span
                        class="text-yellow-500">24</span> REGISTER</h2>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">আপনার সঠিক তথ্য দিয়ে ফরমটি পূরণ
                    করুন</p>
            </div>

            {{-- পেমেন্ট ইনস্ট্রাকশন বক্স (ডাইনামিক) --}}
            {{-- <div id="payment_notice" class="mb-6 p-5 bg-yellow-500/10 border border-yellow-500/30 rounded-3xl text-center">
                <p class="text-yellow-500 text-xs font-black uppercase tracking-tighter mb-1">Payment Instructions</p>
                <h3 class="text-white text-lg font-bold">নিচের নম্বরে <span id="display_amount"
                        class="text-yellow-500">১০০০</span> টাকা <span id="display_method">bKash</span> করুন:</h3>
                <div class="mt-2 inline-block bg-white/5 px-6 py-2 rounded-full border border-white/10">
                    <span id="payment_number" class="text-xl font-black text-white tracking-widest">017XXXXXXXX</span>
                </div>
                <p class="text-gray-500 text-[10px] mt-2 uppercase">টাকা পাঠানোর পর Transaction ID টি নিচে দিন</p>
            </div> --}}

            {{-- পেমেন্ট ইনস্ট্রাকশন বক্স --}}
            <div id="payment_notice" class="mb-6 p-5 bg-blue-500/10 border border-blue-500/20 rounded-3xl text-center">
                <p class="text-blue-400 text-[10px] font-black uppercase tracking-widest mb-1">Merchant Payment Gateway</p>
                <h3 class="text-white text-lg font-bold">
                    নিচের নম্বরে <span id="display_amount" class="text-yellow-500">১০০০</span> টাকা
                    <span id="display_method" class="text-blue-400 font-black italic">bKash Payment</span> করুন
                </h3>

                <div
                    class="mt-3 inline-flex flex-col items-center bg-white/5 px-8 py-3 rounded-3xl border border-white/10 shadow-inner">
                    <span class="text-[10px] text-gray-500 uppercase font-bold mb-1">Merchant Number</span>
                    <span id="payment_number" class="text-2xl font-black text-white tracking-widest">017XXXXXXXX</span>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-2 text-[10px] text-gray-400 uppercase font-bold">
                    <p id="step_instruction">বিকাশ অ্যাপ থেকে 'Payment' অপশনটি সিলেক্ট করুন</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                    <ul class="list-disc list-inside text-red-500 text-xs font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Full
                        Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                        placeholder="Enter your full name">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Phone
                            Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                            placeholder="017XXXXXXXX">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Email
                            (Optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                            placeholder="example@mail.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 bg-white/5 p-5 rounded-3xl border border-white/5">
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Package</label>
                        <select name="type" id="package_select"
                            class="w-full bg-[#161925] border border-white/10 rounded-xl p-3 text-white text-sm focus:border-yellow-500 outline-none">
                            <option value="basic" data-price="1000">Basic (৳1000)</option>
                            <option value="premium" data-price="2000">Premium (৳2000)</option>
                            <option value="premium_pro" data-price="5000">Premium Pro (৳5000)</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Method</label>
                        <select name="payment_type" id="method_select"
                            class="w-full bg-[#161925] border border-white/10 rounded-xl p-3 text-white text-sm focus:border-yellow-500 outline-none">
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Trx
                            ID</label>
                        <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" required
                            class="w-full bg-[#161925] border border-white/10 rounded-xl p-3 text-white text-sm focus:border-yellow-500 outline-none"
                            placeholder="TRX8899XX">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">NID
                            Number</label>
                        <input type="number" name="nid_number" value="{{ old('nid_number') }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Present
                            Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="p-4 bg-white/5 border border-dashed border-white/20 rounded-2xl">
                        <label class="block text-[10px] font-black text-yellow-500 uppercase mb-2">NID Front Side (Max
                            1MB)</label>
                        <input type="file" name="nid_image" id="nid_input" accept="image/*" required
                            class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-yellow-500 file:text-black">
                        <img id="nid_preview"
                            class="mt-3 w-full h-32 object-cover rounded-xl hidden border border-white/10">
                    </div>

                    <div class="p-4 bg-white/5 border border-dashed border-white/20 rounded-2xl">
                        <label class="block text-[10px] font-black text-yellow-500 uppercase mb-2">Profile Photo (Max
                            1MB)</label>
                        <input type="file" name="profile_image" id="profile_input" accept="image/*" required
                            class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-yellow-500 file:text-black">
                        <img id="profile_preview"
                            class="mt-3 w-32 h-32 object-cover rounded-xl hidden border border-white/10 mx-auto">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-black font-black py-5 rounded-2xl hover:shadow-[0_0_20px_rgba(234,179,8,0.3)] transition-all uppercase tracking-widest text-sm">
                    Submit Registration
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 font-bold uppercase">ইতিমধ্যে অ্যাকাউন্ট আছে?
                    <a href="{{ route('login') }}" class="text-yellow-500 hover:underline ml-1">লগইন করুন</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // আপনার মার্চেন্ট নম্বরগুলো এখানে বসান
        const merchantData = {
            bkash: {
                number: "017XXXXXXXX", // বিকাশ মার্চেন্ট নম্বর
                instruction: "বিকাশ অ্যাপ থেকে 'Payment' অপশনে গিয়ে নম্বরটি টাইপ করুন"
            },
            nagad: {
                number: "018XXXXXXXX", // নগদ মার্চেন্ট নম্বর
                instruction: "নগদ অ্যাপ থেকে 'Merchant Pay' অপশনে গিয়ে নম্বরটি টাইপ করুন"
            },
            rocket: {
                number: "019XXXXXXXX", // রকেট মার্চেন্ট নম্বর
                instruction: "রকেট মেনু থেকে 'Merchant Pay' সিলেক্ট করে নম্বরটি দিন"
            }
        };

        const packageSelect = document.getElementById('package_select');
        const methodSelect = document.getElementById('method_select');
        const displayAmount = document.getElementById('display_amount');
        const displayMethod = document.getElementById('display_method');
        const paymentNumber = document.getElementById('payment_number');
        const stepInstruction = document.getElementById('step_instruction');

        function updatePaymentUI() {
            // ১. প্যাকেজ অনুযায়ী টাকা আপডেট
            const selectedPackage = packageSelect.options[packageSelect.selectedIndex];
            displayAmount.innerText = selectedPackage.getAttribute('data-price');

            // ২. মেথড অনুযায়ী মার্চেন্ট ডাটা আপডেট
            const method = methodSelect.value;
            const data = merchantData[method];

            displayMethod.innerText = method.charAt(0).toUpperCase() + method.slice(1) + " Payment";
            paymentNumber.innerText = data.number;
            stepInstruction.innerText = data.instruction;
        }

        // ইভেন্ট লিসেনার যোগ করা
        packageSelect.addEventListener('change', updatePaymentUI);
        methodSelect.addEventListener('change', updatePaymentUI);

        // পেজ লোড হওয়ার সময় ডিফল্ট ডাটা দেখানো
        updatePaymentUI();
    </script>
@endsection
