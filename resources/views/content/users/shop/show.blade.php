@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">

            {{-- ইমেজ স্লাইডার সেকশন (Left Side) --}}
            <div>
                <div
                    class="swiper mainSwiper mb-4 relative bg-[#111421] rounded-[2rem] border border-gray-800 overflow-hidden shadow-2xl">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                class="w-full h-[450px] object-cover main-preview" alt="{{ $product->name }}">
                        </div>

                        @if ($product->images && count(json_decode($product->images)) > 0)
                            @foreach (json_decode($product->images) as $gallery_img)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $gallery_img) }}" class="w-full h-[450px] object-cover"
                                        alt="Gallery Image">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="swiper thumbSwiper relative px-10">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide cursor-pointer opacity-50 transition duration-300">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                class="w-20 h-20 object-cover rounded-2xl border-2 border-transparent" />
                        </div>

                        @if ($product->images && count(json_decode($product->images)) > 0)
                            @foreach (json_decode($product->images) as $gallery_img)
                                <div class="swiper-slide cursor-pointer opacity-50 transition duration-300">
                                    <img src="{{ asset('storage/' . $gallery_img) }}"
                                        class="w-20 h-20 object-cover rounded-2xl border-2 border-transparent" />
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if ($product->images && count(json_decode($product->images)) > 0)
                        <div class="swiper-button-next text-yellow-500 !scale-50 !-right-2"></div>
                        <div class="swiper-button-prev text-yellow-500 !scale-50 !-left-2"></div>
                    @endif
                </div>
            </div>

            {{-- প্রোডাক্ট ডিটেইলস (Right Side) --}}
            <div class="flex flex-col justify-start">
                <h1 class="text-3xl font-black text-white mb-4 uppercase italic tracking-wider">{{ $product->name }}</h1>

                <div class="flex items-center gap-4 mb-8">
                    <span class="text-4xl font-black text-yellow-500">৳{{ number_format($product->price, 2) }}</span>
                    @if ($product->stock > 0)
                        <span
                            class="bg-green-500/10 text-green-500 text-xs font-bold px-3 py-1 rounded-full border border-green-500/20 uppercase">স্টকে
                            আছে</span>
                    @else
                        <span
                            class="bg-red-500/10 text-red-500 text-xs font-bold px-3 py-1 rounded-full border border-red-500/20 uppercase">আউট
                            অফ স্টক</span>
                    @endif
                </div>

                <div class="ck-content-area text-gray-400 mb-8">
                    {!! $product->description !!}
                </div>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="thumbnail" value="{{ $product->thumbnail }}">

                    <button type="submit"
                        class="w-full md:w-max bg-blue-600 hover:bg-blue-700 text-white font-black px-12 py-4 rounded-2xl shadow-xl transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        কার্টে যুক্ত করুন
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Swiper.js Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiperThumb = new Swiper(".thumbSwiper", {
            spaceBetween: 10,
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
        .thumbSwiper .swiper-slide-thumb-active {
            opacity: 1 !important;
        }

        .thumbSwiper .swiper-slide-thumb-active img {
            border-color: #eab308;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px !important;
            font-weight: bold;
        }

        .ck-content-area {
            font-size: 1rem;
            color: #9ca3af;
            line-height: 1.6;
        }

        .ck-content-area strong {
            color: white;
        }
    </style>
@endsection
