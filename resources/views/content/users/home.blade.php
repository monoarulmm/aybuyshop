@extends('layouts.app')

@section('content')
    @include('partials.promo-modal')
    @include('partials.hero')

    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

    <div class="space-y-10 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- লুপ শুরু: এখানে @if ($index < 9) ফেলে দেওয়া হয়েছে --}}
            @forelse ($videos as $video)
                <div id="video-card-{{ $video->id }}"
                    class="group bg-[#161925] rounded-[2rem] overflow-hidden border border-gray-800 relative shadow-xl">

                    {{-- Header --}}
                    <div class="p-5 bg-gray-900/20 border-b border-gray-800 flex justify-between items-center">
                        <h4 class="text-white font-bold text-sm">{{ $video->title }}</h4>
                        <p class="text-yellow-500 font-black italic">৳{{ $video->earning_per_view }}</p>
                    </div>

                    <div class="aspect-video w-full relative bg-black flex items-center justify-center overflow-hidden">

                        {{-- 1. Facebook Player --}}
                        @if ($video->video_type == 'facebook')
                            <div class="fb-wrapper w-full h-full relative" id="fb-container-{{ $video->id }}">
                                <div id="fb-player-{{ $video->id }}" class="fb-video" data-href="{{ $video->video_url }}"
                                    data-width="auto" data-allowfullscreen="true" data-autoplay="false"
                                    data-show-captions="false">
                                </div>
                                <div id="fb-overlay-{{ $video->id }}"
                                    class="hidden absolute bottom-0 left-0 w-full h-14 z-20 bg-transparent cursor-not-allowed">
                                </div>
                                <div id="fb-starter-{{ $video->id }}"
                                    class="absolute inset-0 flex flex-col items-center justify-center bg-[#0d0f1a] z-30 p-6 text-center">
                                    <button onclick="playFBVideo('{{ $video->id }}')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-3 shadow-lg transition-transform hover:scale-105 active:scale-95">
                                        <span class="text-xl">▶</span>
                                    </button>
                                    <p class="text-[10px] text-gray-500 mt-4 uppercase font-black tracking-widest italic">
                                        <strong>সাউন্ডসহ ভিডিওটি সম্পূর্ণ দেখতে হবে !!</strong>
                                    </p>
                                </div>
                                <div id="fb-timer-status-{{ $video->id }}"
                                    class="hidden absolute top-4 left-4 z-40 bg-blue-600/90 backdrop-blur shadow-xl text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase">
                                </div>
                            </div>

                            {{-- 2. YouTube Player --}}
                        @elseif($video->video_type == 'youtube')
                            @php
                                preg_match(
                                    '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                                    $video->video_url,
                                    $match,
                                );
                                $yt_id = $match[1] ?? 'error';
                            @endphp
                            <div class="yt-wrapper w-full h-full relative" data-video-id="{{ $yt_id }}"
                                data-task-id="{{ $video->id }}">
                                <div id="player-{{ $video->id }}" class="w-full h-full pointer-events-none"></div>
                                <div onclick="toggleYT('{{ $video->id }}')"
                                    class="absolute inset-0 z-20 bg-transparent cursor-pointer"></div>
                                <div id="yt-ui-{{ $video->id }}"
                                    class="absolute inset-0 z-30 bg-black/60 flex items-center justify-center pointer-events-none transition-opacity duration-300">
                                    <div
                                        class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center text-white text-xl">
                                        ▶</div>
                                </div>
                            </div>

                            {{-- 3. Local Player --}}
                        @elseif($video->video_type == 'local')
                            <div class="relative w-full h-full bg-black cursor-pointer"
                                onclick="toggleLocal('{{ $video->id }}')">
                                <video id="local-{{ $video->id }}"
                                    class="w-full h-full object-contain pointer-events-none">
                                    <source src="{{ asset('storage/' . $video->local_video) }}" type="video/mp4">
                                </video>
                                <div id="local-ui-{{ $video->id }}"
                                    class="absolute inset-0 z-30 bg-black/50 flex items-center justify-center transition-opacity duration-300">
                                    <div
                                        class="w-14 h-14 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl">
                                        ▶</div>
                                </div>
                            </div>
                        @endif

                        {{-- Finish Message Overlay --}}
                        <div id="msg-overlay-{{ $video->id }}"
                            class="hidden absolute inset-0 bg-[#080a12]/98 z-[100] flex flex-col items-center justify-center text-center p-8 backdrop-blur-xl">
                            <div id="status-icon-{{ $video->id }}" class="text-4xl mb-4"></div>
                            <h3 id="status-title-{{ $video->id }}"
                                class="text-white font-bold text-lg uppercase italic tracking-tighter"></h3>
                            <p id="status-text-{{ $video->id }}" class="text-gray-400 text-xs mt-1 mb-6 max-w-xs"></p>
                            <button onclick="location.reload()"
                                class="bg-yellow-500 hover:bg-yellow-400 text-black px-10 py-2.5 rounded-xl font-black uppercase text-[11px] transition-all shadow-lg">Collect
                                & Next</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-500 font-bold italic">No tasks available right now.</p>
                </div>
            @endforelse
        </div>

        {{-- Load More Section: বাটনটি এখন ডায়নামিক এবং ক্লিকেবল --}}
        @if ($videos->hasMorePages())
            <div class="flex justify-center pt-10">
                <a href="{{ $videos->nextPageUrl() }}"
                    class="bg-gray-800 hover:bg-gray-700 text-white px-10 py-3 rounded-2xl font-bold border border-gray-700 transition-all shadow-lg active:scale-95 flex items-center gap-2">
                    আরো ভিডিও দেখুন
                    <span class="text-yellow-500">➔</span>
                </a>
            </div>
        @endif

        @guest
            <div class="bg-blue-600/10 border border-blue-500/20 p-6 rounded-[2rem] text-center">
                <h3 class="text-white font-bold mb-2">আরো ভিডিও দেখে আয় করতে চান?</h3>
                <p class="text-gray-400 text-sm mb-4">রেজিস্ট্রেশন করে আপনার ইনকাম শুরু করুন আজই!</p>
                <a href="{{ route('register') }}"
                    class="inline-block bg-blue-600 text-white px-8 py-2.5 rounded-xl font-bold uppercase text-xs">রেজিস্ট্রেশন
                    করুন</a>
            </div>
        @endguest
    </div>
@endsection
