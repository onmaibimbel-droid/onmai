@extends('belajar')

@section('content')

<style>
    /* Hamburger Menu */
    .hamburger-lines span { display: block; width: 24px; height: 3px; margin-bottom: 5px; position: relative; background-color: #374151; border-radius: 3px; z-index: 1; transform-origin: 4px 0px; transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0), background 0.5s cubic-bezier(0.77,0.2,0.05,1.0), opacity 0.55s ease; }
    .hamburger-lines span:first-child { transform-origin: 0% 0%; }
    .hamburger-lines span:nth-last-child(2) { transform-origin: 0% 100%; }

    /* CKEditor Custom Height */
    .ck-editor__editable_inline { min-height: 150px; }
    
</style>

<div class="min-h-screen bg-gray-50 p-4 md:p-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div id="dashboard-toggle" class="toggle lg:hidden cursor-pointer p-2 rounded-md hover:bg-gray-200 transition duration-300">
                <div class="hamburger-lines"><span></span><span></span><span></span></div>
            </div>
            <div>
                <h1 class="text-2xl font-bold font-heading text-gray-800">Tambah Soal</h1>
                <p class="text-xs text-gray-500">Buat pertanyaan baru untuk: <span class="font-bold text-[#ffc800]">{{ $mandiri->nama_mapel ?? 'Mapel' }}</span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-6 w-full md:w-auto justify-end">
            <div class="flex items-center gap-3 pl-0 md:pl-6 md:border-l border-gray-200 shrink-0">
                <div class="text-right hidden sm:block">
                    <span class="block text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <span class="block text-xs text-[#ffc800] font-semibold">Administrator</span>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/imgs/customer01.jpg') }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md">
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto animate-[fadeIn_0.5s_ease-out]">
        
        <div class="mb-6">
            <a href="{{ route('mandiri.show', $mandiri->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-[#ffc800] font-bold transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Soal
            </a>
        </div>

        <form action="{{ route('mapel.store', $mandiri->id) }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-lg border-t-4 border-[#ffc800] overflow-hidden">
                
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 text-[#ffc800] flex items-center justify-center shadow-sm">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Form Input Soal Baru</h2>
                </div>

                <div class="p-8 space-y-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan / Soal</label>
                        <textarea id="editor-pertanyaan" name="pertanyaan" placeholder="Tuliskan soal di sini...">{!! old('pertanyaan') !!}</textarea>
                    </div>

                    <div class="border-t border-gray-100 my-6"></div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-4">Pilihan Jawaban</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">A</span>
                                <textarea id="editor-a" name="a" placeholder="Jawaban A">{!! old('a') !!}</textarea>
                            </div>

                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">B</span>
                                <textarea id="editor-b" name="b" placeholder="Jawaban B">{!! old('b') !!}</textarea>
                            </div>

                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">C</span>
                                <textarea id="editor-c" name="c" placeholder="Jawaban C">{!! old('c') !!}</textarea>
                            </div>

                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">D</span>
                                <textarea id="editor-d" name="d" placeholder="Jawaban D">{!! old('d') !!}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kunci Jawaban</label>

                                <select name="kunci" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-[#ffc800] focus:outline-none">
                                    <option value="">-- Pilih Kunci Jawaban --</option>
                                    <option value="A" {{ old('kunci') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('kunci') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('kunci') == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('kunci') == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pembahasan</label>
                                <textarea id="editor-pembahasan" name="pembahasan" placeholder="Tuliskan pembahasan soal...">{!! old('pembahasan') !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('mandiri.mapel', $mandiri->id) }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition text-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#ffc800] text-white rounded-xl font-bold shadow-lg hover:bg-yellow-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Soal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<script>
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function initEditor(id) {
        const element = document.getElementById(id);
        if (!element) return;

        CKEDITOR.ClassicEditor.create(element, {
            // --- TOOLBAR ---
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'underline', 'subscript', 'superscript', '|',
                    'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|',
                    'insertImage', '|', // TOMBOL UPLOAD
                    'undo', 'redo', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },

            // --- HTML SUPPORT ---
            // Elemen MathML ikut diizinkan supaya rumus dari soal hasil import Word
            // tidak dibuang editor waktu soalnya dibuka untuk diedit.
            htmlSupport: {
                allow: [
                    {
                        name: /^(p|span|strong|em|u|ul|ol|li|table|tbody|thead|tr|td|th|img|sub|sup|br)$/,
                        attributes: true,
                        classes: true,
                        styles: false
                    },
                    {
                        name: /^(math|mrow|mi|mn|mo|ms|mtext|mspace|mfrac|msqrt|mroot|msup|msub|msubsup|munder|mover|munderover|mmultiscripts|mtable|mtr|mtd|mfenced|mstyle|menclose|mpadded|mphantom)$/,
                        attributes: true,
                        classes: true,
                        styles: false
                    }
                ]
            },

            // --- UPLOAD CONFIG ---
            simpleUpload: {
                uploadUrl: "{{ route('mapel.upload') }}",
                headers: { 'X-CSRF-TOKEN': getCsrfToken() }
            },

            // Catatan: blok "pasteFromOffice" yang dulu ada di sini dihapus.
            // Opsi itu milik CKEditor 4; di CKEditor 5 plugin PasteFromOffice
            // tidak menerima konfigurasi apa pun, jadi selama ini diabaikan diam-diam.

            // --- RESIZE CONFIG ---
            image: {
                resizeUnit: "%",
                resizeOptions: [
                    { name: 'resizeImage:original', value: null, label: 'Original' },
                    { name: 'resizeImage:50', value: '50', label: '50%' },
                    { name: 'resizeImage:75', value: '75', label: '75%' }
                ],
                toolbar: [ 'imageResize', '|', 'toggleImageCaption', 'imageTextAlternative' ]
            },

            // --- REMOVE PLUGINS (CRITICAL FOR FREE MODE) ---
            removePlugins: [
                'DocumentOutline', 'TableOfContents', 'Pagination', 'WProofreader', 'MathType',
                'AIAssistant', 'CKBox', 'CKFinder', 'EasyImage', 'ExportPdf', 'ExportWord', 
                'FormatPainter', 'ImportWord', 'MultiLevelList', 'PasteFromOfficeEnhanced', 
                'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges', 
                'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 
                'TrackChangesData', 'RevisionHistory', 'SlashCommand', 'Template', 'TextPartLanguage', 'Toc'
            ]
        })
        .then(editor => {
            // Set data old() if exists (untuk repopulate saat validasi gagal)
            const oldData = element.value;
            if(oldData) editor.setData(oldData);
        })
        .catch(error => {
            console.error("Gagal init editor " + id, error);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initEditor('editor-pertanyaan');
        initEditor('editor-a');
        initEditor('editor-b');
        initEditor('editor-c');
        initEditor('editor-d');
        initEditor('editor-pembahasan');
    });


</script>

@endsection