@extends('layouts.app')

@section('content')
    {{-- SweetAlert2 CDN --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    <div class="max-w-6xl mx-auto py-10 px-4 animate-fade-in">

        {{-- Header Section --}}
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-4xl font-black text-white italic uppercase tracking-tighter">
                Account <span class="text-yellow-500">Verification</span>
            </h1>
            <p class="text-gray-500 font-medium text-sm mt-1">আপনার ব্যক্তিগত তথ্য এবং এনআইডি কার্ড আপডেট করুন।</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf
            @method('PUT')

            {{-- Left Column: Images & Identity --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Profile Photo Card --}}
                <div
                    class="bg-[#161925] border border-gray-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-yellow-500"></div>

                    <div class="relative mx-auto w-32 h-32 mb-4">
                        <img id="profile-preview"
                            src="{{ asset('storage/' . $user->profile_image) ?: asset('images/default-avatar.png') }}"
                            alt="Avatar"
                            class="w-full h-full rounded-full object-cover border-4 border-yellow-500/20 group-hover:border-yellow-500 transition-all duration-500">
                        <label
                            class="absolute bottom-0 right-0 bg-yellow-500 p-2.5 rounded-full cursor-pointer shadow-xl hover:scale-110 transition-transform border-4 border-[#161925]">
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <input type="file" name="profile_image" class="hidden"
                                onchange="previewImage(this, 'profile-preview')">
                        </label>
                    </div>

                    <h3 class="text-white text-center font-black uppercase text-sm tracking-widest">
                        {{ Auth::user()->name ?? 'Set Your Name' }}</h3>
                    <p class="text-yellow-500/60 text-center text-[10px] font-bold mt-1 uppercase italic tracking-widest">
                        {{ Auth::user()->type }} Member</p>
                </div>

                {{-- NID Image Card --}}
                <div class="bg-[#161925] border border-gray-800 p-6 rounded-[2.5rem] shadow-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em]">National ID Card</p>
                        <span class="text-xs text-yellow-500"><i class="fas fa-id-card"></i></span>
                    </div>

                    <div
                        class="relative w-full aspect-[1.6/1] bg-black/40 rounded-2xl border-2 border-dashed border-gray-800 flex items-center justify-center overflow-hidden group">
                        <img id="nid-preview" src="{{ asset('storage/' . $user->nid_image) }}"
                            class="w-full h-full object-cover {{ Auth::user()->nid_image ? '' : 'hidden' }}">

                        <div id="nid-placeholder" class="text-center px-4 {{ Auth::user()->nid_image ? 'hidden' : '' }}">
                            <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-gray-600 text-[10px] font-black uppercase tracking-widest">Upload NID
                                Front</span>
                        </div>

                        <div
                            class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <p
                                class="text-white text-[10px] font-black uppercase tracking-widest bg-yellow-500 px-4 py-2 rounded-full text-black">
                                Change Image</p>
                            <input type="file" name="nid_image" class="absolute inset-0 opacity-0 cursor-pointer"
                                onchange="previewImage(this, 'nid-preview', 'nid-placeholder')">
                        </div>
                    </div>
                    <p class="text-[9px] text-gray-600 mt-4 text-center">এনআইডির ছবি পরিষ্কার হতে হবে যাতে নাম ও নম্বর বোঝা
                        যায়।</p>
                </div>
            </div>

            {{-- Right Column: Information Form --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-[#161925] border border-gray-800 p-8 md:p-10 rounded-[2.5rem] shadow-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Name --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-2">Full
                                Name</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white focus:border-yellow-500 outline-none transition-all">
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-2">Email
                                Address</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white focus:border-yellow-500 outline-none transition-all">
                        </div>

                        {{-- NID Number --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-2">NID
                                Number</label>
                            <input type="text" name="nid_number"
                                value="{{ old('nid_number', Auth::user()->nid_number) }}" placeholder="123 456 7890"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white focus:border-yellow-500 outline-none transition-all font-mono tracking-widest">
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-2">Registered
                                Phone</label>
                            <input type="text" value="{{ Auth::user()->phone }}" disabled
                                class="w-full bg-white/5 border border-gray-800 rounded-2xl px-6 py-4 text-gray-600 font-bold cursor-not-allowed">
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] ml-2">Current
                                Address</label>
                            <textarea name="address" rows="4"
                                class="w-full bg-black/40 border border-gray-800 rounded-2xl px-6 py-4 text-white focus:border-yellow-500 outline-none transition-all">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-10">
                        <button type="submit"
                            class="group relative w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black uppercase py-5 rounded-2xl shadow-2xl transition-all transform active:scale-[0.98] overflow-hidden">
                            <span class="relative z-10 flex items-center justify-center gap-3">Save Account Changes</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Error handling with SweetAlert2 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ১. ভ্যালিডেশন এরর চেক (Backend Validation)
            @if ($errors->any())
                let errorMessages = '';
                @foreach ($errors->all() as $error)
                    errorMessages += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'ভুল হয়েছে!',
                    text: errorMessages,
                    background: '#161925',
                    color: '#fff',
                    confirmButtonColor: '#EAB308'
                });
            @endif

            // ২. সাকসেস মেসেজ চেক (Flash Session)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'সফল!',
                    text: "{{ session('success') }}",
                    background: '#161925',
                    color: '#fff',
                    confirmButtonColor: '#EAB308',
                    timer: 3000
                });
            @endif
        });

        // ৩. ইমেজ প্রিভিউ ফাংশন
        function previewImage(input, previewId, placeholderId = null) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholderId) {
                        document.getElementById(placeholderId).classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
