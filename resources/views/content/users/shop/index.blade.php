@extends('layouts.app')

@section('content')
    <div class="mb-10 flex justify-between items-center px-2">
        <h1 class="text-3xl font-black text-white italic uppercase tracking-tighter">আমাদের শপ</h1>
        <a href="{{ route('cart.index') }}"
            class="bg-yellow-500 hover:bg-yellow-400 text-black px-6 py-2.5 rounded-2xl font-bold flex items-center gap-2 shadow-[0_10px_20px_rgba(234,179,8,0.2)] transition-all active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            কার্ট <span class="bg-black text-white px-2 py-0.5 rounded-lg text-xs">{{ \Cart::getContent()->count() }}</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($products as $product)
            <div
                class="bg-[#111421] rounded-[2.5rem] overflow-hidden border border-gray-800 shadow-xl group hover:border-yellow-500/30 transition-all duration-500">

                {{-- ইমেজ সেকশন - এখানে ক্লিক করলে ডিটেইলস পেজে যাবে --}}
                <div class="relative aspect-square overflow-hidden">
                    <a href="{{ route('shop.show', $product->id) }}" class="block w-full h-full">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="{{ $product->name }}">

                        {{-- ওভারলে ইফেক্ট --}}
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                            <span
                                class="bg-white/10 backdrop-blur-md text-white px-6 py-2 rounded-full border border-white/20 font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">বিস্তারিত
                                দেখুন</span>
                        </div>
                    </a>

                    {{-- প্রাইস ট্যাগ --}}
                    <div
                        class="absolute top-5 right-5 bg-yellow-500 text-black px-4 py-1.5 rounded-2xl font-black text-sm shadow-xl">
                        ৳{{ number_format($product->price) }}
                    </div>
                </div>

                <div class="p-6">
                    <a href="{{ route('shop.show', $product->id) }}">
                        <h3 class="text-white font-bold text-lg mb-2 group-hover:text-yellow-500 transition-colors">
                            {{ $product->name }}</h3>
                    </a>
                    <p class="text-gray-500 text-xs mb-6 line-clamp-2 leading-relaxed">{{ $product->description }}</p>

                    <div class="flex items-center justify-between gap-4">
                        @if ($product->stock > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                <input type="hidden" name="thumbnail" value="{{ $product->thumbnail }}">

                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-[1.2rem] font-bold transition-all shadow-lg shadow-blue-900/20 active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    কার্টে যুক্ত করুন
                                </button>
                            </form>
                        @else
                            <button
                                class="w-full bg-gray-800/50 text-gray-600 py-4 rounded-[1.2rem] font-bold cursor-not-allowed border border-gray-800"
                                disabled>স্টক আউট</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
