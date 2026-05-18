@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="bg-[#111421] rounded-[2.5rem] p-8 border border-gray-800 shadow-2xl">
            <h2
                class="text-2xl font-black text-white mb-6 uppercase tracking-tighter italic border-l-4 border-yellow-500 pl-4">
                প্রোডাক্ট এডিট: {{ $product->name }}
            </h2>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                id="editForm" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- নাম ও ক্যাটাগরি --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">নাম</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white outline-none focus:border-blue-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">ক্যাটাগরি</label>
                        <select name="category_id"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white outline-none focus:border-blue-600 appearance-none">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- মূল্য ও স্টক --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">মূল্য</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white outline-none focus:border-blue-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">স্টক</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white outline-none focus:border-blue-600">
                    </div>
                </div>

                {{-- ডেসক্রিপশন (CKEditor) --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase ml-1">বিবরণ (Description)</label>
                    <div class="rounded-2xl overflow-hidden border border-gray-800">
                        <textarea name="description" id="editor">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                {{-- থাম্বনেইল প্রিভিউ ও চেঞ্জ --}}
                <div class="p-4 bg-[#0a0c14] rounded-2xl border border-gray-800">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-3">মেইন থাম্বনেইল</p>
                    <div class="flex items-center gap-6">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                            class="w-24 h-24 rounded-xl object-cover border-2 border-blue-600/30 shadow-lg">
                        <div class="flex-1">
                            <input type="file" name="thumbnail"
                                class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer w-full">
                        </div>
                    </div>
                </div>

                {{-- গ্যালারি ইমেজ প্রিভিউ ও নতুন এড --}}
                <div class="p-4 bg-[#0a0c14] rounded-2xl border border-gray-800">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-3">গ্যালারি ইমেজ (নতুন দিলে আগের সাথে যোগ হবে)
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if ($product->gallery)
                            @foreach ($product->gallery as $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="w-16 h-16 rounded-lg object-cover opacity-60">
                            @endforeach
                        @endif
                    </div>
                    <input type="file" name="gallery[]" multiple class="text-xs text-gray-400 w-full">
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-black py-4 rounded-2xl shadow-xl transition-all uppercase italic tracking-wider transform hover:scale-[1.01]">
                    আপডেট সেভ করুন
                </button>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editEditor;
        ClassicEditor.create(document.querySelector('#editor')).then(editor => {
            editEditor = editor;
        });

        document.querySelector('#editForm').addEventListener('submit', function(e) {
            if (editEditor) {
                document.querySelector('#editor').value = editEditor.getData();
            }
        });
    </script>

    <style>
        :root {
            --ck-color-base-background: #0a0c14;
            --ck-color-base-border: #1f2937;
            --ck-color-text: white;
        }

        .ck-editor__editable {
            min-height: 200px !important;
            background-color: #0a0c14 !important;
            color: white !important;
        }

        .ck-toolbar {
            background-color: #161b2c !important;
            border-color: #1f2937 !important;
        }
    </style>
@endsection
