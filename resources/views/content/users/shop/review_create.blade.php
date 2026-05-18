@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-10 px-4">
        <div class="bg-[#111421] rounded-[2.5rem] border border-gray-800 p-8 shadow-2xl">
            <h2 class="text-2xl font-black text-white italic uppercase mb-2">প্রোডাক্ট রিভিউ</h2>
            <p class="text-yellow-500 text-xs font-bold mb-8 uppercase tracking-widest">{{ $product->name }}</p>

            <form action="{{ route('product.review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- রেটিং --}}
                <div class="mb-6">
                    <label class="text-gray-500 text-[10px] font-black uppercase tracking-widest block mb-4">রেটিং
                        দিন</label>
                    <div class="flex justify-between bg-[#0a0c14] p-2 rounded-2xl border border-gray-800">
                        @for ($i = 5; $i >= 1; $i--)
                            <label class="flex-1 text-center cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer"
                                    required>
                                <div
                                    class="py-3 rounded-xl peer-checked:bg-yellow-500 peer-checked:text-black text-gray-500 transition-all">
                                    <span class="text-sm font-black">{{ $i }}⭐</span>
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>

                {{-- মন্তব্য --}}
                <div class="mb-8">
                    <label class="text-gray-500 text-[10px] font-black uppercase tracking-widest block mb-3">আপনার
                        অভিজ্ঞতা</label>
                    <textarea name="comment" required placeholder="প্রোডাক্টটি কেমন লেগেছে লিখুন..."
                        class="w-full bg-[#0a0c14] border border-gray-800 rounded-[1.5rem] p-5 text-white text-sm focus:border-blue-600 outline-none h-40 italic transition-all shadow-inner"></textarea>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('orders.my') }}"
                        class="flex-1 bg-gray-800/50 text-center text-gray-400 font-black py-4 rounded-2xl text-xs uppercase transition-all">ফিরে
                        যান</a>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-2xl text-xs uppercase shadow-xl shadow-blue-600/20 transition-all">সাবমিট
                        রিভিউ</button>
                </div>
            </form>
        </div>
    </div>
@endsection
