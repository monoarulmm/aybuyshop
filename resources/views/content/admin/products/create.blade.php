@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="bg-[#111421] rounded-[2.5rem] p-8 border border-gray-800 shadow-2xl">
            <h2
                class="text-2xl font-black text-white mb-6 italic uppercase tracking-tighter border-l-4 border-blue-600 pl-4">
                নতুন প্রোডাক্ট আপলোড
            </h2>


            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm"
                class="space-y-6">
                @csrf

                {{-- নাম ও ক্যাটাগরি --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">প্রোডাক্টের নাম</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none transition-all"
                            placeholder="যেমন: স্মার্ট ওয়াচ" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">ক্যাটাগরি</label>
                        <select name="category_id"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none appearance-none cursor-pointer">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- মূল্য ও স্টক --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">মূল্য (টাকা)</label>
                        <input type="number" name="price" value="{{ old('price') }}"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none"
                            placeholder="500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">স্টক পরিমাণ</label>
                        <input type="number" name="stock" value="{{ old('stock') }}"
                            class="w-full bg-[#0a0c14] border border-gray-800 rounded-2xl p-4 text-white focus:border-blue-600 outline-none"
                            placeholder="50" required>
                    </div>
                </div>

                {{-- CKEditor ডেসক্রিপশন --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase ml-1">বিবরণ (Description)</label>
                    <div class="rounded-2xl overflow-hidden border border-gray-800">
                        <textarea name="description" id="editor">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- ইমেজ আপলোড --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="bg-[#0a0c14] p-5 rounded-3xl border border-dashed border-gray-700 hover:border-blue-500 transition-all">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-3 text-center">মেইন থাম্বনেইল (Max
                            5MB)</label>
                        <input type="file" name="thumbnail" class="text-sm text-gray-400 w-full cursor-pointer" required>
                    </div>

                    <div
                        class="bg-[#0a0c14] p-5 rounded-3xl border border-dashed border-gray-700 hover:border-yellow-500 transition-all">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-3 text-center">গ্যালারি ইমেজ
                            (একাধিক)</label>
                        <input type="file" name="gallery[]" multiple class="text-sm text-gray-400 w-full cursor-pointer">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl transition-all transform hover:scale-[1.01] active:scale-95 uppercase tracking-widest">
                    প্রোডাক্ট পাবলিশ করুন
                </button>
            </form>
        </div>
    </div>

    {{-- CKEditor Scripts --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let productEditor;

        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote',
                    'undo', 'redo'
                ],
            })
            .then(editor => {
                productEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        // ডেসক্রিপশন এরর সমাধানের জন্য এই অংশটি মাস্ট!
        document.querySelector('#productForm').addEventListener('submit', function(e) {
            if (productEditor) {
                const data = productEditor.getData();
                document.querySelector('#editor').value = data; // টেক্সট এরিয়াতে ডাটা পুশ করা
            }
        });
    </script>

    <style>
        /* CKEditor ডার্ক মোড ডিজাইন */
        :root {
            --ck-color-base-background: #0a0c14;
            --ck-color-toolbar-background: #161b2c;
            --ck-color-base-border: #1f2937;
            --ck-color-text: #ffffff;
        }

        .ck-editor__editable {
            min-height: 250px !important;
            background-color: #0a0c14 !important;
            color: white !important;
        }

        .ck-toolbar {
            background-color: #161b2c !important;
            border-color: #1f2937 !important;
        }

        .ck.ck-button {
            color: #9ca3af !important;
            cursor: pointer;
        }

        .ck.ck-button:hover {
            background: #1f2937 !important;
        }

        .ck.ck-button.ck-on {
            background: #2563eb !important;
            color: white !important;
        }
    </style>
@endsection
