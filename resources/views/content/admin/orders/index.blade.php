@extends('layouts.app') {{-- আপনার অ্যাডমিন লেআউট অনুযায়ী পরিবর্তন করুন --}}

@section('content')
    <div class="p-6 bg-slate-50/50 min-h-screen">
        {{-- হেডার সেকশন --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight border-l-4 border-amber-500 pl-4 uppercase">
                    অর্ডার ম্যানেজমেন্ট
                </h2>
                <p class="text-slate-500 text-sm mt-1 ml-5">গ্রাহকদের সকল অর্ডারের বর্তমান অবস্থা পরিচালনা করুন</p>
            </div>
            <div class="bg-amber-50 text-amber-800 border border-amber-200/60 px-4 py-2 rounded-2xl text-sm font-bold shadow-sm">
                মোট অর্ডার: <span class="text-amber-600 font-extrabold">{{ $orders->total() }} টি</span>
            </div>
        </div>

        {{-- সাকসেস মেসেজ --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold shadow-sm flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- টেবিল কন্টেইনার --}}
        <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="p-5">অর্ডার তথ্য</th>
                            <th class="p-5">ইউজার ডিটেইলস</th>
                            <th class="p-5">পেমেন্ট ব্রেকডাউন</th>
                            <th class="p-5">মোট (Total)</th>
                            <th class="p-5">অবস্থা (Status)</th>
                            <th class="p-5 text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 divide-y divide-slate-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-slate-50/40 transition-all group">
                                {{-- অর্ডার আইডি --}}
                                <td class="p-5">
                                    <div class="font-black text-blue-600 text-sm">#ORD-{{ $order->id }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">
                                        {{ $order->created_at->format('d M, Y') }}
                                    </div>
                                </td>

                                {{-- ইউজার ইনফো --}}
                                <td class="p-5">
                                    <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                        {{ $order->user->name ?? 'Guest' }}
                                    </div>
                                    <div class="text-xs text-slate-600 font-medium mt-1">{{ $order->phone }}</div>
                                    <div class="text-[10px] text-slate-400 truncate w-40 italic mt-0.5" title="{{ $order->address }}">
                                        {{ $order->address }}
                                    </div>
                                </td>

                                {{-- পেমেন্ট ব্রেকডাউন (ওয়ালেট বনাম ক্যাশ) --}}
                                <td class="p-5">
                                    <div class="space-y-1 w-36">
                                        <div class="flex justify-between text-[11px] font-medium">
                                            <span class="text-slate-400">Wallet Paid:</span>
                                            <span class="text-emerald-600 font-bold">৳{{ number_format($order->wallet_paid, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-medium">
                                            <span class="text-slate-400">Cash (COD):</span>
                                            <span class="text-orange-500 font-bold">৳{{ number_format($order->cash_on_delivery, 2) }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- মোট টাকা --}}
                                <td class="p-5">
                                    <div class="font-black text-slate-900 text-base">
                                        ৳{{ number_format($order->total_amount) }}
                                    </div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Grand Total</div>
                                </td>

                                {{-- স্ট্যাটাস ব্যাজ --}}
                                <td class="p-5">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'active' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                        $class = $statusClasses[$order->status] ?? $statusClasses['pending'];
                                    @endphp
                                    <span class="{{ $class }} px-3 py-1 rounded-xl text-[10px] font-black uppercase border tracking-wider shadow-sm">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                {{-- অ্যাকশন বাটন এবং ফর্ম --}}
                                <td class="p-5">
                                    <div class="flex items-center justify-center gap-2.5">
                                        {{-- বিস্তারিত দেখার বাটন --}}
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="p-2 bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-slate-200/60 shadow-sm"
                                           title="অর্ডারের বিস্তারিত দেখুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        {{-- স্ট্যাটাস আপডেট ফর্ম --}}
                                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex items-center gap-1.5">
                                            @csrf
                                            @method('PUT')
                                            <div class="relative">
                                                <select name="status" class="bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl pl-3 pr-8 py-2 focus:outline-none focus:border-blue-600 focus:bg-white transition-all appearance-none cursor-pointer">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="active" {{ $order->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                    <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                                    <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                </div>
                                            </div>
                                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white p-2.5 rounded-xl transition-all shadow-sm active:scale-95 flex items-center justify-center" title="স্ট্যাটাস সেভ করুন">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-slate-400 italic font-medium">
                                    বর্তমানে কোনো অর্ডার পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- প্যাগিনেশন --}}
        @if($orders->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection