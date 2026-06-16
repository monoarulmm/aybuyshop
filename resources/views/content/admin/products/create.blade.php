@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50/50 py-10 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-sm">
            <h2 class="text-2xl font-black text-slate-800 mb-6 italic uppercase tracking-tighter border-l-4 border-blue-600 pl-4">
                নতুন প্রোডাক্ট আপলোড
            </h2>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm"
                class="space-y-6">
                @csrf

                {{-- নাম ও ক্যাটাগরি --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">প্রোডাক্টের নাম</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 focus:bg-white focus:border-blue-600 outline-none transition-all"
                            placeholder="যেমন: スマート ওয়াচ" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">ক্যাটাগরি</label>
                        <div class="relative">
                            <select name="category_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 focus:bg-white focus:border-blue-600 outline-none appearance-none cursor-pointer font-medium">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">মূল্য (টাকা)</label>
                        <input type="number" name="price" value="{{ old('price') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 focus:bg-white focus:border-blue-600 outline-none transition-all"
                            placeholder="500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">স্টক পরিমাণ</label>
                        <input type="number" name="stock" value="{{ old('stock') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 focus:bg-white focus:border-blue-600 outline-none transition-all"
                            placeholder="50" required>
                    </div>
                </div>

                {{-- CKEditor ডেসক্রিপশন --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase ml-1">বিবরণ (Description)</label>
                    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                        <textarea name="description" id="editor">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- ইমেজ আপলোড --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-50/50 p-5 rounded-3xl border border-dashed border-slate-300 hover:border-blue-500 hover:bg-white transition-all group">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3 text-center group-hover:text-blue-600 transition-colors">
                            মেইন থাম্বনেইল (Max 5MB)
                        </label>
                        <input type="file" name="thumbnail" class="text-sm text-slate-500 w-full cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all" required>
                    </div>

                    <div class="bg-slate-50/50 p-5 rounded-3xl border border-dashed border-slate-300 hover:border-amber-500 hover:bg-white transition-all group">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3 text-center group-hover:text-amber-600 transition-colors">
                            গ্যালারি ইমেজ (একাধিক)
                        </label>
                        <input type="file" name="gallery[]" multiple class="text-sm text-slate-500 w-full cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100 transition-all">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-md shadow-blue-600/10 transition-all transform hover:scale-[1.005] active:scale-95 uppercase tracking-widest">
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
                document.querySelector('#editor').value = data; // টেক্সট এরিয়াতে ডাটা পুশ করা
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
            min-height: 250px !important;
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