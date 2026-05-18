@php
    $siteSetting = $setting ?? \App\Models\SiteSetting::first();

    // ১. স্পনসর ইমেজ লিস্ট ডিকোড করা
    $sponsors = [];
    if ($siteSetting && $siteSetting->sponsor_banner) {
        $decoded = json_decode($siteSetting->sponsor_banner, true);
        $sponsors = is_array($decoded) ? $decoded : [];
    }

    // ২. ইউটিউব লিঙ্ক কনভার্ট লজিক
    $youtubeUrl = $siteSetting->youtube_link ?? '';
    $embedUrl = '';
    if ($youtubeUrl) {
        if (str_contains($youtubeUrl, 'watch?v=')) {
            $videoId = explode('v=', $youtubeUrl)[1];
            $videoId = explode('&', $videoId)[0];
            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
        } elseif (str_contains($youtubeUrl, 'youtu.be/')) {
            $videoId = explode('youtu.be/', $youtubeUrl)[1];
            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
        } else {
            // যদি সরাসরি এমবেড লিঙ্ক থাকে বা অন্য ফরম্যাট হয়
            $embedUrl = $youtubeUrl;
        }
    }
@endphp

{{-- Alpine.js x-data initialization --}}
<div x-data="{ open: false, imgFull: '' }" class="w-full relative space-y-6">

    {{-- ১. স্পনসর ব্যানার লুপ --}}
    @if (count($sponsors) > 0)
        @foreach ($sponsors as $banner)
            @php
                // পাথে 'settings/' থাকলে সেটাকে ব্যবহার করবে, নাহলে যোগ করে নেবে
                $cleanPath = str_starts_with($banner, 'settings/') ? $banner : 'settings/' . $banner;
                $imagePath = asset('storage/' . $cleanPath);
            @endphp

            <div
                class="bg-[#161925] border border-white/5 rounded-[2rem] p-6 text-center shadow-2xl transition-all duration-500 hover:border-white/10">
                <div class="mb-4">
                    <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest italic">Sponsored Ad</p>
                </div>

                {{-- ইমেজে ক্লিক করলে পপআপ ওপেন হবে --}}
                <div @click="open = true; imgFull = '{{ $imagePath }}'"
                    class="relative aspect-video bg-[#0a0c14] rounded-2xl overflow-hidden border border-white/10 group cursor-pointer shadow-inner">

                    <img src="{{ $imagePath }}" alt="Sponsor Banner"
                        class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100"
                        onerror="this.src='https://via.placeholder.com/800x450?text=Image+Not+Found'">

                    {{-- হোভার ইফেক্ট --}}
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                        <div
                            class="bg-yellow-500 text-black px-4 py-2 rounded-full font-bold text-[10px] uppercase tracking-tighter shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            View Full Image 🔍
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ২. ইউটিউব ভিডিও স্লট (শুধুমাত্র লিঙ্ক থাকলে দেখাবে) --}}
    @if ($embedUrl)
        <div class="bg-[#161925] border border-white/5 rounded-[2rem] p-6 text-center shadow-2xl">
            <div class="mb-4">
                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest italic">Video Ad Slot</p>
            </div>

            <div class="relative aspect-video bg-black rounded-2xl overflow-hidden border border-white/10 shadow-inner">
                <iframe class="absolute top-0 left-0 w-full h-full" src="{{ $embedUrl }}?rel=0&modestbranding=1"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>

            <div class="mt-4">
                <p class="text-[11px] text-gray-400 leading-relaxed">
                    আমাদের সাইটের সবকিছু এক ভিডিও তে দেখতে! <span class="text-yellow-500 font-bold">সাথে থাকুন</span>।
                </p>
            </div>
        </div>
    @endif

    {{-- ৩. ইমেজ ফুল ভিউ মডাল (Popup) --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/95 backdrop-blur-sm"
        @click="open = false" @keydown.escape.window="open = false" style="display: none;">

        {{-- ক্লোজ বাটন --}}
        <button
            class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full transition-all hover:rotate-90">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- বড় ইমেজ কন্টেইনার --}}
        <div class="max-w-5xl w-full" @click.stop>
            <img :src="imgFull"
                class="w-full h-auto max-h-[85vh] rounded-xl shadow-2xl border border-white/10 object-contain mx-auto transition-all">
        </div>
    </div>
</div>
