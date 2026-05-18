@extends('layouts.app')

@section('content')
    <div class="bg-[#111421] rounded-3xl p-6 border border-gray-800 shadow-xl">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">প্রোডাক্ট ম্যানেজমেন্ট</h2>
                <p class="text-gray-400 text-sm">আপনার স্টোরের সকল প্রোডাক্ট এখানে দেখুন</p>
            </div>
            <a href="{{ route('products.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg flex items-center gap-2">
                <span class="text-xl">+</span> নতুন প্রোডাক্ট
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-800">
                        <th class="pb-4 font-medium px-2">ইমেজ</th>
                        <th class="pb-4 font-medium">নাম</th>
                        <th class="pb-4 font-medium">ক্যাটাগরি</th>
                        <th class="pb-4 font-medium">মূল্য</th>
                        <th class="pb-4 font-medium text-center">স্টক</th>
                        <th class="pb-4 font-medium text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach ($products as $product)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="py-4 px-2">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                    class="w-12 h-12 rounded-xl object-cover border border-gray-700">
                            </td>
                            <td class="py-4">
                                <p class="text-white font-bold">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest italic">ID:
                                    #{{ $product->id }}</p>
                            </td>
                            <td class="py-4 text-gray-300 text-sm">
                                <span
                                    class="bg-gray-800 px-3 py-1 rounded-full text-[11px]">{{ $product->category->name }}</span>
                            </td>
                            <td class="py-4 text-yellow-500 font-black">৳{{ $product->price }}</td>
                            <td class="py-4 text-center">
                                <span class="{{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }} font-bold">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="p-2 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition-all">
                                        📝
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('ডিলিট করতে চান?')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
