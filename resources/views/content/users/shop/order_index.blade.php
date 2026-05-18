@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black text-white italic uppercase tracking-wider">আমার অর্ডারসমূহ</h2>
            <a href="{{ route('shop.index') }}" class="text-blue-500 hover:underline text-sm transition-all font-bold">আরো
                শপিং করুন</a>
        </div>

        {{-- ফ্ল্যাশ মেসেজ সেকশন --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-2xl text-sm font-bold animate-pulse">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="bg-[#111421] p-20 rounded-[3rem] text-center border border-gray-800 shadow-2xl">
                <div class="mb-6 flex justify-center text-gray-700">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-gray-400 text-xl font-medium italic">আপনি এখনো কোনো অর্ডার করেননি!</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($orders as $order)
                    <div
                        class="bg-[#111421] rounded-[2.5rem] border border-gray-800 overflow-hidden shadow-2xl hover:border-gray-700 transition-all duration-300">

                        {{-- কার্ড হেডার --}}
                        <div
                            class="bg-[#161b2c] p-6 border-b border-gray-800 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mb-1">অর্ডার আইডি
                                </p>
                                <h3 class="text-white font-black text-xl italic text-blue-500">#ORD-{{ $order->id }}</h3>
                            </div>
                            <div class="text-center md:text-right">
                                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mb-1">তারিখ</p>
                                <p class="text-gray-300 font-bold">{{ $order->created_at->format('d M, Y') }}</p>
                            </div>
                            <div>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                        'delivered' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                        'active' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                        'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    ];
                                    $currentClass = $statusClasses[$order->status] ?? $statusClasses['pending'];
                                @endphp
                                <span
                                    class="{{ $currentClass }} px-5 py-2 rounded-xl text-[10px] font-black uppercase border tracking-widest">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        {{-- কার্ড বডি --}}
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8">
                                <div class="space-y-4">
                                    <h4 class="text-gray-500 text-[10px] font-black uppercase tracking-widest">ডেলিভারি
                                        ঠিকানা</h4>
                                    <div class="bg-[#0a0c14] p-5 rounded-2xl border border-gray-800/50">
                                        <p class="text-gray-300 leading-relaxed text-sm mb-3 italic">"{{ $order->address }}"
                                        </p>
                                        <p class="text-blue-400 font-black text-sm flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $order->phone }}
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-[#0a0c14] rounded-3xl p-6 border border-gray-800 shadow-inner">
                                    <h4
                                        class="text-yellow-500 font-black mb-4 border-b border-gray-800 pb-3 text-[10px] uppercase italic tracking-widest">
                                        পেমেন্ট সামারি</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-500">Subtotal</span>
                                            <span
                                                class="text-gray-300 font-bold">৳{{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs text-green-500 font-bold">
                                            <span>Wallet Used</span>
                                            <span>- ৳{{ number_format($order->wallet_paid, 2) }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between text-lg font-black text-white border-t border-gray-800 pt-3">
                                            <span class="italic uppercase text-sm">COD Payable</span>
                                            <span
                                                class="text-yellow-500">৳{{ number_format($order->cash_on_delivery, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- প্রোডাক্ট রিভিউ সেকশন --}}
                            @if ($order->status == 'delivered' && $order->products->count() > 0)
                                <div class="mt-8 border-t border-gray-800 pt-8">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="h-px flex-1 bg-gray-800"></div>
                                        <h4
                                            class="text-yellow-500 font-black text-[10px] uppercase tracking-[0.2em] italic">
                                            ⭐⭐⭐⭐⭐ রিভিউ দিন
                                        </h4>
                                        <div class="h-px flex-1 bg-gray-800"></div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach ($order->products as $product)
                                            <div
                                                class="flex items-center justify-between bg-[#161b2c] p-4 rounded-2xl border border-gray-800 hover:border-blue-500/50 transition-all duration-300">
                                                <div class="flex items-center gap-4">
                                                    <div class="relative w-12 h-12 flex-shrink-0">
                                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                            class="w-full h-full rounded-xl object-cover shadow-lg"
                                                            onerror="this.src='https://via.placeholder.com/150'">
                                                        <div class="absolute inset-0 bg-blue-500/5 rounded-xl"></div>
                                                    </div>
                                                    <span
                                                        class="text-white text-sm font-black italic truncate max-w-[150px]">{{ $product->name }}</span>
                                                </div>

                                                <a href="{{ route('product.review.create', $product->id) }}"
                                                    class="bg-blue-600 hover:bg-blue-500 text-white text-[10px] px-4 py-2.5 rounded-xl font-black uppercase tracking-tighter shadow-lg shadow-blue-600/10 active:scale-95 transition-all">
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
