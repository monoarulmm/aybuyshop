@extends('layouts.app')

{{-- কাস্টম সিএসএস অ্যানিমেশন স্টাইল যেন লেআউট না ভাঙে --}}
@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(12deg); }
        50% { transform: translateY(-12px) rotate(14deg); }
    }
    @keyframes float-slow {
        0%, 100% { transform: translateY(0px) rotate(-12deg); }
        50% { transform: translateY(10px) rotate(-10deg); }
    }
    .animate-float { animation: float 5s ease-in-out infinite; }
    .animate-float-slow { animation: float-slow 7s ease-in-out infinite; }
</style>
@endpush

@section('content')
    <div class="bg-[#fafafa] min-h-screen pb-16 w-full overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

            {{-- ১. হিরো সেকশন (ই-কমার্স + হালাল আর্নিং ফোকাসড) --}}
            <section class="relative w-full mb-16 overflow-hidden rounded-[2.5rem] bg-white border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                <div class="relative min-h-[420px] lg:min-h-[500px] flex flex-col lg:flex-row items-center px-6 lg:px-16 py-12 lg:py-0 overflow-hidden gap-8">
                    
                    {{-- ব্যাকগ্রাউন্ড গ্রাফিক্স --}}
                    <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-50/50 via-white to-transparent">
                        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-transparent"></div>
                    </div>

                    {{-- হিরো কন্টেন্ট --}}
                    <div class="relative z-10 w-full lg:max-w-2xl text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 mb-5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600">100%  & Secure Platform</span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-6xl font-black text-gray-900 leading-[1.15] mb-5 tracking-tight uppercase">
                            Premium Shop & <br>
                            <span class="text-blue-600 drop-shadow-[0_0_15px_rgba(37,99,235,0.1)]"> Earnings!</span>
                        </h1>

                        <p class="text-gray-500 text-xs sm:text-sm lg:text-base mb-8 leading-relaxed max-w-md font-medium">
                            <span class="font-black text-gray-900">AyBuyShop</span>-এ আপনার পছন্দের প্রিমিয়াম প্রোডাক্ট কিনুন সাশ্রয়ী মূল্যে। পাশাপাশি বড় বড় ব্র্যান্ডের মার্জিত ও শিক্ষণীয় বিজ্ঞাপন দেখে উপায়ে প্রতিদিন নিশ্চিত কয়েন রিওয়ার্ডস জিতে নিন!
                        </p>

                        {{-- প্রধান দুটি অ্যাকশন লিংক --}}
                        <div class="flex flex-wrap gap-4">
                            {{-- লিংক ১: ই-কমার্স শপ --}}
                            <a href="{{ route('shop.search') }}"
                                class="group relative px-7 py-3.5 bg-gray-950 text-white text-xs font-black rounded-2xl transition-all duration-300 hover:bg-blue-600 hover:scale-105 active:scale-95 shadow-[0_10px_25px_rgba(0,0,0,0.08)] flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>পণ্য কেনাকাটা করুন</span>
                            </a>

                            {{-- লিংক ২: ওয়াচ অ্যান্ড আর্ন --}}
                            <a href="{{ route('home') }}"
                                class="group relative px-7 py-3.5 bg-amber-500 text-gray-950 text-xs font-black rounded-2xl transition-all duration-300 hover:bg-amber-600 hover:scale-105 active:scale-95 shadow-[0_10px_25px_rgba(245,158,11,0.2)] flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>ভিডিও দেখে আয় করুন</span>
                            </a>
                        </div>
                    </div>

                    {{-- ফ্লোটিং ভিজ্যুয়াল ইলিমেন্টস --}}
                    <div class="hidden lg:block absolute right-[5%] top-1/2 -translate-y-1/2 z-10">
                        <div class="relative w-[350px] h-[350px]">
                            <div class="absolute inset-0 bg-blue-400/10 rounded-full blur-[100px] animate-pulse"></div>

                            {{-- শপিং কার্ড --}}
                            <div class="absolute top-4 right-12 w-40 h-48 bg-white/80 backdrop-blur-md rounded-[2.5rem] border border-gray-200/50 rotate-12 animate-float shadow-lg flex flex-col items-center justify-center p-6">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl mb-3 border border-blue-100 shadow-sm">🛍️</div>
                                <div class="h-2 w-16 bg-gray-200 rounded-full mb-2"></div>
                                <div class="h-2 w-10 bg-gray-100 rounded-full"></div>
                            </div>

                            {{-- ভিডিও আর্নিং কার্ড --}}
                            <div class="absolute bottom-8 left-4 w-36 h-36 bg-white/80 backdrop-blur-md rounded-[2rem] border border-gray-200/40 -rotate-12 animate-float-slow shadow-lg flex flex-col items-center justify-center">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-2 border border-emerald-100">🛡️</div>
                                <p class="text-[10px] font-black text-gray-900 uppercase tracking-wider"> Ads Only</p>
                                <span class="text-[9px] font-bold text-emerald-600 mt-0.5">Verified ✅</span>
                            </div>
                        </div>
                    </div>

                </div>
            </section>


            {{-- ২. কোর ফিচার সেকশন (আমরা কি কি দিচ্ছি) --}}
            <section id="how-it-works" class="mb-20">
                <div class="text-center max-w-xl mx-auto mb-12">
                    <span class="text-[10px] font-black tracking-[0.2em] uppercase text-blue-600">Our Services</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mt-1">আমাদের প্ল্যাটফর্মের মূল আকর্ষণ</h2>
                    <p class="text-xs sm:text-sm text-gray-400 mt-2">একই প্ল্যাটফর্মে নিরাপদ কেনাকাটা এবং বিশ্বস্ত আয়ের চমৎকার সমন্বয়।</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    {{-- ফিচার ১: ই-কমার্স কেনাবেচা --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200/60 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6 border border-blue-100">
                            🛒
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-gray-900 group-hover:text-blue-600 transition-colors">প্রিমিয়াম ই-কমার্স শপিং</h3>
                        <p class="text-gray-500 text-xs sm:text-sm mt-3 leading-relaxed">
                            AyBuyShop-এ ট্রেন্ডি গ্যাজেট, SMART লাইফস্টাইল পণ্য ও নিত্যপ্রয়োজনীয় ডিজিটাল সামগ্রী পাবেন খুচরা ও পাইকারি মূল্যে। সহজ অর্ডার প্রসেস এবং দ্রুততম ক্যাশ অন ডেলিভারি সুবিধাসহ যেকোনো সময় নিরাপদ শপিং করুন।
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('shop.search') }}" class="text-xs font-black uppercase text-blue-600 tracking-wider inline-flex items-center gap-1 hover:underline">
                                শপ প্রোডাক্টগুলো দেখুন &rarr;
                            </a>
                        </div>
                    </div>

                    {{-- ফিচার ২: ভিডিও থেকে হালাল আয় --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200/60 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-6 border border-emerald-100">
                            ✅
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-gray-900 group-hover:text-emerald-600 transition-colors">মার্জিত ভিডিও থেকে আয়</h3>
                        <p class="text-gray-500 text-xs sm:text-sm mt-3 leading-relaxed">
                            কোনো প্রকার অশ্লীলতা বা আপত্তিকর কন্টেন্ট ছাড়া, আমরা শুধুমাত্র দেশের নামী-দামী কর্পোরেট ব্র্যান্ডের বিজ্ঞাপন, শিক্ষণীয় কন্টেন্ট এবং ইনফরমেটিভ ভিডিও প্রমোট করি। সম্পূর্ণ শালীন উপায়ে ভিডিও দেখে প্রতিদিন পয়েন্ট ও রিওয়ার্ডস আর্ন করুন।
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('home') }}" class="text-xs font-black uppercase text-emerald-600 tracking-wider inline-flex items-center gap-1 hover:underline">
                                আজই ভিডিও দেখা শুরু করুন &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </section>


            {{-- ৩. সিকিউরিটি এবং পলিসি গ্যারান্টি বার --}}
            <section class="bg-emerald-600 text-white rounded-[2.5rem] p-8 sm:p-10 mb-16 relative overflow-hidden shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="max-w-md text-center md:text-left">
                        <h3 class="text-lg sm:text-xl font-black uppercase tracking-tight">আমাদের কঠোর কন্টেন্ট পলিসি</h3>
                        <p class="text-emerald-100 text-xs mt-1">আমরা শতভাগ ক্লিন, শিক্ষণীয় এবং ধর্মীয় মূল্যবোধসম্পন্ন বিজ্ঞাপনের নিশ্চয়তা দিই। পরিবারের সবাইকে নিয়ে নিশ্চিন্তে কাজ করার প্ল্যাটফর্ম এটি।</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest text-center">
                        🛡️ শতভাগ অশ্লীলতা মুক্ত
                    </div>
                </div>
            </section>


            {{-- ৪. ওয়ার্কф্লো সেকশন (কীভাবে কাজ করে) --}}
            <section class="bg-gray-900 text-white rounded-[2.5rem] p-8 sm:p-12 mb-16 relative overflow-hidden shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-transparent to-transparent"></div>
                
                <div class="relative z-10 text-center max-w-md mx-auto mb-12">
                    <h2 class="text-2xl font-black uppercase tracking-tight sm:text-3xl">মাত্র ৩টি ধাপে শুরু করুন</h2>
                    <p class="text-gray-400 text-xs mt-2">সহজ এবং নিরাপদ কাজের প্রক্রিয়া</p>
                </div>

                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                    {{-- ধাপ ১ --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-black text-sm text-blue-400 mb-4 border border-white/5">১</div>
                        <h4 class="text-base font-bold">ফ্রি একাউন্ট তৈরি</h4>
                        <p class="text-gray-400 text-xs mt-2 max-w-[200px]">১ মিনিটে ফ্রিতে আপনার নাম ও মোবাইল নাম্বার দিয়ে আপনার প্রোফাইল অ্যাক্টিভ করুন।</p>
                    </div>
                    {{-- ধাপ ২ --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-black text-sm text-blue-400 mb-4 border border-white/5">২</div>
                        <h4 class="text-base font-bold">ক্লিন ভিডিও ওয়াচিং</h4>
                        <p class="text-gray-400 text-xs mt-2 max-w-[200px]">ড্যাশবোর্ডে গিয়ে বড় ব্র্যান্ডগুলোর মার্জিত রিওয়ার্ডেড বিজ্ঞাপন দেখে সহজেই কয়েন জমান।</p>
                    </div>
                    {{-- ধাপ ৩ --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-black text-sm text-blue-400 mb-4 border border-white/5">৩</div>
                        <h4 class="text-base font-bold">শপিং বা ক্যাশআউট</h4>
                        <p class="text-gray-400 text-xs mt-2 max-w-[200px]">অর্জিত রিওয়ার্ডস কয়েন দিয়ে শপ থেকে শপিং করুন অথবা বিকাশ/নগদে ক্যাশআউট করুন।</p>
                    </div>
                </div>
            </section>


            {{-- ৫. কল টু অ্যাকশন বাটন ফটার স্টাইল --}}
            <section class="text-center py-6">
                <div class="bg-blue-600 rounded-3xl p-8 sm:p-12 text-white shadow-lg shadow-blue-600/10 max-w-4xl mx-auto">
                    <h2 class="text-xl sm:text-3xl font-black tracking-tight">আজই যোগ দিন AyBuyShop পরিবারে!</h2>
                    <p class="text-blue-100 text-xs sm:text-sm mt-2 max-w-md mx-auto">হাজারো ইউজার আমাদের সাথে কেনাকাটা ও প্রতিদিন বিশ্বস্ত পার্ট-টাইম আর্নিং করছেন ঘরে বসেই।</p>
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('normal.register') }}" class="bg-white text-gray-950 font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-2xl shadow-md hover:bg-gray-100 transition-all active:scale-95">
                            ফ্রি জয়েন করুন
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection