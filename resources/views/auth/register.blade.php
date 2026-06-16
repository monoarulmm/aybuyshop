@extends('layouts.app')
@php
    $settings = $settings ?? \DB::table('site_settings')->first();
@endphp

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#f4f6fa]">
        <div class="max-w-2xl w-full">
            <div class="bg-white border border-gray-200/80 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-6 md:p-10 relative overflow-hidden">

                <div class="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="text-center mb-8 relative">
                    <h2 class="text-3xl font-black text-black italic uppercase tracking-tighter">
                        {{ $settings->site_name ?? 'AyBuyShop' }}<span class="text-yellow-600">24</span> REGISTER
                    </h2>
                    <p class="text-gray-500 text-[11px] font-black uppercase tracking-widest mt-2">
                        আপনার সঠিক তথ্য দিয়ে ফরমটি পূরণ করুন
                    </p>
                </div>

                {{-- পেমেন্ট ইনস্ট্রাকশন বক্স --}}
                <div id="payment_notice" class="mb-6 p-5 bg-blue-50 border border-blue-100 rounded-3xl text-center shadow-sm">
                    <p class="text-blue-600 text-[10px] font-black uppercase tracking-widest mb-1">Merchant Payment Gateway</p>
                    <h3 class="text-black text-base md:text-lg font-bold">
                        নিচের নম্বরে <span id="display_amount" class="text-yellow-600 font-black">১০০০</span> টাকা
                        <span id="display_method" class="text-blue-600 font-black italic">bKash Payment</span> করুন
                    </h3>

                    <div class="mt-3 inline-flex flex-col items-center bg-white px-8 py-3 rounded-2xl border border-gray-200 shadow-sm">
                        <span class="text-[10px] text-gray-400 uppercase font-bold mb-1">Merchant Number</span>
                        <span id="payment_number" class="text-xl md:text-2xl font-black text-black tracking-widest">017XXXXXXXX</span>
                    </div>

                    <div class="mt-3 text-[10px] text-gray-500 uppercase font-bold">
                        <p id="step_instruction">বিকাশ অ্যাপ থেকে 'Payment' অপশনটি সিলেক্ট করুন</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                        <ul class="list-disc list-inside text-red-600 text-xs font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-5 relative">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📝</span>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                placeholder="Enter your full name">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📞</span>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                    placeholder="017XXXXXXXX">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Email (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">✉️</span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all placeholder:text-gray-400 font-medium"
                                    placeholder="example@mail.com">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50 p-5 rounded-3xl border border-gray-200/60">
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Package</label>
                            <select name="type" id="package_select"
                                class="w-full bg-white border border-gray-200 rounded-xl p-3 text-black text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 outline-none transition-all font-medium">
                                <option value="basic" data-price="1000">Basic (৳1000)</option>
                                <option value="premium" data-price="2000">Premium (৳2000)</option>
                                <option value="premium_pro" data-price="5000">Premium Pro (৳5000)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Method</label>
                            <select name="payment_type" id="method_select"
                                class="w-full bg-white border border-gray-200 rounded-xl p-3 text-black text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 outline-none transition-all font-medium">
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Trx ID</label>
                            <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" required
                                class="w-full bg-white border border-gray-200 rounded-xl p-3 text-black text-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 outline-none transition-all placeholder:text-gray-400 font-bold uppercase"
                                placeholder="TRX8899XX">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">NID Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🪪</span>
                                <input type="number" name="nid_number" value="{{ old('nid_number') }}" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all font-medium">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-black uppercase tracking-widest mb-2 ml-1">Present Address</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📍</span>
                                <input type="text" name="address" value="{{ old('address') }}" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 pl-12 text-black focus:bg-white focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 outline-none transition-all font-medium">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-2xl transition-all hover:border-gray-400">
                            <label class="block text-[10px] font-black text-yellow-600 uppercase mb-2">NID Front Side (Max 1MB)</label>
                            <input type="file" name="nid_image" id="nid_input" accept="image/*" required
                                class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-black file:text-white file:cursor-pointer hover:file:bg-gray-900 transition-all">
                            <img id="nid_preview" class="mt-3 w-full h-32 object-cover rounded-xl hidden border border-gray-200 shadow-sm">
                        </div>

                        <div class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-2xl transition-all hover:border-gray-400">
                            <label class="block text-[10px] font-black text-yellow-600 uppercase mb-2">Profile Photo (Max 1MB)</label>
                            <input type="file" name="profile_image" id="profile_input" accept="image/*" required
                                class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-black file:text-white file:cursor-pointer hover:file:bg-gray-900 transition-all">
                            <img id="profile_preview" class="mt-3 w-32 h-32 object-cover rounded-xl hidden border border-gray-200 shadow-sm mx-auto">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-black text-white font-black py-5 rounded-2xl hover:bg-gray-900 hover:shadow-[0_10px_20px_rgba(0,0,0,0.15)] transition-all uppercase tracking-widest text-xs active:scale-95 mt-2">
                        Submit Registration
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                        ইতিমধ্যে অ্যাকাউন্ট আছে?
                        <a href="{{ route('login') }}" class="text-yellow-600 hover:text-yellow-700 hover:underline ml-1 transition">
                            লগইন করুন
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- জাভাস্ক্রিপ্ট লজিক --}}
    <script>
        const merchantData = {
            bkash: {
                number: "017XXXXXXXX", 
                instruction: "বিকাশ অ্যাপ থেকে 'Payment' অপশনে গিয়ে নম্বরটি টাইপ করুন"
            },
            nagad: {
                number: "018XXXXXXXX", 
                instruction: "নগদ অ্যাপ থেকে 'Merchant Pay' অপশনে গিয়ে নম্বরটি টাইপ করুন"
            },
            rocket: {
                number: "019XXXXXXXX", 
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
            const selectedPackage = packageSelect.options[packageSelect.selectedIndex];
            displayAmount.innerText = selectedPackage.getAttribute('data-price');

            const method = methodSelect.value;
            const data = merchantData[method];

            displayMethod.innerText = method.charAt(0).toUpperCase() + method.slice(1) + " Payment";
            paymentNumber.innerText = data.number;
            stepInstruction.innerText = data.instruction;
        }

        packageSelect.addEventListener('change', updatePaymentUI);
        methodSelect.addEventListener('change', updatePaymentUI);

        // ফাইল আপলোড প্রিভিউ হ্যান্ডলার
        function setupImagePreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const preview = document.getElementById(previewId);
                const file = e.target.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                }
            });
        }
        setupImagePreview('nid_input', 'nid_preview');
        setupImagePreview('profile_input', 'profile_preview');

        updatePaymentUI();
    </script>
@endsection