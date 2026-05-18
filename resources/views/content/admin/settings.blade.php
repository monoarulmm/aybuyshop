@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#080a11] py-12 px-6 lg:px-16 text-white">

        {{-- Top Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div>
                <h2 class="text-3xl font-black italic tracking-tighter uppercase">Admin <span
                        class="text-yellow-500">Panel</span></h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Management Dashboard</p>
            </div>

            <div class="flex items-center gap-6">
                {{-- Notifications --}}
                <div class="relative" x-data="{ open: false }">
                    @php
                        $pendingRegCount = \App\Models\User::where('status', 'pending')->count();
                        $pendingWithdrawCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                        $totalNotif = $pendingRegCount + $pendingWithdrawCount;
                    @endphp

                    <button @click="open = !open"
                        class="relative p-3 bg-[#161925] rounded-2xl border border-white/5 hover:border-yellow-500/50 transition-all group focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-yellow-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($totalNotif > 0)
                            <span class="absolute top-2 right-2 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-[#161925]"></span>
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-4 w-80 bg-[#161925] border border-white/10 rounded-[2rem] shadow-2xl z-50 overflow-hidden">
                        <div class="p-5 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                            <h4 class="font-black text-[10px] uppercase tracking-widest text-yellow-500">Alerts Dashboard
                            </h4>
                            <span
                                class="text-[9px] bg-yellow-500 text-black px-2 py-0.5 rounded-full font-black">{{ $totalNotif }}
                                Total</span>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            @if ($totalNotif == 0)
                                <div class="p-8 text-center text-gray-600 text-[10px] font-black uppercase tracking-widest">
                                    ✨ No Pending Actions</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Admin Identity --}}
                <div class="flex items-center gap-3 bg-[#161925] p-1.5 pr-5 rounded-2xl border border-white/5">
                    <div
                        class="w-10 h-10 bg-gradient-to-tr from-yellow-500 to-yellow-700 rounded-xl flex items-center justify-center font-black text-[#0f111a] text-xs">
                        {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <div class="hidden md:block">
                        <p class="text-[10px] font-black uppercase tracking-tight text-white leading-none">
                            {{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[9px] text-emerald-500 font-bold uppercase leading-none mt-1">Online</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            {{-- Title & Flash Messages --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-4xl font-black italic tracking-tighter uppercase">SYSTEM <span
                            class="text-yellow-500">CONTROL</span> PANEL</h1>
                    <p class="text-gray-500 text-xs font-bold tracking-[0.4em] mt-2 uppercase">Professional Global Config
                    </p>
                </div>

                @if (session('success'))
                    <div id="status-popup"
                        class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-8 py-4 rounded-3xl text-sm font-bold shadow-2xl animate-bounce">
                        ✨ {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div id="status-popup"
                        class="bg-red-500/10 border border-red-500/30 text-red-400 px-8 py-4 rounded-3xl text-sm font-bold shadow-2xl animate-pulse">
                        ❌ Error: Please check the fields below
                    </div>
                @endif
            </div>

            <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST"
                enctype="multipart/form-data" class="space-y-10">
                @csrf

                {{-- 01. Core Identity & Branding --}}
                <div class="bg-[#111421] border border-white/5 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 text-[12rem] font-black text-white/5 italic">01</div>
                    <h2 class="text-xl font-black uppercase italic mb-10 flex items-center gap-4">
                        <span
                            class="w-10 h-10 bg-yellow-500 text-black rounded-2xl flex items-center justify-center not-italic">1</span>
                        Core Identity & Branding
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <div class="lg:col-span-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Website
                                Official Title</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name ?? '') }}"
                                class="w-full bg-[#080a11] border {{ $errors->has('site_name') ? 'border-red-500' : 'border-white/10' }} rounded-2xl px-6 py-5 focus:border-yellow-500 outline-none transition-all font-bold text-lg">
                            @error('site_name')
                                <p class="text-red-500 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Logo Upload --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Main
                                Logo</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 flex items-center justify-center overflow-hidden">
                                    @if (isset($setting->logo) && !empty($setting->logo))
                                        <img id="logo-preview" src="{{ asset('storage/' . $setting->logo) }}"
                                            class="max-h-full">
                                    @else
                                        <span id="logo-placeholder" class="text-gray-700 font-bold italic">NO LOGO</span>
                                        <img id="logo-preview" src="#" class="max-h-full hidden">
                                    @endif
                                </div>
                                <input type="file" name="logo" class="text-[10px] text-gray-500 cursor-pointer"
                                    onchange="previewImg(this, 'logo-preview', 'logo-placeholder')">
                            </div>
                        </div>

                        {{-- Favicon --}}
                        <div class="space-y-4">
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Favicon</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 flex items-center justify-center">
                                    @if (isset($setting->favicon) && !empty($setting->favicon))
                                        <img id="favicon-preview" src="{{ asset('storage/' . $setting->favicon) }}"
                                            class="h-16 w-16 object-contain">
                                    @else
                                        <span id="favicon-placeholder" class="text-gray-700 font-bold italic">NO ICON</span>
                                        <img id="favicon-preview" src="#" class="h-16 w-16 object-contain hidden">
                                    @endif
                                </div>
                                <input type="file" name="favicon" class="text-[10px] text-gray-500"
                                    onchange="previewImg(this, 'favicon-preview', 'favicon-placeholder')">
                            </div>
                        </div>

                        {{-- Banner --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Hero
                                Banner</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 w-full overflow-hidden rounded-xl">
                                    @if (isset($setting->main_banner) && !empty($setting->main_banner))
                                        <img id="banner-preview" src="{{ asset('storage/' . $setting->main_banner) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span id="banner-placeholder" class="text-gray-700 font-bold italic">EMPTY</span>
                                        <img id="banner-preview" src="#"
                                            class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <input type="file" name="main_banner" class="text-[10px] text-gray-500"
                                    onchange="previewImg(this, 'banner-preview', 'banner-placeholder')">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 02. Sponsor Management --}}
                <div class="bg-[#111421] border border-white/5 rounded-[3rem] p-10 shadow-2xl relative">
                    <h2 class="text-xl font-black uppercase italic mb-10 flex items-center gap-4">
                        <span
                            class="w-10 h-10 bg-indigo-600 text-white rounded-2xl flex items-center justify-center not-italic">2</span>
                        Sponsor Management
                    </h2>

                    <div class="relative group mb-12">
                        <input type="file" name="sponsor_banner[]" multiple
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div
                            class="bg-[#080a11] border-2 border-dashed border-white/10 rounded-[2.5rem] py-16 text-center group-hover:border-indigo-500 transition-all duration-500">
                            <div class="text-4xl mb-4">📤</div>
                            <p class="text-gray-500 font-bold uppercase text-xs tracking-widest">Click or Drag images to
                                upload new sponsors</p>
                        </div>
                    </div>

                    @php $sponsors = isset($setting) ? json_decode($setting->sponsor_banner, true) : []; @endphp
                    @if ($sponsors && count($sponsors) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                            @foreach ($sponsors as $index => $path)
                                <div
                                    class="group relative aspect-square rounded-[1.5rem] overflow-hidden border border-white/5 bg-[#080a11]">
                                    <img src="{{ asset('storage/' . $path) }}"
                                        class="w-full h-full object-cover opacity-50 group-hover:opacity-100 transition-all duration-700">
                                    <label class="absolute inset-0 flex items-center justify-center cursor-pointer">
                                        <input type="checkbox" name="remove_sponsors[]" value="{{ $index }}"
                                            class="peer hidden">
                                        <div
                                            class="absolute inset-0 bg-red-600/40 opacity-0 peer-checked:opacity-100 transition-all flex items-center justify-center">
                                            <span
                                                class="bg-white text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">To
                                                Be Deleted</span>
                                        </div>
                                        <div
                                            class="absolute top-3 right-3 bg-red-500 text-white p-2 rounded-xl opacity-0 group-hover:opacity-100 peer-checked:bg-white peer-checked:text-red-600 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- 03. Global Connectivity --}}
                <div class="bg-[#111421] border border-white/5 rounded-[3rem] p-10 shadow-2xl relative">
                    <h2 class="text-xl font-black uppercase italic mb-10 flex items-center gap-4">
                        <span
                            class="w-10 h-10 bg-emerald-600 text-white rounded-2xl flex items-center justify-center not-italic">3</span>
                        Global Connectivity
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Primary
                                Phone</label>
                            <input type="text" name="phone_primary"
                                value="{{ old('phone_primary', $setting->phone_primary ?? '') }}"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-emerald-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Secondary
                                Phone</label>
                            <input type="text" name="phone_secondary"
                                value="{{ old('phone_secondary', $setting->phone_secondary ?? '') }}"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-white/20 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Official
                                Email</label>
                            <input type="email" name="email" value="{{ old('email', $setting->email ?? '') }}"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-blue-500 outline-none">
                        </div>
                        <div class="md:col-span-3 space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Head Office
                                Address</label>
                            <textarea name="address" rows="3"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-5 focus:border-yellow-500 outline-none">{{ old('address', $setting->address ?? '') }}</textarea>
                        </div>
                        {{-- Social Links --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Facebook
                                Profile</label>
                            <input type="text" name="fb_link" value="{{ old('fb_link', $setting->fb_link ?? '') }}"
                                placeholder="https://..."
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-blue-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-red-500 uppercase tracking-widest">YouTube
                                Channel</label>
                            <input type="text" name="youtube_link"
                                value="{{ old('youtube_link', $setting->youtube_link ?? '') }}" placeholder="https://..."
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-red-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">WhatsApp
                                Number</label>
                            <input type="text" name="whatsapp_link"
                                value="{{ old('whatsapp_link', $setting->whatsapp_link ?? '') }}" placeholder="+880..."
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-emerald-500 outline-none">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-8 pt-10 pb-20">
                    <button type="reset"
                        class="text-[10px] font-black text-gray-600 uppercase tracking-[0.3em] hover:text-white transition-colors">Discard
                        Changes</button>
                    <button type="submit" id="submit-btn"
                        class="bg-yellow-500 text-black px-16 py-6 rounded-3xl font-black uppercase text-xs tracking-widest shadow-xl hover:scale-[1.05] active:scale-95 transition-all duration-300 flex items-center gap-3">
                        <span id="btn-text">Update Global Config</span>
                        <div id="loader"
                            class="hidden w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin">
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ১. সাবমিট বাটন ডিসেবল করা
        const form = document.getElementById('settings-form');
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btnText.innerText = 'Updating...';
            loader.classList.remove('hidden');
        });

        // ২. সাকসেস মেসেজ অটো হাইড করা
        const popup = document.getElementById('status-popup');
        if (popup) {
            setTimeout(() => {
                popup.style.transition = "opacity 0.8s ease";
                popup.style.opacity = "0";
                setTimeout(() => popup.remove(), 800);
            }, 4000);
        }

        // ৩. ইনস্ট্যান্ট ইমেজ প্রিভিউ ফাংশন (উন্নত সংস্করণ)
        function previewImg(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #080a11;
        }

        ::-webkit-scrollbar-thumb {
            background: #1a1e2e;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ea7708;
        }
    </style>
@endsection
