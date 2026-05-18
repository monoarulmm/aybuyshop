@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#080a12] p-6 lg:p-10 text-white relative">

        {{-- ফুল স্ক্রিন আপলোডিং ইন্ডিকেটর --}}
        <div id="loadingOverlay"
            class="hidden fixed inset-0 bg-[#080a12]/95 z-[9999] flex flex-col items-center justify-center backdrop-blur-xl">
            <div class="relative w-24 h-24 mb-8">
                <div class="absolute inset-0 border-4 border-yellow-500/10 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-yellow-500 border-t-transparent rounded-full animate-spin">
                </div>
            </div>
            <h2 class="text-yellow-500 font-black italic uppercase tracking-[0.2em] text-xl animate-pulse">Uploading Media
            </h2>
            <p class="text-gray-500 text-[10px] mt-4 font-black uppercase tracking-widest">বড় ফাইল হলে কিছুক্ষণ সময় লাগতে
                পারে, অপেক্ষা করুন</p>
        </div>

        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black italic text-yellow-500 uppercase tracking-tighter">Video <span
                        class="text-white">Management</span></h1>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">Add and manage earning videos</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- ১. ভিডিও অ্যাড করার ফর্ম --}}
            <div class="bg-[#111421] p-8 rounded-[2.5rem] border border-white/5 h-fit shadow-2xl">
                <h4 class="font-black text-lg mb-6 italic text-gray-300 uppercase tracking-tighter">নতুন ভিডিও যুক্ত করুন
                </h4>

                <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data"
                    id="videoUploadForm" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">ভিডিও
                            টাইটেল</label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            class="w-full bg-[#080a12] border border-white/5 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-white"
                            placeholder="ভিডিওর শিরোনাম দিন">
                    </div>

                    <div x-data="{ videoType: '{{ old('video_type', 'youtube') }}' }">
                        <label class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">ভিডিও
                            টাইপ</label>
                        <select name="video_type" x-model="videoType"
                            class="w-full bg-[#080a12] border border-white/5 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition cursor-pointer text-white">
                            <option value="youtube">YouTube</option>
                            <option value="facebook">Facebook</option>
                            <option value="tiktok">TikTok</option>
                            <option value="local">Local Storage (Upload File)</option>
                            <option value="others">Others</option>
                        </select>

                        <div class="mt-5">
                            <div x-show="videoType === 'local'" x-transition>
                                <label
                                    class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest text-yellow-500">ভিডিও
                                    ফাইল (সর্বোচ্চ ১০০ মেগাবাইট)</label>
                                <input type="file" name="local_video" id="localVideoInput"
                                    class="w-full bg-[#080a12] border border-white/5 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-gray-400 file:bg-yellow-500 file:border-none file:text-black file:mr-4 file:rounded-lg file:text-[10px] file:uppercase file:font-black file:px-4 file:py-1">
                            </div>

                            <div x-show="videoType !== 'local'" x-transition>
                                <label
                                    class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">ভিডিও
                                    লিঙ্ক (URL)</label>
                                <input type="text" name="video_url" value="{{ old('video_url') }}"
                                    class="w-full bg-[#080a12] border border-white/5 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-white"
                                    placeholder="https://example.com/video">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">Earning
                                (৳)</label>
                            <input type="number" step="0.01" name="earning_per_view"
                                value="{{ old('earning_per_view', '5.00') }}"
                                class="w-full bg-[#080a12] border border-white/5 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-white">
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">Duration
                                (Sec)</label>
                            <input type="number" name="duration" value="{{ old('duration') }}"
                                class="w-full bg-[#080a12] border border-white/5 rounded-xl p-4 text-sm focus:border-yellow-500 outline-none transition text-white"
                                placeholder="ঐচ্ছিক">
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full py-4 bg-yellow-500 text-black font-black rounded-2xl uppercase tracking-[0.2em] text-xs hover:bg-yellow-600 transition shadow-lg shadow-yellow-500/20 active:scale-95">
                        <span id="btnText">Publish Video Now</span>
                    </button>
                </form>
            </div>

            {{-- ২. ভিডিও লিস্ট সেকশন --}}
            <div class="lg:col-span-2 bg-[#111421] rounded-[3rem] p-8 border border-white/5 shadow-2xl overflow-hidden">
                <h4 class="font-black text-lg mb-8 italic text-gray-300 uppercase tracking-tighter">Active Video List</h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-[10px] font-black uppercase text-gray-600 tracking-widest">
                                <th class="pb-4 pl-4">Title & Duration</th>
                                <th class="pb-4">Platform</th>
                                <th class="pb-4">Reward</th>
                                <th class="pb-4 text-right pr-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($videos as $video)
                                <tr class="bg-[#080a12]/50 group hover:bg-white/5 transition-all duration-300">
                                    <td class="p-5 rounded-l-2xl border-l border-white/5">
                                        <p class="text-sm font-bold text-gray-200">{{ $video->title }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[9px] text-gray-600 uppercase font-black tracking-widest">
                                                {{ $video->duration ? $video->duration . ' Seconds' : 'No Duration' }}
                                            </span>
                                            @if ($video->video_type === 'local')
                                                <span
                                                    class="text-[8px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-lg border border-blue-500/20 font-black uppercase">📁
                                                    Local</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="px-3 py-1 bg-white/5 border border-white/5 rounded-xl text-[9px] font-black uppercase text-yellow-500">
                                            {{ $video->video_type }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <span class="text-emerald-500 font-black text-sm italic">৳
                                            {{ number_format($video->earning_per_view, 2) }}</span>
                                    </td>
                                    <td class="p-5 rounded-r-2xl border-r border-white/5 text-right pr-4">
                                        <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                class="delete-btn w-9 h-9 inline-flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-3 py-1 bg-white/5 border border-white/5 rounded-xl text-[9px] font-black uppercase text-yellow-500">
                                                {{ $video->video_type }}
                                            </span>

                                            {{-- কপি বাটন --}}
                                            <button onclick="copyToClipboard('{{ $video->video_url }}', this)"
                                                class="p-2 bg-white/5 hover:bg-yellow-500/20 text-gray-400 hover:text-yellow-500 rounded-lg transition-all border border-white/5 group/copy"
                                                title="Copy Video Link">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-20 opacity-20 text-xs font-black uppercase">
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
        // SweetAlert কনফিগারেশন (Dark Theme)
        const DarkToast = Swal.mixin({
            background: '#111421',
            color: '#fff',
            confirmButtonColor: '#eab308',
            cancelButtonColor: '#ef4444',
            customClass: {
                popup: 'rounded-[2rem] border border-white/5 shadow-2xl',
                title: 'italic font-black text-yellow-500',
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
                    DarkToast.fire({
                        icon: 'error',
                        title: 'ফাইলটি অনেক বড়!',
                        text: `আপনার ভিডিও ফাইলটি ${fileSize.toFixed(2)} MB। দয়া করে ১০০ MB এর নিচে ফাইল আপলোড করুন।`,
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
                DarkToast.fire({
                    title: 'আপনি কি নিশ্চিত?',
                    text: "এই ভিডিওটি ডিলিট করলে আর ফিরে পাওয়া যাবে না!",
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
            DarkToast.fire({
                icon: 'success',
                title: 'সাফল্য!',
                text: "{{ session('success') }}",
                timer: 3000
            });
        @endif

        @if (session('error') || $errors->any())
            DarkToast.fire({
                icon: 'error',
                title: 'ওহ না!',
                text: "{{ session('error') ?? $errors->first() }}",
            });
        @endif
    </script>

    <script>
        function copyToClipboard(text, btn) {
            // ক্রোম এবং আধুনিক ব্রাউজারের জন্য
            navigator.clipboard.writeText(text).then(() => {
                // বাটনের আইকন সাময়িকভাবে পরিবর্তন (Success feedback)
                const originalHTML = btn.innerHTML;
                btn.innerHTML = `
            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
                btn.classList.add('border-emerald-500/50');

                // ২ সেকেন্ড পর আবার আগের অবস্থায় ফিরে যাওয়া
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('border-emerald-500/50');
                }, 2000);
            }).catch(err => {
                console.error('Copy failed!', err);
            });
        }
    </script>
@endsection
