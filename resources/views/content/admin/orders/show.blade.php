@extends('layouts.app')

@section('content')
<div class="p-6 bg-slate-50/50 min-h-screen text-slate-700 antialiased">
    
    {{-- টপ ব্যাক বাটন ও টাইটেল বার --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="p-2.5 bg-white border border-slate-200 hover:border-slate-300 rounded-xl transition text-slate-500 hover:text-slate-800 shadow-sm group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight uppercase">অর্ডার ডিটেইলস</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">
                    অর্ডার আইডি: <span class="text-blue-600 font-bold">#ORD-{{ $order->id }}</span> | তারিখ: {{ $order->created_at->format('d M, Y - h:i A') }}
                </p>
            </div>
        </div>
        
        {{-- স্ট্যাটাস ব্যাজ --}}
        <div>
            @php
                $statusClasses = [
                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'active' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                ];
                $badgeClass = $statusClasses[$order->status] ?? $statusClasses['pending'];
            @endphp
            <span class="{{ $badgeClass }} px-4 py-2 rounded-xl text-xs font-black uppercase border tracking-wider shadow-sm">
                {{ $order->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start max-w-7xl mx-auto">
        
        {{-- বাম পাশ: অর্ডার করা প্রোডাক্ট আইটেম সমূহের তালিকা (8 Columns) --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm">
                <h3 class="text-slate-800 font-bold text-base mb-5 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    অর্ডারকৃত প্রোডাক্ট আইটেমস তালিকা
                </h3>

                <div class="space-y-5">
                    @foreach ($order->items as $item)
                        <div class="bg-slate-50/50 border border-slate-200/60 p-5 rounded-2xl flex flex-col gap-4 shadow-sm transition-all hover:border-slate-300">
                            
                            {{-- প্রোডাক্টের মূল রো --}}
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    {{-- থাম্বনেইল ছবি --}}
                                    <img src="{{ asset('storage/' . ($item->product->thumbnail ?? $item->thumbnail)) }}" 
                                         class="w-16 h-16 object-cover rounded-xl border border-slate-200 bg-white shadow-sm flex-shrink-0">
                                    <div>
                                        <h4 class="text-slate-800 font-bold text-sm line-clamp-1">{{ $item->name }}</h4>
                                        <p class="text-slate-400 text-xs font-semibold mt-1">প্রাইস: ৳{{ number_format($item->price) }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-sm font-extrabold text-slate-900">৳{{ number_format($item->price * $item->quantity) }}</div>
                                    <div class="text-[10px] text-amber-700 bg-amber-50 border border-amber-200/50 px-2 py-0.5 rounded-md font-bold mt-1 inline-block">
                                        পরিমাণ: {{ $item->quantity }}টি
                                    </div>
                                </div>
                            </div>

                            {{-- সিকেএডিটর থেকে আসা পণ্যের আলাদা স্পেসিফিকেশন নোট এরিয়া --}}
                            <div class="bg-white border border-slate-200 p-4 rounded-xl text-xs shadow-sm">
                                <div class="text-[10px] font-black uppercase text-blue-600 tracking-wider mb-2.5 flex items-center gap-1.5 border-b border-slate-50 pb-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 m-.293 1.707H14V4"></path>
                                    </svg>
                                    পণ্যের কাস্টম সাইজ/কালার রিকোয়ারমেন্ট নোট:
                                </div>
                                
                                @php 
                                    $rawNote = $item->product_note ?? $item->attributes['product_note'] ?? '';
                                @endphp

                                @if(!empty(trim(strip_tags($rawNote))))
                                    {{-- সিকেএডিটরের আউটপুট লাইট ইউআইতে রেন্ডার করার প্রফেশনাল কন্টেইনার --}}
                                    <div class="ck-admin-content-render text-slate-600 leading-relaxed">
                                        {!! $rawNote !!}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic block py-1">এই পণ্যের জন্য ক্রেতা আলাদা কোনো নির্দিষ্ট নোট প্রদান করেননি।</span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ডান পাশ: গ্রাহক তথ্য এবং বিলিং সামারি (4 Columns) --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- কাস্টমার প্রোফাইলカード --}}
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm">
                <h3 class="text-slate-800 font-bold text-base mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    গ্রাহকের বিবরণ
                </h3>
                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">নাম:</span>
                        <span class="text-slate-800 font-bold text-sm">{{ $order->user->name ?? $order->name ?? 'Guest User' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">ফোন নম্বর:</span>
                        <span class="text-blue-600 font-bold tracking-wider text-sm">{{ $order->phone }}</span>
                    </div>
                    @if($order->email)
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">ইমেইল:</span>
                        <span class="text-slate-600 font-medium">{{ $order->email }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">ডেলিভারি ঠিকানা:</span>
                        <span class="text-slate-600 bg-slate-50 border border-slate-200 p-3 rounded-xl block leading-relaxed shadow-inner font-medium">{{ $order->address }}</span>
                    </div>
                    @if($order->note)
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">অতিরিক্ত ডেলিভারি নির্দেশনা (General Note):</span>
                        <span class="text-amber-800 font-medium block bg-amber-50 border border-amber-200/70 p-3 rounded-xl leading-relaxed shadow-inner">{{ $order->note }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- পেমেন্ট ব্রেকডাউন সামারি উইজেট --}}
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm space-y-4">
                <h3 class="text-slate-800 font-bold text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    পেমেন্ট ক্যালকুলেশন
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>ইনকাম ওয়ালেট পেইড:</span>
                        <span class="font-bold text-emerald-600">৳{{ number_format($order->wallet_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>ক্যাশ অন ডেলিভারি (COD):</span>
                        <span class="font-bold text-orange-600">৳{{ number_format($order->cash_on_delivery, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-slate-800 font-black text-sm border-t border-slate-100 pt-3.5 mt-2">
                        <span class="text-slate-500 font-bold">সর্বমোট অর্ডার ভ্যালু:</span>
                        <span class="text-xl text-slate-900 font-black tracking-tight">৳{{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- সিকেএডিটর ফরম্যাটিং লাইট মোডে নিখুঁতভাবে রেন্ডার করার জন্য গ্লোবাল স্টাইলশিট ফিক্স --}}
<style>
    /* বেসিক টেক্সট ফরম্যাটিং */
    .ck-admin-content-render {
        font-size: 13px !important;
    }
    .ck-admin-content-render p { 
        margin-bottom: 0.6rem; 
        color: #334155; /* slate-700 */
    }
    .ck-admin-content-render p:last-child { 
        margin-bottom: 0; 
    }
    .ck-admin-content-render strong { 
        color: #0f172a !important; /* slate-900 */
        font-weight: 700; 
    }
    .ck-admin-content-render em { 
        font-style: italic; 
        color: #475569; /* slate-600 */
    }
    
    /* লিস্ট বা তালিকা স্টাইলিং */
    .ck-admin-content-render ul { 
        list-style-type: disc !important; 
        margin-left: 1.5rem !important; 
        margin-bottom: 0.75rem !important; 
    }
    .ck-admin-content-render ol { 
        list-style-type: decimal !important; 
        margin-left: 1.5rem !important; 
        margin-bottom: 0.75rem !important; 
    }
    .ck-admin-content-render li { 
        margin-bottom: 0.25rem; 
        color: #334155;
    }

    /* সিকেএডিটর টেবিল লাইট মোড ফিক্স */
    .ck-admin-content-render table { 
        width: 100% !important; 
        border-collapse: collapse !important; 
        margin: 0.75rem 0 !important; 
        border: 1px solid #e2e8f0 !important; /* slate-200 */
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }
    .ck-admin-content-render table td, 
    .ck-admin-content-render table th { 
        border: 1px solid #e2e8f0 !important; 
        padding: 8px 12px !important; 
        text-align: left; 
        color: #334155 !important;
    }
    .ck-admin-content-render table th { 
        background-color: #f8fafc !important; /* slate-50 */
        color: #0f172a !important; 
        font-weight: bold;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.05em;
    }
    .ck-admin-content-render table tr:hover {
        background-color: #f1f5f9 !important; /* slate-100 */
    }

    /* ব্লককোট (Blockquote) স্টাইল */
    .ck-admin-content-render blockquote {
        border-left: 3px solid #2563eb !important; /* blue-600 */
        padding-left: 1rem !important;
        margin-bottom: 0.6rem !important;
        font-style: italic !important;
        color: #475569 !important;
        background: #f8fafc; /* slate-50 */
    }
</style>
@endsection