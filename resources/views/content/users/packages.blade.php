@extends('layouts.app')

@section('content')
    <div class="container mx-auto pt-6 pb-20 px-4 max-w-7xl w-full overflow-x-hidden">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tight">
                Choose Your <span class="text-indigo-600">Success Plan</span>
            </h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto text-sm md:text-base font-medium">
                আপনার পছন্দের প্যাকেজটি বেছে নিন এবং আজই আয় করা শুরু করুন। প্যাকেজ যত বড়, আয়ের সুযোগ তত বেশি!
            </p>
        </div>

        {{-- Pricing Grid (3 Columns Layout) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

            {{-- Plan 1: Normal User --}}
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 flex flex-col hover:border-gray-200 hover:shadow-[0_20px_50px_rgba(0,0,0,0.02)] transition duration-300">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">নরমাল ইউজার</h3>
                    <div class="text-4xl font-black text-indigo-600">৳ ০ <span class="text-sm text-gray-400 font-bold">/লাইফটাইম</span></div>
                </div>
                
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-600 font-medium">
                        <span class="mr-3 text-emerald-500 font-bold text-base">✔</span> আনলিমিটেড ভিডিও (নো কয়েন)
                    </li>
                    <li class="flex items-center text-sm text-gray-600 font-medium">
                        <span class="mr-3 text-emerald-500 font-bold text-base">✔</span> প্রতি টাস্ক ভিডিও: ৫ কয়েন্স
                    </li>
                    <li class="flex items-center text-sm text-gray-400 font-medium line-through decoration-gray-300">
                        <span class="mr-3 text-red-400 text-base">✖</span> নো ডেইলি ভিডিও লিমিট
                    </li>
                </ul>
                
                <button disabled class="w-full py-4 rounded-2xl border border-gray-200 text-gray-400 font-bold bg-gray-50 cursor-not-allowed text-sm">
                    ডিফল্ট প্ল্যান
                </button>
            </div>

            {{-- Plan 2: Premium --}}
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 flex flex-col hover:scale-[1.02] hover:shadow-[0_20px_50px_rgba(99,102,241,0.05)] transition duration-300">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">প্রিমিয়াম</h3>
                    <div class="text-4xl font-black text-indigo-600">৳ ২০০০</div>
                </div>
                
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-600 font-medium">
                        <span class="mr-3 text-indigo-500 font-bold text-base">✔</span> দিনে ২০টি ভিডিও
                    </li>
                    <li class="flex items-center text-sm text-gray-600 font-medium">
                        <span class="mr-3 text-indigo-500 font-bold text-base">✔</span> প্রতি ভিডিও: ১০০ কয়েন্স (৫ টাকা)
                    </li>
                    <li class="flex items-center text-sm text-gray-600 font-medium">
                        <span class="mr-3 text-indigo-500 font-bold text-base">✔</span> ডেইলি ইনকাম: ১০০ টাকা
                    </li>
                </ul>
                
                <button class="w-full py-4 rounded-2xl bg-indigo-600 text-white font-bold shadow-md hover:bg-indigo-700 hover:shadow-indigo-600/10 transition text-sm">
                    আপগ্রেড করুন
                </button>
            </div>

            {{-- Plan 3: Premium Pro (Featured / Best Value) --}}
            <div class="bg-white border-2 border-amber-400 rounded-[2.5rem] p-8 flex flex-col relative scale-100 md:scale-105 shadow-[0_25px_60px_rgba(251,191,36,0.12)] z-10">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-amber-400 text-amber-950 text-[11px] font-black px-6 py-1.5 rounded-full uppercase tracking-wider shadow-md">
                    Best Value
                </div>
                
                <div class="mb-8 mt-2 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">প্রিমিয়াম প্রো</h3>
                    <div class="text-5xl font-black text-amber-500">৳ ৫০০০</div>
                </div>
                
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-700 font-bold">
                        <span class="mr-3 text-amber-500 text-lg">★</span> দিনে ৫০টি ভিডিও
                    </li>
                    <li class="flex items-center text-sm text-gray-700 font-bold">
                        <span class="mr-3 text-amber-500 text-lg">★</span> প্রতি ভিডিও: ১০০ কয়েন্স (৫ টাকা)
                    </li>
                    <li class="flex items-center text-sm text-gray-700 font-bold">
                        <span class="mr-3 text-amber-500 text-lg">★</span> ডেইলি ইনকাম: ২৫০ টাকা
                    </li>
                    <li class="flex items-center text-sm text-gray-700 font-bold">
                        <span class="mr-3 text-amber-500 text-lg">★</span> ইনস্ট্যান্ট উইথড্র সুবিধা
                    </li>
                </ul>
                
                <button class="w-full py-4.5 rounded-2xl bg-amber-400 hover:bg-amber-500 text-amber-950 font-black uppercase text-xs tracking-wider shadow-md transition">
                    প্রিমিয়াম মেম্বার হোন
                </button>
            </div>

        </div>

        {{-- Bottom Support Banner --}}
        <div class="mt-20 bg-gray-50 border border-gray-100 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xs">
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-1">প্যাকেজ নিয়ে কোনো প্রশ্ন আছে?</h4>
                <p class="text-gray-500 text-sm font-medium">আমাদের সাপোর্ট টিম আপনার সহায়তার জন্য সবসময় প্রস্তুত।</p>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <a href="#" class="flex-1 md:flex-none text-center bg-white border border-gray-200 px-6 py-3 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition shadow-2xs">
                    সাপোর্ট সেন্টার
                </a>
                <a href="#" class="flex-1 md:flex-none text-center bg-indigo-600 px-6 py-3 rounded-xl text-sm font-bold text-white hover:bg-indigo-700 transition shadow-md">
                    লাইভ চ্যাট
                </a>
            </div>
        </div>
    </div>
@endsection