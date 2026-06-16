@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-12 px-4 bg-[#f8fafc] min-h-screen flex flex-col justify-center">
        <div class="bg-white rounded-[2.5rem] border border-gray-200/80 p-8 sm:p-10 shadow-sm">
            <h2 class="text-2xl font-extrabold text-gray-900 uppercase tracking-tight mb-1">প্রোডাক্ট রিভিউ</h2>
            <p class="text-amber-600 text-xs font-bold mb-8 uppercase tracking-wider">{{ $product->name }}</p>

            <form action="{{ route('product.review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- রেটিং রেডিও বাটন সেকশন --}}
                <div class="mb-6">
                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-wider block mb-3">রেটিং দিন</label>
                    <div class="flex justify-between bg-gray-50 p-2 rounded-2xl border border-gray-100">
                        @for ($i = 5; $i >= 1; $i--)
                            <label class="flex-1 text-center cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                <div class="py-3 rounded-xl peer-checked:bg-amber-500 peer-checked:text-white text-gray-400 hover:text-gray-600 transition-all font-bold">
                                    <span class="text-sm">{{ $i }}⭐</span>
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>

                {{-- মন্তব্য টেক্সট-এরিয়া --}}
                <div class="mb-8">
                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-wider block mb-3">আপনার অভিজ্ঞতা</label>
                    <textarea name="comment" required placeholder="প্রোডাক্টটি কেমন লেগেছে? আপনার সৎ মতামত লিখুন..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-5 text-gray-800 text-sm focus:border-blue-500 focus:bg-white outline-none h-40 transition-all placeholder-gray-400 resize-none font-medium"></textarea>
                </div>

                {{-- বাটন গ্রুপ --}}
                <div class="flex gap-4">
                    <a href="{{ route('orders.my') }}"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-center text-gray-600 font-bold py-4 rounded-2xl text-xs uppercase tracking-wide transition-all border border-gray-200/50">
                        ফিরে যান
                    </a>
                    <button type="submit"
                        class="flex-1 bg-gray-950 hover:bg-gray-800 text-white font-bold py-4 rounded-2xl text-xs uppercase tracking-wide shadow-md transition-all">
                        সাবমিট রিভিউ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection