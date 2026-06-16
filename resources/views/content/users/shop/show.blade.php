@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-[#f8fafc] min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16 items-start">

            {{-- ইমেজ স্লাইডার সেকশন (Left Side) --}}
            <div class="space-y-4">
                {{-- মেইন স্লাইডার --}}
                <div class="swiper mainSwiper relative bg-white rounded-[2.5rem] border border-gray-200/80 overflow-hidden shadow-sm">
                    <div class="swiper-wrapper">
                        {{-- প্রধান থাম্বনেইল ইমেজ --}}
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                class="w-full h-[480px] object-cover main-preview" alt="{{ $product->name }}">
                        </div>

                        {{-- গ্যালারি এক্সট্রা ইমেজসমূহ --}}
                        @if ($product->gallery && $product->gallery->count() > 0)
                            @foreach ($product->gallery as $gallery_img)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $gallery_img->image_path) }}" class="w-full h-[480px] object-cover"
                                        alt="Gallery Image">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- নিচের থাম্বনেইল স্লাইডার (ন্যাভিগেশন বার) --}}
                <div class="swiper thumbSwiper relative px-8">
                    <div class="swiper-wrapper">
                        {{-- প্রধান থাম্বনেইল এর ছোট রূপ --}}
                        <div class="swiper-slide cursor-pointer opacity-40 transition-all duration-300">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                class="w-20 h-20 object-cover rounded-2xl border-2 border-transparent shadow-sm" />
                        </div>

                        {{-- গ্যালারি ইমেজের ছোট রূপসমূহ --}}
                        @if ($product->gallery && $product->gallery->count() > 0)
                            @foreach ($product->gallery as $gallery_img)
                                <div class="swiper-slide cursor-pointer opacity-40 transition-all duration-300">
                                    <img src="{{ asset('storage/' . $gallery_img->image_path) }}"
                                        class="w-20 h-20 object-cover rounded-2xl border-2 border-transparent shadow-sm" />
                                </div>
                            @endforeach
                        @endif
                    </div>

                    {{-- ন্যাভিগেশন অ্যারো বাটন (প্রফেশনাল ব্লু থিম) --}}
                    @if ($product->gallery && $product->gallery->count() > 0)
                        <div class="swiper-button-next text-blue-600 !scale-50 !-right-2"></div>
                        <div class="swiper-button-prev text-blue-600 !scale-50 !-left-2"></div>
                    @endif
                </div>
            </div>

            {{-- প্রোডাক্ট ডিটেইলস সেকশন (Right Side) --}}
            <div class="flex flex-col justify-start bg-white p-8 sm:p-10 rounded-[2.5rem] border border-gray-200/80 shadow-sm">
                
                {{-- প্রোডাক্টের নাম --}}
                <h1 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight uppercase">{{ $product->name }}</h1>

                {{-- প্রাইস এবং স্টক স্ট্যাটাস --}}
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <span class="text-4xl font-black text-amber-600 tracking-tight">৳{{ number_format($product->price) }}</span>
                    
                    @if ($product->stock > 0)
                        <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 uppercase tracking-wide">
                            স্টকে আছে
                        </span>
                    @else
                        <span class="bg-rose-50 text-rose-700 text-xs font-bold px-3 py-1 rounded-full border border-rose-200 uppercase tracking-wide">
                            আউট অফ স্টক
                        </span>
                    @endif
                </div>

                {{-- CKEditor ডেসক্রিপশন সেকশন (লাইটিং কালার ফিক্সড) --}}
                <div class="ck-content-area text-gray-600 mb-8 leading-relaxed dynamic-html-content">
                    {!! $product->description !!}
                </div>

                {{-- কার্ট ফর্ম --}}
                <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="thumbnail" value="{{ $product->thumbnail }}">

                    @if($product->stock > 0)
                        <button type="submit" 
                            class="w-full md:w-max bg-gray-900 hover:bg-gray-800 text-white font-bold px-12 py-4 rounded-2xl shadow-lg shadow-gray-900/10 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-3 text-base tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            কার্টে যুক্ত করুন
                        </button>
                    @else
                        <button type="button" disabled 
                            class="w-full md:w-max bg-gray-100 text-gray-400 font-bold px-12 py-4 rounded-2xl border border-gray-200 cursor-not-allowed flex items-center justify-center gap-3 text-base">
                            স্টক আউট
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Swiper.js Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiperThumb = new Swiper(".thumbSwiper", {
            spaceBetween: 12,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        var swiperMain = new Swiper(".mainSwiper", {
            spaceBetween: 10,
            thumbs: {
                swiper: swiperThumb,
            },
        });
    </script>

    <style>
        /* স্লাইডার থাম্বনেইল অ্যাক্টিভ বর্ডার কালার (মডার্ন ব্লু) */
        .thumbSwiper .swiper-slide-thumb-active {
            opacity: 1 !important;
        }

        .thumbSwiper .swiper-slide-thumb-active img {
            border-color: #2563eb !important;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px !important;
            font-weight: 900;
        }

        /* CKEditor এর এইচটিএমএল কন্টেন্ট লাইট মোড রিডিবিলিটি ফিক্স */
        .ck-content-area {
            font-size: 0.975rem;
            color: #4b5563 !important; /* Medium dark text */
        }
        .ck-content-area strong { 
            color: #111827 !important; /* Deep black for bold text */
            font-weight: 700;
        }
        .ck-content-area p { 
            margin-bottom: 1rem; 
        }
        .ck-content-area ul { 
            list-style-type: disc !important; 
            margin-left: 1.5rem; 
            margin-bottom: 1rem; 
        }
        .ck-content-area ol { 
            list-style-type: decimal !important; 
            margin-left: 1.5rem; 
            margin-bottom: 1rem; 
        }
        .ck-content-area li {
            margin-bottom: 0.25rem;
        }
        /* টেবিল কাস্টমাইজেশন (যদি ডেসক্রিপশনে টেবিল থাকে) */
        .ck-content-area table {
            width: 100% !important;
            margin: 1.5rem 0;
            border-collapse: collapse;
        }
        .ck-content-area table td, .ck-content-area table th {
            border: 1px solid #e5e7eb !important;
            padding: 0.75rem !important;
            color: #374151;
        }
        .ck-content-area table th {
            background-color: #f9fafb;
            font-weight: 700;
        }
    </style>
@endsection