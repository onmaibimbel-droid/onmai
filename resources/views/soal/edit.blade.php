@extends('belajar')

@section('content')

<style>
    .ck-editor__editable_inline { min-height: 150px; }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 flex font-body text-gray-800">

    <aside class="w-64 bg-white hidden md:flex flex-col shadow-xl fixed inset-y-0 z-20">
        <div class="h-20 flex items-center justify-center border-b border-gray-100">
            <a href="#" class="flex items-center gap-2 text-xl font-bold text-[#ffc800] tracking-widest">
                <i class="fas fa-graduation-cap"></i> GURU
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-gray-500 hover:bg-yellow-50 hover:text-[#ffc800] transition font-bold">
                        <i class="fas fa-home w-5 text-center"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('mandiri.materi') }}" class="flex items-center gap-3 px-6 py-3 text-gray-500 hover:bg-yellow-50 hover:text-[#ffc800] transition font-bold">
                        <i class="fas fa-book-open w-5 text-center"></i> Bank Soal
                    </a>
                </li>
                <li>
                    <a href="{{ route('ujian.index') }}" class="flex items-center gap-3 px-6 py-3 bg-[#ffc800] text-white shadow-lg shadow-yellow-200 rounded-r-full mr-4 transition font-bold">
                        <i class="fas fa-file-alt w-5 text-center"></i> Ujian Sekolah
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition duration-300 font-bold text-sm">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col md:pl-64 transition-all duration-300">
        
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-4 md:px-8 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-500 text-2xl focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="text-xl font-bold text-gray-800">Edit Soal</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="block text-sm font-bold text-gray-800">{{ Auth::user()->name ?? 'Guru' }}</span>
                    <span class="block text-xs text-[#ffc800] font-bold uppercase">Pengajar</span>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Guru' }}&background=random" class="w-10 h-10 rounded-full border-2 border-gray-100">
            </div>
        </header>

        <main class="p-4 md:p-8">
            <div class="max-w-5xl mx-auto animate-[fadeIn_0.5s_ease-out]">
                
                <div class="mb-6">
                    <a href="{{ route('ujian.show', $ujian->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-[#ffc800] font-bold transition group">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('soal.update', [$ujian->id, $soal->id]) }}" method="POST" data-encode-html>
                    @csrf
                    @method('PUT')

                    <div class="bg-white rounded-2xl shadow-lg border-t-4 border-[#ffc800] overflow-hidden">
                        
                        <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-[#ffc800] flex items-center justify-center shadow-sm">
                                <i class="fas fa-pencil-alt"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Form Edit Soal</h2>
                                <p class="text-xs text-gray-500">Ujian: {{ $ujian->nama_ujian }}</p>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan / Soal</label>
                                <textarea id="editor-pertanyaan" name="pertanyaan">{!! old('pertanyaan', $soal->pertanyaan) !!}</textarea>
                                @error('pertanyaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="border-t border-gray-100 my-6"></div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-4">Pilihan Jawaban</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    
                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">A</span>
                                        <textarea id="editor-a" name="opsi_a">{!! old('opsi_a', $soal->opsi_a) !!}</textarea>
                                    </div>

                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">B</span>
                                        <textarea id="editor-b" name="opsi_b">{!! old('opsi_b', $soal->opsi_b) !!}</textarea>
                                    </div>

                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">C</span>
                                        <textarea id="editor-c" name="opsi_c">{!! old('opsi_c', $soal->opsi_c) !!}</textarea>
                                    </div>

                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">D</span>
                                        <textarea id="editor-d" name="opsi_d">{!! old('opsi_d', $soal->opsi_d) !!}</textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                                <label class="block text-sm font-bold text-gray-800 mb-2">Kunci Jawaban Benar</label>
                                <select name="jawaban_benar" class="w-full md:w-1/3 px-4 py-3 rounded-xl border border-gray-300 focus:border-[#ffc800] outline-none bg-white font-bold text-gray-700 shadow-sm cursor-pointer transition">
                                    <option value="A" {{ old('jawaban_benar', $soal->jawaban_benar) == 'A' ? 'selected' : '' }}>Jawaban A</option>
                                    <option value="B" {{ old('jawaban_benar', $soal->jawaban_benar) == 'B' ? 'selected' : '' }}>Jawaban B</option>
                                    <option value="C" {{ old('jawaban_benar', $soal->jawaban_benar) == 'C' ? 'selected' : '' }}>Jawaban C</option>
                                    <option value="D" {{ old('jawaban_benar', $soal->jawaban_benar) == 'D' ? 'selected' : '' }}>Jawaban D</option>
                                </select>
                            </div>

                        </div>

                        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('ujian.show', $ujian->id) }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-[#ffc800] text-white rounded-xl font-bold shadow-lg hover:bg-yellow-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                                <i class="fas fa-save"></i> Update Soal
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>

<script>
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function initEditor(id) {
        const element = document.getElementById(id);
        if (!element) return;

        CKEDITOR.ClassicEditor.create(element, {
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'underline', 'subscript', 'superscript', '|',
                    'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|',
                    'insertImage', '|', 
                    'undo', 'redo', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            htmlSupport: {
                allow: [{ name: /.*/, attributes: true, classes: true, styles: true }]
            },
            simpleUpload: {
                // Pastikan route upload benar sesuai dengan route di web.php
                uploadUrl: "{{ route('soal.upload') }}", 
                headers: { 'X-CSRF-TOKEN': getCsrfToken() }
            },
            image: {
                resizeUnit: "%",
                resizeOptions: [
                    { name: 'resizeImage:original', value: null, label: 'Original' },
                    { name: 'resizeImage:50', value: '50', label: '50%' },
                    { name: 'resizeImage:75', value: '75', label: '75%' }
                ],
                toolbar: [ 'imageResize', '|', 'toggleImageCaption', 'imageTextAlternative' ]
            },
            // Menghapus plugin berbayar agar tidak muncul error lisensi
            removePlugins: [
                'DocumentOutline', 'TableOfContents', 'Pagination', 'WProofreader', 'MathType',
                'AIAssistant', 'CKBox', 'CKFinder', 'EasyImage', 'ExportPdf', 'ExportWord', 
                'FormatPainter', 'ImportWord', 'MultiLevelList', 'PasteFromOfficeEnhanced', 
                'PasteFromOffice', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges', 
                'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 
                'TrackChangesData', 'RevisionHistory', 'SlashCommand', 'Template', 'TextPartLanguage', 'Toc'
            ]
        }).catch(error => {
            console.error("Gagal init editor " + id, error);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initEditor('editor-pertanyaan');
        initEditor('editor-a');
        initEditor('editor-b');
        initEditor('editor-c');
        initEditor('editor-d');
    });
</script>

@include('partials.encode-html-form')

@endsection
