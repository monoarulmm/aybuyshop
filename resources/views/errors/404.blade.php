@extends('layouts.app')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-6">
        <div class="max-w-3xl w-full text-center relative">

            {{-- Background Glows --}}
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-600/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-purple-600/20 rounded-full blur-[100px] animate-pulse">
            </div>

            {{-- Main Content --}}
            <div
                class="relative z-10 bg-[#161925]/60 backdrop-blur-3xl border border-white/5 rounded-[3rem] p-12 md:p-20 shadow-2xl overflow-hidden">

                {{-- Big 404 Text --}}
                <h1
                    class="text-[120px] md:text-[180px] font-black leading-none italic tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-white via-indigo-400 to-indigo-900 drop-shadow-2xl">
                    404
                </h1>

                <div class="mt-4 space-y-4">
                    <h2 class="text-white text-2xl md:text-4xl font-bold uppercase tracking-tight italic">
                        Lost in the <span class="text-indigo-400">Digital Void?</span>
                    </h2>
                    <p class="text-gray-400 text-sm md:text-base max-w-md mx-auto font-medium leading-relaxed">
                        The page you are looking for has been moved, deleted, or never existed in this dimension.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-5">
                    <a href="{{ url('/') }}"
                        class="w-full md:w-auto px-10 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-1 active:scale-95">
                        Return Home
                    </a>

                    <button onclick="window.history.back()"
                        class="w-full md:w-auto px-10 py-4 bg-white/5 hover:bg-white/10 text-gray-300 border border-white/10 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all backdrop-blur-md">
                        Go Back
                    </button>
                </div>

                {{-- Decorative Status Line --}}
                <div class="mt-12 pt-8 border-t border-white/5 flex items-center justify-center gap-8">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                        <span class="text-[10px] text-gray-500 font-black uppercase tracking-widest">System Stable</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Error Code:
                            NULL_PATH</span>
                    </div>
                </div>

            </div>

            {{-- Floating Shapes --}}
            <div class="absolute top-1/2 left-0 -translate-x-1/2 w-12 h-12 bg-indigo-500/20 rounded-xl rotate-12 blur-sm">
            </div>
            <div class="absolute bottom-1/4 right-0 translate-x-1/2 w-16 h-16 bg-purple-500/20 rounded-full blur-sm"></div>
        </div>
    </div>

    <style>
        body {
            background-color: #0d0f1a;
            /* আপনার সাইটের মেইন ব্যাকগ্রাউন্ড কালার */
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 0.8;
            }
        }
    </style>
@endsection
