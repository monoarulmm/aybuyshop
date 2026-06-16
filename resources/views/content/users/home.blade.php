@extends('layouts.app')

@section('content')


    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

    <div class="space-y-10 pb-20">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            @forelse ($videos as $video)
                <div id="video-card-{{ $video->id }}"
                    class="group bg-white rounded-3xl overflow-hidden border border-gray-200 relative shadow-md hover:shadow-lg hover:border-indigo-200 transition-all duration-300">

                    {{-- Header --}}
                    <div class="p-5 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="text-gray-800 font-bold text-sm">{{ $video->title }}</h4>
                        <span class="bg-amber-50 border border-amber-200 text-amber-600 font-black text-sm px-3 py-1 rounded-full">
                            ৳{{ $video->earning_per_view }}
                        </span>
                    </div>

                    <div class="aspect-video w-full relative bg-gray-100 flex items-center justify-center overflow-hidden">

                        {{-- 1. Facebook Player --}}
                        @if ($video->video_type == 'facebook')
                            <div class="fb-wrapper w-full h-full relative" id="fb-container-{{ $video->id }}">
                                <div id="fb-player-{{ $video->id }}" class="fb-video"
                                    data-href="{{ $video->video_url }}"
                                    data-width="auto" data-allowfullscreen="true"
                                    data-autoplay="false" data-show-captions="false">
                                </div>
                                <div id="fb-overlay-{{ $video->id }}"
                                    class="hidden absolute bottom-0 left-0 w-full h-14 z-20 bg-transparent cursor-not-allowed">
                                </div>
                                <div id="fb-starter-{{ $video->id }}"
                                    class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 z-30 p-6 text-center"
                                    data-duration="{{ $video->duration ?? 30 }}">
                                    <button onclick="playFBVideo('{{ $video->id }}')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-3 shadow-lg transition-transform hover:scale-105 active:scale-95">
                                        <span class="text-xl">▶</span>
                                        ভিডিও চালু করুন
                                    </button>
                                    <p class="text-xs text-gray-400 mt-4 uppercase font-bold tracking-widest">
                                        সাউন্ডসহ ভিডিওটি সম্পূর্ণ দেখতে হবে
                                    </p>
                                </div>
                                <div id="fb-timer-status-{{ $video->id }}"
                                    class="hidden absolute top-4 left-4 z-40 bg-indigo-600 text-white text-xs px-4 py-1.5 rounded-full font-bold">
                                </div>
                            </div>

                        {{-- 2. YouTube Player --}}
                        @elseif($video->video_type == 'youtube')
                            @php
                                preg_match(
                                    '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                                    $video->video_url, $match
                                );
                                $yt_id = $match[1] ?? 'error';
                            @endphp
                            <div class="yt-wrapper w-full h-full relative"
                                data-video-id="{{ $yt_id }}"
                                data-task-id="{{ $video->id }}">
                                <div id="player-{{ $video->id }}" class="w-full h-full pointer-events-none"></div>
                                <div onclick="toggleYT('{{ $video->id }}')"
                                    class="absolute inset-0 z-20 bg-transparent cursor-pointer"></div>
                                <div id="yt-ui-{{ $video->id }}"
                                    class="absolute inset-0 z-30 bg-black/50 flex items-center justify-center pointer-events-none transition-opacity duration-300">
                                    <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center text-white text-xl shadow-lg">
                                        ▶
                                    </div>
                                </div>
                            </div>

                        {{-- 3. Local Player --}}
                        @elseif($video->video_type == 'local')
                            <div class="relative w-full h-full bg-gray-900 cursor-pointer"
                                onclick="toggleLocal('{{ $video->id }}')">
                                <video id="local-{{ $video->id }}" class="w-full h-full object-contain pointer-events-none">
                                    <source src="{{ asset('storage/' . $video->local_video) }}" type="video/mp4">
                                </video>
                                <div id="local-ui-{{ $video->id }}"
                                    class="absolute inset-0 z-30 bg-black/50 flex items-center justify-center transition-opacity duration-300">
                                    <div class="w-14 h-14 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xl shadow-lg">
                                        ▶
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Finish Message Overlay --}}
                        <div id="msg-overlay-{{ $video->id }}"
                            class="hidden absolute inset-0 bg-white/97 z-[100] flex flex-col items-center justify-center text-center p-8 backdrop-blur-sm">
                            <div id="status-icon-{{ $video->id }}" class="text-4xl mb-4"></div>
                            <h3 id="status-title-{{ $video->id }}"
                                class="text-gray-800 font-black text-lg uppercase tracking-tight"></h3>
                            <p id="status-text-{{ $video->id }}"
                                class="text-gray-500 text-xs mt-1 mb-6 max-w-xs leading-relaxed"></p>
                            <button onclick="location.reload()"
                                class="bg-amber-500 hover:bg-amber-400 text-black px-10 py-2.5 rounded-xl font-black uppercase text-xs transition-all shadow-md active:scale-95">
                                Collect & Next
                            </button>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full text-center py-20">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-video text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-400 font-semibold">No tasks available right now.</p>
                </div>
            @endforelse
        </div>

        {{-- Load More --}}
        @if ($videos->hasMorePages())
            <div class="flex justify-center pt-10">
                <a href="{{ $videos->nextPageUrl() }}"
                    class="bg-white hover:bg-indigo-50 text-indigo-600 border border-indigo-200 px-10 py-3 rounded-2xl font-bold shadow-sm hover:shadow-md transition-all active:scale-95 flex items-center gap-2">
                    আরো ভিডিও দেখুন
                    <i class="fas fa-arrow-right text-amber-500"></i>
                </a>
            </div>
        @endif

        {{-- Guest CTA --}}
        @guest
            <div class="bg-indigo-50 border border-indigo-100 p-8 rounded-3xl text-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-indigo-600 text-lg"></i>
                </div>
                <h3 class="text-gray-800 font-bold text-lg mb-2">আরো ভিডিও দেখে আয় করতে চান?</h3>
                <p class="text-gray-500 text-sm mb-6">রেজিস্ট্রেশন করে আপনার ইনকাম শুরু করুন আজই!</p>
                <a href="{{ route('register') }}"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold uppercase text-xs shadow-md transition-all">
                    রেজিস্ট্রেশন করুন
                </a>
            </div>
        @endguest

    </div>
@endsection