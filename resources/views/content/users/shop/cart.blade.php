@extends('layouts.app')

{{-- সিকেএডিটর এর স্টাইল লাইট/প্রিমিয়াম থিমের সাথে নিখুঁতভাবে মেলানোর জন্য কাস্টম সিএসএস --}}
@section('styles')
<style>
    /* সিকেএডিটর মেইন কন্টেইনার ও এডিটেবল এরিয়া */
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused),
    .ck.ck-editor__main>.ck-editor__editable.ck-focused {
        min-height: 120px !important;
        background-color: #f9fafb !important; /* Light slate/gray background */
        color: #1f2937 !important; /* Dark text */
        border: 1px solid #e5e7eb !important;
        border-radius: 0 0 1rem 1rem !important;
    }
    
    /* এডিটর ফোকাসড হলে বর্ডার গ্লো */
    .ck.ck-editor__main>.ck-editor__editable.ck-focused {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    }

    /* টুলবার ব্যাকগ্রাউন্ড ও বর্ডার ফিক্স */
    .ck.ck-toolbar {
        background: #f3f4f6 !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 1rem 1rem 0 0 !important;
    }

    /* টুলবারের ভেতরের বাটন রিসেট */
    .ck.ck-toolbar .ck-button {
        color: #4b5563 !important;
        cursor: pointer !important;
    }

    /* বাটন হোভার ও একটিভ স্টেট */
    .ck.ck-toolbar .ck-button:hover,
    .ck.ck-button:not(.ck-disabled):hover {
        background: #e5e7eb !important;
        color: #111827 !important;
    }

    .ck.ck-toolbar .ck-button.ck-on {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    /* ড্রপডাউন এবং টেবিল পপআপ ইনপুট ফিক্স (লাইট মোড সাপোর্ট) */
    .ck.ck-list, .ck.ck-balloon-panel {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    .ck.ck-list__item .ck-button:hover:not(.ck-disabled) {
        background: #2563eb !important;
        color: #ffffff !important;
    }
    .ck.ck-placeholder::before {
        color: #9ca3af !important;
    }
</style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-[#f8fafc] min-h-screen">
        
        {{-- হেডার সেকশন --}}
        <div class="flex items-center justify-between mb-10 border-b border-gray-200 pb-5">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">আপনার শপিং কার্ট</h2>
                <p class="mt-2 text-sm text-gray-500">অর্ডার সম্পন্ন করতে আইটেমগুলো যাচাই করুন।</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition flex items-center gap-2 group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                শপিং চালিয়ে যান
            </a>
        </div>

        {{-- সফল বা ভ্যালিডেশন মেসেজ অ্যালার্ট --}}
        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl font-semibold flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-sm space-y-1 shadow-sm">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-600 rounded-full"></span>
                        <p class="font-medium">{{ $error }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if (\Cart::isEmpty())
            {{-- ফাকা কার্ট স্টেট --}}
            <div class="bg-white p-16 rounded-[2.5rem] text-center border border-gray-200/80 shadow-xl max-w-xl mx-auto my-12">
                <div class="w-24 h-24 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-6 ring-4 ring-gray-50">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-gray-900 text-xl font-bold mb-2">কার্ট একদম খালি!</h3>
                <p class="text-gray-500 text-sm mb-8">মনে হচ্ছে আপনি এখনও কোনো পণ্য পছন্দ করেননি।</p>
                <a href="{{ route('shop.index') }}"
                    class="inline-block bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 active:scale-95">শপ ভিজিট করুন</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- বাম পাশ: কার্ট আইটেমস লিস্ট (7 Cols) --}}
                <div class="lg:col-span-7 space-y-5">
                    @foreach (\Cart::getContent() as $item)
                        <div class="bg-white p-6 rounded-3xl border border-gray-200/60 flex flex-col gap-5 transition-all hover:border-gray-300 shadow-sm hover:shadow-md">
                            
                            {{-- প্রোডাক্টের বিবরণী --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $item->attributes->thumbnail) }}"
                                        class="w-20 h-20 rounded-2xl object-cover border border-gray-100 shadow-sm flex-shrink-0">
                                    <div>
                                        <h4 class="text-gray-900 font-bold text-base line-clamp-1 mb-1">{{ $item->name }}</h4>
                                        <p class="text-amber-600 font-extrabold text-sm mb-2">৳{{ number_format($item->price) }}</p>
                                        
                                        <span class="text-[11px] text-gray-600 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200 font-semibold">
                                            মোট: ৳{{ number_format($item->price * $item->quantity) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- কোয়ান্টিটি ও অ্যাকশন কন্ট্রোল --}}
                                <div class="flex items-center justify-between sm:justify-end gap-4 border-t border-gray-100 pt-4 sm:pt-0 sm:border-t-0">
                                    {{-- কোয়ান্টিটি কাউন্টার --}}
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center bg-gray-50 border border-gray-200 rounded-xl overflow-hidden shadow-inner">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        
                                        <button type="submit" name="quantity" value="-1" class="px-3.5 py-2 text-gray-500 hover:text-gray-900 hover:bg-gray-200/50 transition font-bold text-lg">-</button>
                                        <span class="px-4 py-2 text-gray-900 font-bold text-sm bg-white border-x border-gray-200 min-w-[45px] text-center">{{ $item->quantity }}</span>
                                        <button type="submit" name="quantity" value="1" class="px-3.5 py-2 text-gray-500 hover:text-gray-900 hover:bg-gray-200/50 transition font-bold text-lg">+</button>
                                    </form>

                                    {{-- রিমুভ বাটন --}}
                                    <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('আপনি কি পণ্যটি কার্ট থেকে বাদ দিতে চান?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm border border-rose-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- সিকেএডিটর স্পেসিফিকেশন নোট --}}
                            <div class="border-t border-gray-100 pt-4">
                                <div class="space-y-2.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        প্রোডাক্ট স্পেসিফিকেশন (সাইজ, কালার বা কাস্টমাইজেশন)
                                    </label>
                                    
                                    <div class="rounded-2xl overflow-hidden border border-transparent">
                                        <textarea id="editor-{{ $item->id }}" data-product-id="{{ $item->id }}" class="ck-product-editor" placeholder="এই প্রোডাক্টের রিকোয়ারমেন্ট এখানে টেবিল বা লিস্ট আকারে লিখুন...">{{ $item->attributes->product_note ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- ডান পাশ: চেকআউট ফর্ম এবং পেমেন্ট সামারি (5 Cols) --}}
                <div class="lg:col-span-5">
                    <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] border border-gray-200/80 shadow-lg space-y-6 sticky top-6">
                        <h3 class="text-gray-900 font-bold text-lg flex items-center gap-2 border-b border-gray-100 pb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            শিপিং ও অর্ডার বিবরণী
                        </h3>
                        
                        <form id="mainOrderForm" action="{{ route('order.place') }}" method="POST" class="space-y-5">
                            @csrf

                            {{-- জাভাস্ক্রিপ্ট ডাইনামিকলি প্রোডাক্ট নোটগুলো এখানে পুশ করবে --}}
                            <div id="hiddenNotesContainer"></div>

                            @guest
                                <div class="space-y-4">
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="আপনার নাম"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition" required>
                                    
                                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="মোবাইল নম্বর"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition" required>
                                    
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="ইমেইল এড্রেস (ঐচ্ছিক)"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition">
                                </div>
                            @endguest

                            {{-- ঠিকানা ইনপুট --}}
                            <div class="space-y-1">
                                <textarea name="address" placeholder="আপনার সম্পূর্ণ ডেলিভারি ঠিকানা লিখুন..." rows="3"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition resize-none" required>{{ old('address') }}</textarea>
                            </div>

                            {{-- ডেলিভারি নোট --}}
                            <div class="space-y-2">
                                <label class="text-gray-500 text-[11px] block ml-1 font-bold uppercase tracking-wider">অতিরিক্ত ডেলিভারি নোট (ঐচ্ছিক)</label>
                                <textarea name="note" placeholder="যেমন: ডেলিভারি সময় বা কোনো বিশেষ কাস্টম বার্তা..." rows="2"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition resize-none">{{ old('note') }}</textarea>
                            </div>

                            {{-- পেমেন্ট ব্রেকডাউন উইজেট --}}
                            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200 space-y-3.5 shadow-inner">
                                <div class="flex justify-between text-gray-500 text-sm">
                                    <span>মোট আইটেম সংখ্যা:</span>
                                    <span class="font-bold text-gray-800">{{ \Cart::getTotalQuantity() }} টি</span>
                                </div>
                                <div class="flex justify-between text-gray-500 text-sm">
                                    <span>পণ্যের মোট মূল্য:</span>
                                    <span class="font-bold text-gray-800">৳{{ number_format(\Cart::getTotal()) }}</span>
                                </div>

                                @auth
                                    @php
                                        $user = auth()->user();
                                        $cartTotal = \Cart::getTotal();
                                        $totalEarnings = \DB::table('user_earnings')->where('user_id', $user->id)->sum('amount');
                                        $walletPaid = 0;
                                        
                                        if ($user->role !== 'n_user' && in_array($user->type, ['basic', 'premium', 'premium_pro'])) {
                                            $walletPaid = min($totalEarnings, $cartTotal);
                                        }
                                        $payableAmount = $cartTotal - $walletPaid;
                                    @endphp

                                    @if ($walletPaid > 0)
                                        <div class="flex justify-between text-emerald-600 text-sm pt-2.5 border-t border-gray-200 italic">
                                            <span>ওয়ালেট অ্যাডজাস্টমেন্ট:</span>
                                            <span class="font-extrabold">- ৳{{ number_format($walletPaid, 2) }}</span>
                                        </div>
                                        <div class="text-[10px] text-gray-400 text-right italic">
                                            (বর্তমান ইনকাম ব্যালেন্স: ৳{{ number_format($totalEarnings, 2) }})
                                        </div>
                                    @endif
                                @else
                                    @php $payableAmount = \Cart::getTotal(); @endphp
                                @endauth

                                {{-- সর্বমোট পেয়েবল বিল --}}
                                <div class="flex justify-between items-center text-gray-900 font-bold text-base border-t border-gray-200 pt-3.5 mt-2">
                                    <div>
                                        <span class="block uppercase text-[9px] text-blue-600 tracking-widest font-black">Net Payable</span>
                                        <span>সর্বমোট পরিশোধযোগ্য:</span>
                                    </div>
                                    <span class="text-2xl text-amber-600 font-black tracking-tight">৳{{ number_format($payableAmount) }}</span>
                                </div>
                            </div>

                            {{-- কনফার্ম সাবমিট বাটন --}}
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/10 transition-all transform hover:-translate-y-0.5 active:scale-95 text-center block text-base tracking-wide">
                                অর্ডার নিশ্চিত করুন
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @endif
    </div>

{{-- CKEditor 5 CDN স্ক্রিপ্ট এবং প্রফেশনাল বাইন্ডিং স্ক্রিপ্ট --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editorInstances = {};

        // সিকেএডিটর ডাইনামিক রেন্ডারিং লোড করা হচ্ছে
        document.querySelectorAll('.ck-product-editor').forEach(function(textarea) {
            const productId = textarea.getAttribute('data-product-id');
            
            ClassicEditor
                .create(textarea, {
                    toolbar: [ 
                        'insertTable', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' 
                    ],
                    table: {
                        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    },
                    placeholder: textarea.getAttribute('placeholder')
                })
                .then(editor => {
                    editorInstances[productId] = editor;
                })
                .catch(error => {
                    console.error('Editor Initialization Error:', error);
                });
        });

        // ফর্ম সাবমিশনের সময় হিডেন ডাটা হ্যান্ডেলিং
        const mainForm = document.getElementById('mainOrderForm');
        if (mainForm) {
            mainForm.addEventListener('submit', function(e) {
                const container = document.getElementById('hiddenNotesContainer');
                container.innerHTML = ''; 

                Object.keys(editorInstances).forEach(function(productId) {
                    const editorData = editorInstances[productId].getData();

                    // Headless অ্যারে ডাটা ইনপুট মেমরি রেন্ডার
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `product_notes[${productId}]`;
                    hiddenInput.value = editorData;
                    
                    container.appendChild(hiddenInput);
                });
            });
        }
    });
</script>
@endsection