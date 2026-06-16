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
            <h2 class="text-yellow-600 font-black italic uppercase tracking-[0.2em] text-xl animate-pulse">Uploading Media
            </h2>
            <p class="text-gray-500 text-[10px] mt-4 font-black uppercase tracking-widest">বড় ফাইল হলে কিছুক্ষণ সময় লাগতে
                পারে, অপেক্ষা করুন</p>
        </div>

        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black italic text-yellow-600 uppercase tracking-tighter">Video <span
                        class="text-gray-900">Management</span></h1>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">Add and manage earning videos</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- ১. ভিডিও অ্যাড করার ফর্ম --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-200/80 h-fit shadow-xl shadow-gray-100">
                <h4 class="font-black text-lg mb-6 italic text-gray-700 uppercase tracking-tighter">নতুন ভিডিও যুক্ত করুন
                </h4>

                <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data"
                    id="videoUploadForm" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">ভিডিও
                            টাইটেল</label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900 placeholder-gray-400"
                            placeholder="ভিডিওর শিরোনাম দিন">
                    </div>

                    <div x-data="{ videoType: '{{ old('video_type', 'youtube') }}' }">
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">ভিডিও
                            টাইপ</label>
                        <select name="video_type" x-model="videoType"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition cursor-pointer text-gray-900">
                            <option value="youtube">YouTube</option>
                            <option value="facebook">Facebook</option>
                            <option value="tiktok">TikTok</option>
                            <option value="local">Local Storage (Upload File)</option>
                            <option value="others">Others</option>
                        </select>

                        <div class="mt-5">
                            <div x-show="videoType === 'local'" x-transition>
                                <label
                                    class="text-[10px] font-black uppercase text-yellow-600 mb-2 block tracking-widest">ভিডিও
                                    ফাইল (সর্বোচ্চ ১০০ মেগাবাইট)</label>
                                <input type="file" name="local_video" id="localVideoInput"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-gray-500 file:bg-yellow-500 file:border-none file:text-black file:mr-4 file:rounded-lg file:text-[10px] file:uppercase file:font-black file:px-4 file:py-1 cursor-pointer">
                            </div>

                            <div x-show="videoType !== 'local'" x-transition>
                                <label
                                    class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">ভিডিও
                                    লিঙ্ক (URL)</label>
                                <input type="text" name="video_url" value="{{ old('video_url') }}"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900 placeholder-gray-400"
                                    placeholder="https://example.com/video">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Earning
                                (৳)</label>
                            <input type="number" step="0.01" name="earning_per_view"
                                value="{{ old('earning_per_view', '5.00') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900">
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Duration
                                (Sec)</label>
                            <input type="number" name="duration" value="{{ old('duration') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:border-yellow-500 focus:bg-white outline-none transition text-gray-900 placeholder-gray-400"
                                placeholder="ঐচ্ছিক">
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full py-4 bg-yellow-500 text-black font-black rounded-2xl uppercase tracking-[0.2em] text-xs hover:bg-yellow-600 transition shadow-lg shadow-yellow-500/10 active:scale-95">
                        <span id="btnText">Publish Video Now</span>
                    </button>
                </form>
            </div>

            {{-- ২. ভিডিও লিস্ট সেকশন --}}
            <div class="lg:col-span-2 bg-white rounded-[3rem] p-8 border border-gray-200/80 shadow-xl shadow-gray-100 overflow-hidden">
                <h4 class="font-black text-lg mb-8 italic text-gray-700 uppercase tracking-tighter">Active Video List</h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <th class="pb-4 pl-4">Title & Duration</th>
                                <th class="pb-4">Platform</th>
                                <th class="pb-4">Reward</th>
                                <th class="pb-4 text-right pr-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($videos as $video)
                                <tr class="bg-gray-50 group hover:bg-gray-100/70 transition-all duration-300">
                                    <td class="p-5 rounded-l-2xl border-l border-y border-gray-200/60">
                                        <p class="text-sm font-bold text-gray-800">{{ $video->title }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">
                                                {{ $video->duration ? $video->duration . ' Seconds' : 'No Duration' }}
                                            </span>
                                            @if ($video->video_type === 'local')
                                                <span
                                                    class="text-[8px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg border border-blue-100 font-black uppercase">📁
                                                    Local</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-5 border-y border-gray-200/60">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-3 py-1 bg-white border border-gray-200 rounded-xl text-[9px] font-black uppercase text-yellow-600">
                                                {{ $video->video_type }}
                                            </span>

                                            {{-- কপি বাটন --}}
                                            @if($video->video_url)
                                                <button onclick="copyToClipboard('{{ $video->video_url }}', this)"
                                                    class="p-2 bg-white hover:bg-yellow-50 text-gray-400 hover:text-yellow-600 rounded-lg transition-all border border-gray-200 group/copy"
                                                    title="Copy Video Link">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-5 border-y border-gray-200/60">
                                        <span class="text-emerald-600 font-black text-sm italic">৳
                                            {{ number_format($video->earning_per_view, 2) }}</span>
                                    </td>
                                    <td class="p-5 rounded-r-2xl border-r border-y border-gray-200/60 text-right pr-4">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- এডিট বাটন (নতুন যুক্ত করা হয়েছে) --}}
                                            <a href="{{route('admin.videos.edit', $video->id) }}"
                                                class="w-9 h-9 inline-flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100"
                                                title="Edit Video">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            {{-- ডিলিট বাটন --}}
                                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST"
                                                class="delete-form inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="delete-btn w-9 h-9 inline-flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm border border-red-100"
                                                    title="Delete Video">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-20 opacity-40 text-xs font-black uppercase text-gray-500">
                                        কোনো ভিডিও নেই</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

        // ১. ভিডিও আপলোড ও সাইজ চেক
        const form = document.getElementById('videoUploadForm');
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
            document.getElementById('btnText').innerText = 'Processing...';
        });

        // ২. ডিলিট কনফার্মেশন (SweetAlert)
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                LightToast.fire({
                    title: 'আপনি কি নিশ্চিত?',
                    text: "এই ভিডিওটি ডিলিট করলে আর ফিরে পাওয়া যাবে না!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'হ্যাঁ, ডিলিট করুন!',
                    cancelButtonText: 'না, বাতিল'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // ৩. সাকসেস/এরর মেসেজ (SweetAlert)
        @if (session('success'))
            LightToast.fire({
                icon: 'success',
                title: 'সাফল্য!',
                text: "{{ session('success') }}",
                timer: 3000
            });
        @endif

        @if (session('error') || $errors->any())
            LightToast.fire({
                icon: 'error',
                title: 'ওহ না!',
                text: "{{ session('error') ?? $errors->first() }}",
            });
        @endif
    </script>

    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = `
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                btn.classList.add('border-emerald-500/50', 'bg-emerald-5');

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('border-emerald-500/50', 'bg-emerald-5');
                }, 2000);
            }).catch(err => {
                console.error('Copy failed!', err);
            });
        }
    </script>
@endsection