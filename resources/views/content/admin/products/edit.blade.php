@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50/50 py-10 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-sm">
            <h2 class="text-2xl font-black text-slate-800 mb-6 uppercase tracking-tighter italic border-l-4 border-amber-500 pl-4">
                প্রোডাক্ট এডিট: <span class="text-amber-500">{{ $product->name }}</span>
            </h2>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                id="editForm" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- নাম ও ক্যাটাগরি --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">নাম</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 outline-none focus:bg-white focus:border-blue-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">ক্যাটাগরি</label>
                        <div class="relative">
                            <select name="category_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 outline-none focus:bg-white focus:border-blue-600 appearance-none cursor-pointer">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- মূল্য ও স্টক --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">মূল্য</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 outline-none focus:bg-white focus:border-blue-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">স্টক</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 outline-none focus:bg-white focus:border-blue-600 transition-all">
                    </div>
                </div>

                {{-- ডেসক্রিপশন (CKEditor) --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase ml-1">বিবরণ (Description)</label>
                    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                        <textarea name="description" id="editor">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                {{-- থাম্বনেইল প্রিভিউ ও চেঞ্জ --}}
                <div class="p-5 bg-slate-50/60 rounded-3xl border border-slate-200 shadow-sm group">
                    <p class="text-xs text-slate-500 font-bold uppercase mb-3">মেইন থাম্বনেইল</p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                            class="w-24 h-24 rounded-2xl object-cover border-2 border-white ring-4 ring-blue-600/5 shadow-md group-hover:scale-105 transition-transform">
                        <div class="flex-1 w-full">
                            <input type="file" name="thumbnail"
                                class="text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer w-full transition-all">
                        </div>
                    </div>
                </div>

                {{-- গ্যালারি ইমেজ প্রিভিউ ও নতুন এড --}}
                <div class="p-5 bg-slate-50/60 rounded-3xl border border-slate-200 shadow-sm">
                    <p class="text-xs text-slate-500 font-bold uppercase mb-3">
                        গ্যালারি ইমেজ <span class="text-[10px] text-amber-600 normal-case font-medium">(নতুন দিলে আগের সাথে যোগ হবে)</span>
                    </p>
                    <div class="flex flex-wrap gap-3 mb-4">
                        @if ($product->gallery && count($product->gallery) > 0)
                            @foreach ($product->gallery as $img)
                                <div class="relative group/img">
                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                        class="w-16 h-16 rounded-xl object-cover shadow-sm border border-slate-200 opacity-80 group-hover/img:opacity-100 transition-opacity">
                                </div>
                            @endforeach
                        @else
                            <p class="text-xs text-slate-400 italic">কোনো গ্যালারি ইমেজ নেই</p>
                        @endif
                    </div>
                    <input type="file" name="gallery[]" multiple 
                        class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer w-full transition-all">
                </div>

                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-2xl shadow-md shadow-amber-500/10 transition-all uppercase italic tracking-wider transform hover:scale-[1.005] active:scale-95">
                    আপডেট সেভ করুন
                </button>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editEditor;
        ClassicEditor.create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
        }).then(editor => {
            editEditor = editor;
        });

        document.querySelector('#editForm').addEventListener('submit', function(e) {
            if (editEditor) {
                document.querySelector('#editor').value = editEditor.getData();
            }
        });
    </script>

    <style>
        /* CKEditor লাইট মোড ডিজাইন */
        :root {
            --ck-color-base-background: #ffffff;
            --ck-color-toolbar-background: #f8fafc;
            --ck-color-base-border: #e2e8f0;
            --ck-color-text: #334155;
        }

        .ck-editor__editable {
            min-height: 200px !important;
            background-color: #ffffff !important;
            color: #334155 !important;
            padding: 0 1rem !important;
        }

        .ck-toolbar {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            padding: 0.5rem !important;
        }

        .ck.ck-button {
            color: #64748b !important;
            cursor: pointer;
            border-radius: 8px !important;
        }

        .ck.ck-button:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
        }

        .ck.ck-button.ck-on {
            background: #2563eb !important;
            color: white !important;
        }

        .ck-editor {
            border-radius: 1rem !important;
            overflow: hidden;
        }
    </style>
@endsection