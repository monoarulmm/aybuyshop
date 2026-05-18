@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-black text-white mb-8 italic uppercase tracking-wider">আপনার কার্ট</h2>

        @if (\Cart::isEmpty())
            <div class="bg-[#111421] p-10 rounded-3xl text-center border border-gray-800">
                <p class="text-gray-400 mb-6">আপনার কার্টে কোনো প্রোডাক্ট নেই!</p>
                <a href="{{ route('shop.index') }}"
                    class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-blue-700 transition">শপিং করুন</a>
            </div>
        @else
            {{-- কার্ট আইটেমস লিস্ট --}}
            <div class="space-y-4 mb-8">
                @foreach (\Cart::getContent() as $item)
                    <div class="bg-[#111421] p-4 rounded-3xl border border-gray-800 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('storage/' . $item->attributes->thumbnail) }}"
                                class="w-16 h-16 rounded-xl object-cover border border-gray-700">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ $item->name }}</h4>
                                <p class="text-yellow-500 font-black">৳{{ $item->price }}</p>
                            </div>
                        </div>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="text-red-500 text-xs font-bold hover:underline">রিমুভ</button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- অর্ডার ফরম --}}
            <div class="bg-[#111421] p-8 rounded-[2.5rem] border border-gray-800 shadow-2xl">
                <h3 class="text-white font-bold mb-6 italic border-b border-gray-800 pb-4 text-xl">অর্ডার কনফার্ম করুন</h3>
                <form action="{{ route('order.place') }}" method="POST" class="space-y-6">
                    @csrf

                    @guest
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="name" placeholder="আপনার নাম"
                                class="bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none"
                                required>
                            <input type="text" name="phone" placeholder="ফোন নম্বর"
                                class="bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none"
                                required>
                            <input type="email" name="email" placeholder="ইমেইল"
                                class="bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none md:col-span-2">
                        </div>
                        <textarea name="address" placeholder="পুরো ঠিকানা"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none"
                            required></textarea>
                    @endguest

                    {{-- CKEditor Note Section --}}

                    <textarea name="address" placeholder="পুরো ঠিকানা"
                        class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none"
                        required></textarea>
                    <div class="space-y-2">
                        <label class="text-gray-400 text-xs block ml-2 font-bold uppercase tracking-widest">অর্ডার নোট
                            (Color, Size, etc.)</label>
                        <div class="rounded-2xl overflow-hidden border border-gray-800">
                            <textarea id="editor" name="note">
                                <ul>
                                    <li><strong>Color:</strong> </li>
                                    <li><strong>Size:</strong> </li>
                                    <li><strong>Note:</strong> </li>
                                </ul>
                            </textarea>
                        </div>
                    </div>

                    {{-- পেমেন্ট সামারি ক্যালকুলেশন --}}
                    <div class="p-6 bg-[#0a0c14] rounded-2xl border border-gray-800 space-y-3 mt-4">
                        <div class="flex justify-between text-gray-400 text-sm">
                            <span>মোট দাম:</span>
                            <span class="font-bold text-gray-200">৳{{ \Cart::getTotal() }}</span>
                        </div>

                        @auth
                            @php
                                $user = auth()->user();
                                $cartTotal = \Cart::getTotal();
                                $totalEarnings = \DB::table('user_earnings')
                                    ->where('user_id', $user->id)
                                    ->sum('amount');
                                $walletPaid = 0;
                                if (
                                    $user->role !== 'n_user' &&
                                    in_array($user->type, ['basic', 'premium', 'premium_pro'])
                                ) {
                                    $walletPaid = min($totalEarnings, $cartTotal);
                                }
                                $payableAmount = $cartTotal - $walletPaid;
                            @endphp

                            @if ($walletPaid > 0)
                                <div class="flex justify-between text-green-500 text-sm italic">
                                    <span>ব্যালেন্স থেকে কাটা হবে:</span>
                                    <span class="font-bold">- ৳{{ number_format($walletPaid, 2) }}</span>
                                </div>
                                <div class="text-[10px] text-gray-500 text-right italic">
                                    (আপনার মোট ইনকাম ব্যালেন্স: ৳{{ number_format($totalEarnings, 2) }})
                                </div>
                            @endif
                        @else
                            @php $payableAmount = \Cart::getTotal(); @endphp
                        @endauth

                        <div
                            class="flex justify-between items-center text-white font-black text-xl border-t border-gray-800 pt-4 mt-2">
                            <div>
                                <span
                                    class="block uppercase text-[10px] text-blue-500 tracking-widest mb-1 font-black">Payable
                                    Amount</span>
                                <span>এখন পরিশোধ করতে হবে:</span>
                            </div>
                            <span class="text-yellow-500 italic">৳{{ number_format($payableAmount, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl transition-all transform hover:scale-[1.01]">অর্ডার
                        কনফার্ম করুন</button>
                </form>
            </div>
        @endif
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo']
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <style>
        /* CKEditor 5 Custom Dark Styles */
        :root {
            --ck-color-base-background: #0a0c14;
            --ck-color-toolbar-background: #111421;
            --ck-color-base-border: #1f2937;
            --ck-color-toolbar-border: #1f2937;
            --ck-color-text: #ffffff;
            --ck-color-button-default-hover-background: #1f2937;
        }

        .ck-editor__editable_inline {
            min-height: 150px;
            background-color: #0a0c14 !important;
            color: white !important;
        }

        .ck.ck-toolbar {
            background-color: #111421 !important;
            border-color: #1f2937 !important;
        }

        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
            border-color: #1f2937 !important;
        }

        .ck.ck-button {
            color: white !important;
            cursor: pointer;
        }

        .ck.ck-button:hover {
            background: #1f2937 !important;
        }

        .ck.ck-list {
            background: #111421 !important;
        }
    </style>
@endsection
