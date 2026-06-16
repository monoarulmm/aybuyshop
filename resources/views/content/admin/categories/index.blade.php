@extends('layouts.app')

@section('content')
    <div class="p-6 bg-slate-50/50 min-h-screen text-slate-700 antialiased">
        
        {{-- হেডার সেকশন --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 max-w-5xl mx-auto">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight uppercase border-l-4 border-amber-500 pl-4">
                    ক্যাটাগরি ম্যানেজমেন্ট
                </h2>
                <p class="text-xs text-slate-400 font-medium mt-1 ml-5">ওয়েবসাইটের সকল প্রোডাক্ট ক্যাটাগরি তৈরি ও নিয়ন্ত্রণ করুন</p>
            </div>
            <div class="bg-amber-50 text-amber-800 border border-amber-200/60 px-4 py-2 rounded-2xl text-sm font-bold shadow-sm">
                মোট ক্যাটাগরি: <span class="text-amber-600 font-extrabold">{{ count($categories) }}টি</span>
            </div>
        </div>

        {{-- নোটিফিকেশন মেসেজ (সাকসেস এবং এরর হ্যান্ডলিং) --}}
        <div class="max-w-5xl mx-auto">
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold shadow-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start max-w-5xl mx-auto">
            
            {{-- বাম পাশ: নতুন ক্যাটাগরি তৈরি করার ফর্ম (4 Columns) --}}
            <div class="lg:col-span-4 bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm">
                <h3 class="text-slate-800 font-bold text-base mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    নতুন ক্যাটাগরি যুক্ত করুন
                </h3>

                <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[11px] font-black uppercase text-slate-400 tracking-wider block mb-1.5 ml-1">ক্যাটাগরির নাম</label>
                        <input type="text" name="name" placeholder="উদা: ইলেকট্রনিক্স, কসমেটিক্স" 
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-600 focus:bg-white transition-all placeholder:text-slate-400" 
                               required autocomplete="off">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-xl transition-all shadow-md shadow-blue-600/10 active:scale-[0.98] flex items-center justify-center gap-2">
                        <span>সংরক্ষণ করুন</span>
                    </button>
                </form>
            </div>

            {{-- ডান পাশ: ক্যাটাগরি তালিকা (8 Columns) --}}
            <div class="lg:col-span-8 bg-white border border-slate-200 rounded-[2rem] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-black tracking-widest">
                            <tr>
                                <th class="p-5 w-20 text-center">#আইডি</th>
                                <th class="p-5">ক্যাটাগরির নাম</th>
                                <th class="p-5 text-center w-32">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 divide-y divide-slate-100">
                            @forelse ($categories as $index => $cat)
                                <tr class="hover:bg-slate-50/40 transition-all group">
                                    {{-- আইডি কলাম --}}
                                    <td class="p-5 text-center font-bold text-slate-400 text-xs">
                                        {{ $index + 1 }}
                                    </td>
                                    
                                    {{-- ক্যাটাগরি নাম কলাম --}}
                                    <td class="p-5">
                                        <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-sm">
                                            {{ $cat->name }}
                                        </div>
                                    </td>

                                    {{-- ডিলিট অ্যাকশন কলাম --}}
                                    <td class="p-5 text-center">
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" 
                                              onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই ক্যাটাগরি তথ্যটি ডিলিট করতে চান?');"
                                              class="inline-block">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100 rounded-xl transition-all shadow-sm active:scale-95 flex items-center justify-center" 
                                                    title="ডিলিট করুন">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-10 text-center text-slate-400 italic font-medium">
                                        বর্তমানে কোনো ক্যাটাগরি খুঁজে পাওয়া যায়নি।
                                    </td>
                                endtr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection