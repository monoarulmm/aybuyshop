@extends('layouts.app') {{-- আপনার অ্যাডমিন লেআউট অনুযায়ী পরিবর্তন করুন --}}

@section('content')
    <div class="p-6 bg-[#0a0c14] min-h-screen">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black text-white italic uppercase tracking-wider border-l-4 border-yellow-500 pl-4">
                অর্ডার ম্যানেজমেন্ট
            </h2>
            <div class="text-gray-400 text-sm italic">
                মোট অর্ডার: <span class="text-yellow-500 font-bold">{{ $orders->total() }}টি</span>
            </div>
        </div>

        {{-- সাকসেস মেসেজ --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-2xl text-sm font-bold animate-pulse">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#111421] border border-gray-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#161b2c] text-gray-400 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="p-5">অর্ডার তথ্য</th>
                            <th class="p-5">ইউজার ডিটেইলস</th>
                            <th class="p-5">পেমেন্ট ব্রেকডাউন</th> {{-- নতুন কলাম --}}
                            <th class="p-5">মোট (Total)</th>
                            <th class="p-5">অবস্থা</th>
                            <th class="p-5 text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 divide-y divide-gray-800/50">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-[#161b2c]/30 transition-all group">
                                {{-- অর্ডার আইডি --}}
                                <td class="p-5">
                                    <div class="font-black text-blue-500 text-sm">#ORD-{{ $order->id }}</div>
                                    <div class="text-[10px] text-gray-600 mt-1 uppercase">
                                        {{ $order->created_at->format('d M, Y') }}
                                    </div>
                                </td>

                                {{-- ইউজার ইনফো --}}
                                <td class="p-5">
                                    <div class="font-bold text-white group-hover:text-yellow-500 transition-colors">
                                        {{ $order->user->name ?? 'Guest' }}
                                    </div>
                                    <div class="text-xs text-blue-400 font-medium mt-1">{{ $order->phone }}</div>
                                    <div class="text-[9px] text-gray-500 truncate w-32 italic">{{ $order->address }}</div>
                                </td>

                                {{-- পেমেন্ট ব্রেকডাউন (ওয়ালেট বনাম ক্যাশ) --}}
                                <td class="p-5">
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-[10px]">
                                            <span class="text-gray-500">Wallet Paid:</span>
                                            <span
                                                class="text-green-500 font-bold">৳{{ number_format($order->wallet_paid, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-[10px]">
                                            <span class="text-gray-500">Cash (COD):</span>
                                            <span
                                                class="text-orange-400 font-bold">৳{{ number_format($order->cash_on_delivery, 2) }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- মোট টাকা --}}
                                <td class="p-5">
                                    <div class="font-black text-yellow-500 text-lg">
                                        ৳{{ number_format($order->total_amount) }}
                                    </div>
                                    <div class="text-[9px] text-gray-600 uppercase tracking-tighter">Grand Total</div>
                                </td>

                                {{-- স্ট্যাটাস ব্যাজ --}}
                                <td class="p-5">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                            'active' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                            'delivered' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                            'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                        ];
                                        $class = $statusClasses[$order->status] ?? $statusClasses['pending'];
                                    @endphp
                                    <span
                                        class="{{ $class }} px-3 py-1 rounded-lg text-[9px] font-black uppercase border tracking-tighter">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                {{-- আপডেট ফর্ম --}}
                                <td class="p-5">
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST"
                                        class="flex items-center justify-center gap-2">
                                        @csrf
                                        @method('PUT')

                                        <select name="status"
                                            class="bg-[#0a0c14] border border-gray-800 text-white text-[10px] font-bold rounded-xl px-3 py-2 focus:outline-none focus:border-yellow-500 transition-all appearance-none cursor-pointer">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="active" {{ $order->status == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="delivered"
                                                {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>
                                                Rejected</option>
                                        </select>

                                        <button type="submit"
                                            class="bg-yellow-500 hover:bg-yellow-400 text-black p-2 rounded-xl transition-all shadow-lg shadow-yellow-500/10 active:scale-90"
                                            title="আপডেট করুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- প্যাগিনেশন --}}
        <div class="mt-8 flex justify-center">
            <div class="bg-[#111421] p-2 rounded-2xl border border-gray-800">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
