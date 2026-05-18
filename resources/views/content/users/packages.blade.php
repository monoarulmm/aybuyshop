@extends('layouts.app') {{-- আপনার মেইন লেআউট ফাইলটির নাম এখানে দিন --}}

@section('content')
    <div class="py-10 px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic tracking-tighter">
                Choose Your <span class="text-yellow-500">Success Plan</span>
            </h2>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto text-sm md:text-base">
                আপনার পছন্দের প্যাকেজটি বেছে নিন এবং আজই আয় করা শুরু করুন। প্যাকেজ যত বড়, আয়ের সুযোগ তত বেশি!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">

            <div
                class="bg-[#161925] border border-white/5 rounded-[2.5rem] p-8 flex flex-col hover:border-white/20 transition duration-300">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-white mb-2">নরমাল ইউজার</h3>
                    <div class="text-4xl font-black text-yellow-500 italic">৳ ০ <span
                            class="text-sm text-gray-500 not-italic">/লাইফটাইম</span></div>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-green-500">✔</span> আনলিমিটেড ভিডিও (নো কয়েন)
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-green-500">✔</span> প্রতি টাস্ক ভিডিও: ৫ কয়েন্স
                    </li>
                    <li class="flex items-center text-sm text-gray-500">
                        <span class="mr-3">✖</span> নো ডেইলি ভিডিও লিমিট
                    </li>
                </ul>
                <button
                    class="w-full py-4 rounded-2xl border border-white/10 text-white font-bold hover:bg-white/5 transition">ডিফল্ট
                    প্ল্যান</button>
            </div>

            <div
                class="bg-[#161925] border border-indigo-500/30 rounded-[2.5rem] p-8 flex flex-col relative overflow-hidden group hover:scale-[1.02] transition duration-300">
                <div
                    class="absolute top-0 right-0 bg-indigo-600 text-white text-[10px] font-black px-4 py-1 rounded-bl-xl uppercase tracking-widest">
                    Popular</div>
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-white mb-2">বেসিক প্রোফাইল</h3>
                    <div class="text-4xl font-black text-white italic">৳ ১০০০</div>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-indigo-500">✔</span> দিনে ১০টি ভিডিও
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-indigo-500">✔</span> প্রতি ভিডিও: ১০০ কয়েন্স (৫ টাকা)
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-indigo-500">✔</span> ডেইলি ইনকাম: ৫০ টাকা
                    </li>
                </ul>
                <button
                    class="w-full py-4 rounded-2xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/20 hover:bg-indigo-500 transition">আপগ্রেড
                    করুন</button>
            </div>

            <div
                class="bg-gradient-to-b from-[#1e1b4b] to-[#161925] border border-purple-500/30 rounded-[2.5rem] p-8 flex flex-col hover:scale-[1.02] transition duration-300">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-white mb-2">প্রিমিয়াম</h3>
                    <div class="text-4xl font-black text-white italic">৳ ২০০০</div>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-purple-500">✔</span> দিনে ২০টি ভিডিও
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-purple-500">✔</span> প্রতি ভিডিও: ১০০ কয়েন্স (৫ টাকা)
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-purple-500">✔</span> ডেইলি ইনকাম: ১০০ টাকা
                    </li>
                </ul>
                <button
                    class="w-full py-4 rounded-2xl bg-purple-600 text-white font-bold shadow-lg shadow-purple-600/20 hover:bg-purple-500 transition">আপগ্রেড
                    করুন</button>
            </div>

            <div
                class="bg-[#161925] border-2 border-yellow-500/50 rounded-[2.5rem] p-8 flex flex-col relative scale-105 shadow-[0_0_40px_rgba(234,179,8,0.1)]">
                <div
                    class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-500 text-black text-[11px] font-black px-6 py-1.5 rounded-full uppercase tracking-tighter shadow-xl">
                    Best Value</div>
                <div class="mb-8 mt-2 text-center">
                    <h3 class="text-xl font-bold text-white mb-2">প্রিমিয়াম প্রো</h3>
                    <div class="text-5xl font-black text-yellow-500 italic">৳ ৫০০০</div>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-yellow-500 font-bold">★</span> দিনে ৫০টি ভিডিও
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-yellow-500 font-bold">★</span> প্রতি ভিডিও: ১০০ কয়েন্স (৫ টাকা)
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-yellow-500 font-bold">★</span> ডেইলি ইনকাম: ২৫০ টাকা
                    </li>
                    <li class="flex items-center text-sm text-gray-300">
                        <span class="mr-3 text-yellow-500 font-bold">★</span> ইনস্ট্যান্ট উইথড্র সুবিধা
                    </li>
                </ul>
                <button
                    class="w-full py-5 rounded-2xl bg-yellow-500 text-black font-black uppercase text-xs hover:shadow-[0_0_20px_rgba(234,179,8,0.3)] transition">প্রিমিয়াম
                    মেম্বার হোন</button>
            </div>

        </div>

        <div
            class="mt-20 bg-white/5 border border-white/5 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h4 class="text-lg font-bold text-white mb-1">প্যাকেজ নিয়ে কোনো প্রশ্ন আছে?</h4>
                <p class="text-gray-400 text-sm">আমাদের সাপোর্ট টিম আপনার সহায়তার জন্য সবসময় প্রস্তুত।</p>
            </div>
            <div class="flex gap-4">
                <a href="#"
                    class="bg-white/10 px-6 py-3 rounded-xl text-sm font-bold hover:bg-white/20 transition">সাপোর্ট
                    সেন্টার</a>
                <a href="#"
                    class="bg-indigo-600 px-6 py-3 rounded-xl text-sm font-bold hover:bg-indigo-500 transition">লাইভ
                    চ্যাট</a>
            </div>
        </div>
    </div>
@endsection
