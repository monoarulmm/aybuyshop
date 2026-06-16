@extends('layouts.app')

@section('content')
    {{-- মেইন কন্টেইনার (ক্লিন গ্রে ব্যাকগ্রাউন্ড) --}}
    <div class="bg-gray-50/50 min-h-screen py-6 px-3 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            {{-- হেডার সেকশন --}}
            <div class="mb-6 flex justify-between items-center pb-4 border-b border-gray-200/60">
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase sm:text-2xl">আমাদের <span class="text-blue-600">শপ</span></h1>
                    <p class="text-[11px] text-gray-400 font-medium hidden sm:block">আপনার পছন্দের পণ্যগুলো সহজে খুঁজে নিন</p>
                </div>
                
                {{-- কার্ট বাটন (কম্প্যাক্ট সাইজ) --}}
                <a href="{{ route('cart.index') }}"
                    class="relative bg-white border border-gray-200 hover:border-gray-900 text-gray-900 px-4 py-2 rounded-xl font-bold flex items-center gap-2 shadow-sm transition-all duration-300 active:scale-95 group">
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="text-xs font-black text-gray-800 uppercase tracking-wider hidden sm:inline">কার্ট</span> 
                    <span class="bg-blue-600 text-white px-1.5 py-0.5 rounded-md text-[10px] font-black">
                        {{ \Cart::getContent()->count() }}
                    </span>
                </a>
            </div>

            {{-- 🎯 যদি ইউজার কোনো কিছু সার্চ করে, তবে এখানে ছোট একটি নোটিশ দেখাবে --}}
            @if(request('query'))
                <div class="mb-4 text-xs sm:text-sm text-gray-500">
                    "<span class="font-bold text-gray-900">{{ request('query') }}</span>" এর জন্য অনুসন্ধান ফলাফল (<span class="font-bold text-blue-600">{{ $products->count() }}টি</span> পণ্য পাওয়া গেছে)
                </div>
            @endif

            {{-- 🎯 কন্ডিশন: প্রোডাক্ট লিস্ট যদি খালি থাকে (Not Found State) --}}
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl border border-gray-200/70 p-12 text-center max-w-md mx-auto my-12 shadow-sm animate-fade-in">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100 text-2xl">
                        🔍✕
                    </div>
                    <h3 class="text-gray-900 font-black text-base uppercase tracking-tight">কোনো পণ্য পাওয়া যায়নি!</h3>
                    <p class="text-gray-400 text-xs mt-1.5 leading-relaxed">
                        দুঃখিত, আপনার অনুসন্ধান করা পণ্যটি আমাদের ডাটাবেজে নেই। অনুগ্রহ করে অন্য কোনো কি-ওয়ার্ড দিয়ে আবার চেষ্টা করুন।
                    </p>
                    <div class="mt-5">
                        <a href="{{ url()->current() }}" class="inline-block bg-gray-950 hover:bg-gray-800 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-xl transition-all active:scale-95">
                            সব পণ্য লোড করুন
                        </a>
                    </div>
                </div>
            @else
                {{-- প্রোডাক্ট গ্রিড (যদি প্রোডাক্ট থাকে, তবে এটি দেখাবে) --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-2xl overflow-hidden border border-gray-200/70 shadow-sm hover:shadow-md group transition-all duration-300 flex flex-col justify-between relative p-2">
                            
                            {{-- ছোট স্টক ব্যাজ --}}
                            @if ($product->stock <= 0)
                                <span class="absolute top-3 left-3 z-10 bg-red-500/90 backdrop-blur-sm text-white text-[8px] font-black uppercase px-2 py-0.5 rounded-md">Stock Out</span>
                            @endif

                            <div>
                                {{-- ইমেজ সেকশন --}}
                                <div class="relative aspect-square overflow-hidden bg-gray-50 rounded-xl">
                                    <a href="{{ route('shop.show', $product->id) }}" class="block w-full h-full">
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            alt="{{ $product->name }}">
                                    </a>
                                </div>

                                {{-- বিবরণী সেকশন --}}
                                <div class="pt-2 px-1">
                                    <a href="{{ route('shop.show', $product->id) }}" class="block">
                                        <h3 class="text-gray-900 font-bold text-xs sm:text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    <p class="text-gray-400 text-[10px] sm:text-xs line-clamp-1 mt-0.5">
                                        {{ \Illuminate\Support\Str::words(strip_tags($product->description ?? ''), 8, '...') }}
                                    </p>
                                </div>
                            </div>

                            {{-- প্রাইস এবং কার্ট অ্যাকশন বাটন জোন --}}
                            <div class="pt-2 mt-2 border-t border-gray-100 px-1 pb-1">
                                <div class="flex items-center justify-between gap-1">
                                    {{-- প্রাইস --}}
                                    <div class="flex flex-col">
                                        <span class="text-[11px] sm:text-sm font-black text-gray-900">
                                            ৳{{ number_format($product->price) }}
                                        </span>
                                    </div>

                                    {{-- ছোট অ্যাড বাটন --}}
                                    <div>
                                        @if ($product->stock > 0)
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $product->id }}">
                                                <input type="hidden" name="name" value="{{ $product->name }}">
                                                <input type="hidden" name="price" value="{{ $product->price }}">
                                                <input type="hidden" name="thumbnail" value="{{ $product->thumbnail }}">

                                                <button type="submit"
                                                    class="bg-gray-900 hover:bg-blue-600 text-white p-2 sm:px-3 sm:py-1.5 rounded-lg font-bold transition-all duration-200 active:scale-95 flex items-center justify-center gap-1 text-[10px] uppercase"
                                                    title="কার্টে যুক্ত করুন">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Add</span>
                                                </button>
                                            </form>
                                        @else
                                            <button class="bg-gray-100 text-gray-400 p-1.5 rounded-lg text-[9px] font-bold cursor-not-allowed border border-gray-200 uppercase" disabled>
                                                Out
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection