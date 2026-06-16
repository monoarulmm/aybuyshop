@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-[#f8fafc] min-h-screen">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 uppercase tracking-tight">আমার অর্ডারসমূহ</h2>
            <a href="{{ route('shop.index') }}" class="text-blue-600 hover:text-blue-700 text-sm transition-all font-bold flex items-center gap-1">
                আরো শপিং করুন 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        {{-- ফ্ল্যাশ মেসেজ সেকশন --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold shadow-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="bg-white p-20 rounded-[3rem] text-center border border-gray-200/80 shadow-sm">
                <div class="mb-6 flex justify-center text-gray-300">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 text-xl font-semibold">আপনি এখনো কোনো অর্ডার করেননি!</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-[2.5rem] border border-gray-200/80 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">

                        {{-- কার্ড হেডার --}}
                        <div class="bg-gray-50/70 p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">অর্ডার আইডি</p>
                                <h3 class="text-blue-600 font-extrabold text-xl tracking-wide">#ORD-{{ $order->id }}</h3>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">তারিখ</p>
                                <p class="text-gray-700 font-bold text-sm">{{ $order->created_at->format('d M, Y') }}</p>
                            </div>
                            <div>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'delivered' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                    $currentClass = $statusClasses[$order->status] ?? $statusClasses['pending'];
                                @endphp
                                <span class="{{ $currentClass }} px-4 py-1.5 rounded-xl text-xs font-bold uppercase border tracking-wider">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        {{-- কার্ড বডি --}}
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <div class="space-y-3">
                                    <h4 class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">ডেলিভারি ঠিকানা</h4>
                                    <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 min-h-[110px] flex flex-col justify-between">
                                        <p class="text-gray-600 leading-relaxed text-sm font-medium">"{{ $order->address }}"</p>
                                        <p class="text-gray-900 font-bold text-sm flex items-center gap-2 mt-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $order->phone }}
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                                    <h4 class="text-gray-400 font-bold mb-4 border-b border-gray-100 pb-2 text-[10px] uppercase tracking-wider">পেমেন্ট সামারি</h4>
                                    <div class="space-y-2.5">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Subtotal</span>
                                            <span class="text-gray-800 font-semibold">৳{{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm text-emerald-600 font-semibold">
                                            <span>Wallet Used</span>
                                            <span>- ৳{{ number_format($order->wallet_paid, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-base font-black text-gray-900 border-t border-gray-200/60 pt-3 mt-1">
                                            <span class="uppercase text-xs tracking-wider text-gray-500">COD Payable</span>
                                            <span class="text-amber-600 text-lg">৳{{ number_format($order->cash_on_delivery, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- প্রোডাক্ট রিভিউ সেকশন --}}
                            @if ($order->status == 'delivered' && $order->products->count() > 0)
                                <div class="mt-8 border-t border-gray-100 pt-6">
                                    <div class="flex items-center gap-3 mb-5">
                                        <div class="h-px flex-1 bg-gray-100"></div>
                                        <h4 class="text-amber-600 font-bold text-[10px] uppercase tracking-widest flex items-center gap-1">
                                            ⭐⭐⭐⭐⭐ প্রোডাক্ট রিভিউ দিন
                                        </h4>
                                        <div class="h-px flex-1 bg-gray-100"></div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach ($order->products as $product)
                                            <div class="flex items-center justify-between bg-gray-50/50 p-4 rounded-2xl border border-gray-100 hover:border-blue-200 transition-all duration-300">
                                                <div class="flex items-center gap-4 min-w-0">
                                                    <div class="relative w-12 h-12 flex-shrink-0">
                                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                            class="w-full h-full rounded-xl object-cover shadow-sm"
                                                            onerror="this.src='https://via.placeholder.com/150'">
                                                    </div>
                                                    <span class="text-gray-800 text-sm font-bold truncate pr-2">{{ $product->name }}</span>
                                                </div>

                                                <a href="{{ route('product.review.create', $product->id) }}"
                                                    class="bg-gray-950 hover:bg-gray-800 text-white text-xs px-4 py-2.5 rounded-xl font-bold uppercase shadow-sm transition-all flex-shrink-0">
                                                    Write Review
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection