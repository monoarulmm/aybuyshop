@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 p-6 lg:p-10 text-gray-800 relative">

        {{-- ফুল স্ক্রিন আপলোডিং ইন্ডিকেটর (Light Blur Overlay) --}}
        <div id="loadingOverlay"
            class="hidden fixed inset-0 bg-white/90 z-[9999] flex flex-col items-center justify-center backdrop-blur-xl">
            <div class="relative w-24 h-24 mb-8">
                <div class="absolute inset-0 border-4 border-yellow-500/20 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-yellow-500 border-t-transparent rounded-full animate-spin">
                </div>
            </div>
            <h2 class="text-yellow-600 font-black italic uppercase tracking-[0.2em] text-xl animate-pulse">Updating Media
            </h2>
            <p class="text-gray-500 text-[10px] mt-4 font-black uppercase tracking-widest">বড় ফাইল হলে কিছুক্ষণ সময় লাগতে
                পারে, অপেক্ষা করুন</p>
        </div>

        {{-- হেডার ও ব্যাক বাটন --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black italic text-yellow-600 uppercase tracking-tighter">Edit <span
                        class="text-gray-900">Video</span></h1>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">Modify video information and details</p>
            </div>
            <div>
                <a href="{{ route('admin.videos.index') }}"
                    class="px-5 py-3 bg-white border border-gray-200 text-gray-700 font-black rounded-xl text-xs uppercase tracking-widest hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <div class="max-w-3xl mx-auto">
            {{-- ভিডিও এডিট করার ফর্ম --}}
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] border border-gray-200/80 shadow-xl shadow-gray-100">
                <h4 class="font-black text-lg mb-6 italic text-gray-700 uppercase tracking-tighter">ভিডিও তথ্য সংশোধন করুন</h4>

                <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data"
                    id="videoUpdateForm" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">ভিডিও টাইটেল</label>
                        <input type="text" name="title" required value="{{ old('title', $video->title) }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900 placeholder-gray-400"
                            placeholder="ভিডিওর শিরোনাম দিন">
                    </div>

                    <div x-data="{ videoType: '{{ old('video_type', $video->video_type) }}' }">
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">ভিডিও টাইপ</label>
                        <select name="video_type" x-model="videoType"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition cursor-pointer text-gray-900">
                            <option value="youtube">YouTube</option>
                            <option value="facebook">Facebook</option>
                            <option value="tiktok">TikTok</option>
                            <option value="local">Local Storage (Upload File)</option>
                            <option value="others">Others</option>
                        </select>

                        <div class="mt-5">
                            {{-- লোকাল ফাইল ইনপুট --}}
                            <div x-show="videoType === 'local'" x-transition>
                                <label class="text-[10px] font-black uppercase text-yellow-600 mb-2 block tracking-widest">
                                    ভিডিও ফাইল (সর্বোচ্চ ১০০ মেগাবাইট)
                                </label>
                                <input type="file" name="local_video" id="localVideoInput"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-gray-500 file:bg-yellow-500 file:border-none file:text-black file:mr-4 file:rounded-lg file:text-[10px] file:uppercase file:font-black file:px-4 file:py-1 cursor-pointer">
                                
                                @if($video->video_type === 'local' && $video->local_video)
                                    <div class="mt-3 p-3 bg-blue-50/50 border border-blue-100 rounded-xl flex items-center gap-2">
                                        <span class="text-xs text-blue-600 font-bold">📁 বর্তমান ফাইল:</span>
                                        <a href="{{ asset('storage/' . $video->local_video) }}" target="_blank" class="text-xs text-gray-600 underline hover:text-blue-700 truncate max-w-xs">
                                            {{ basename($video->local_video) }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- ভিডিও লিঙ্ক (URL) --}}
                            <div x-show="videoType !== 'local'" x-transition>
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">ভিডিও লিঙ্ক (URL)</label>
                                <input type="text" name="video_url" value="{{ old('video_url', $video->video_url) }}"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900 placeholder-gray-400"
                                    placeholder="https://example.com/video">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Earning (৳)</label>
                            <input type="number" step="0.01" name="earning_per_view"
                                value="{{ old('earning_per_view', $video->earning_per_view) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Duration (Sec)</label>
                            <input type="number" name="duration" value="{{ old('duration', $video->duration) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900 placeholder-gray-400"
                                placeholder="ঐচ্ছিক">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('admin.videos.index') }}" 
                            class="w-1/3 py-4 bg-gray-100 text-gray-700 font-black rounded-2xl uppercase tracking-[0.2em] text-xs hover:bg-gray-200 transition text-center">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                            class="w-2/3 py-4 bg-yellow-500 text-black font-black rounded-2xl uppercase tracking-[0.2em] text-xs hover:bg-yellow-600 transition shadow-lg shadow-yellow-500/10 active:scale-95">
                            <span id="btnText">Save Changes Now</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // SweetAlert কনফিগারেশন (Light Theme)
        const LightToast = Swal.mixin({
            background: '#ffffff',
            color: '#1f2937',
            confirmButtonColor: '#ca8a04',
            cancelButtonColor: '#ef4444',
            customClass: {
                popup: 'rounded-[2rem] border border-gray-200 shadow-2xl',
                title: 'italic font-black text-yellow-600',
            }
        });

        // ভিডিও আপডেট সাইজ চেক ও ইন্ডিকেটর
        const form = document.getElementById('videoUpdateForm');
        const fileInput = document.getElementById('localVideoInput');
        const overlay = document.getElementById('loadingOverlay');

        form.addEventListener('submit', function(e) {
            const videoType = form.video_type.value;

            if (videoType === 'local' && fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size / 1024 / 1024; // MB
                if (fileSize > 100) {
                    e.preventDefault();
                    LightToast.fire({
                        icon: 'error',
                        title: 'ফাইলটি অনেক বড়!',
                        text: `আপনার ভিডিও ফাইলটি ${fileSize.toFixed(2)} MB। দয়া করে ১০০ MB এর নিচে ফাইল আপলোড করুন।`,
                    });
                    return;
                }
            }

            // সাকসেস হলে লোডিং দেখাবে
            overlay.classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('btnText').innerText = 'Updating...';
        });

        // এরর মেসেজ অ্যালার্ট (যদি ব্যাকএন্ড ভ্যালিডেশন ফেইল করে)
        @if (session('error') || $errors->any())
            LightToast.fire({
                icon: 'error',
                title: 'ওহ না!',
                text: "{{ session('error') ?? $errors->first() }}",
            });
        @endif
    </script>
@endsection