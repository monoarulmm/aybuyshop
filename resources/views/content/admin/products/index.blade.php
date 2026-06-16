@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50/50 py-10 px-4">
        <div class="max-w-6xl mx-auto bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-sm">
            
            {{-- হেডার সেকশন --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">প্রোডাক্ট ম্যানেজমেন্ট</h2>
                    <p class="text-slate-500 text-sm mt-1">আপনার স্টোরের সকল প্রোডাক্ট এখানে দেখুন ও পরিচালনা করুন</p>
                </div>
                <a href="{{ route('products.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-bold transition-all shadow-md shadow-blue-600/10 flex items-center gap-2 transform hover:scale-[1.02] active:scale-95 text-sm">
                    <span class="text-lg font-black">+</span> নতুন প্রোডাক্ট যোগ করুন
                </a>
            </div>

            {{-- টেবিল সেকশন --}}
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-500 bg-slate-50/70 border-b border-slate-200 text-xs font-bold uppercase tracking-wider">
                            <th class="py-4 px-4 font-bold text-center w-20">ইমেজ</th>
                            <th class="py-4 px-4 font-bold">প্রোডাক্টের নাম</th>
                            <th class="py-4 px-4 font-bold">ক্যাটাগরি</th>
                            <th class="py-4 px-4 font-bold">মূল্য</th>
                            <th class="py-4 px-4 font-bold text-center">স্টক পরিমাণ</th>
                            <th class="py-4 px-4 font-bold text-right w-28">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($products as $product)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- প্রোডাক্ট ইমেজ --}}
                                <td class="py-4 px-4 text-center">
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                        class="w-12 h-12 rounded-xl object-cover border border-slate-200 mx-auto shadow-sm group-hover:scale-105 transition-transform">
                                </td>
                                {{-- নাম ও আইডি --}}
                                <td class="py-4 px-4">
                                    <p class="text-slate-800 font-bold group-hover:text-blue-600 transition-colors">{{ $product->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-widest mt-0.5">
                                        ID: #{{ $product->id }}
                                    </p>
                                </td>
                                {{-- ক্যাটাগরি --}}
                                <td class="py-4 px-4">
                                    <span class="bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full text-[11px] border border-slate-200/50">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                {{-- মূল্য --}}
                                <td class="py-4 px-4 font-extrabold text-slate-900">
                                    ৳{{ number_format($product->price) }}
                                </td>
                                {{-- স্টক স্ট্যাটাস --}}
                                <td class="py-4 px-4 text-center">
                                    @if($product->stock > 0)
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/60 px-3 py-1 rounded-xl text-xs font-bold inline-block">
                                            {{ $product->stock }} টি আছে
                                        </span>
                                    @else
                                        <span class="bg-rose-50 text-rose-600 border border-rose-200/60 px-3 py-1 rounded-xl text-xs font-bold inline-block">
                                            স্টক আউট
                                        </span>
                                    @endif
                                </td>
                                {{-- অ্যাকশন বাটন --}}
                                <td class="py-4 px-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100"
                                            title="এডিট করুন">
                                            📝
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই প্রোডাক্টটি ডিলিট করতে চান?')">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100"
                                                title="ডিলিট করুন">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 italic">
                                    কোনো প্রোডাক্ট খুঁজে পাওয়া যায়নি!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection