@extends('belajar')

@section('content')

<style>
    /* Styling Hamburger Menu */
    .hamburger-lines span {
        display: block; width: 24px; height: 3px; margin-bottom: 5px;
        position: relative; background-color: #374151; border-radius: 3px;
        z-index: 1; transform-origin: 4px 0px;
        transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
                    background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
                    opacity 0.55s ease;
    }
    .hamburger-lines span:first-child { transform-origin: 0% 0%; }
    .hamburger-lines span:nth-last-child(2) { transform-origin: 0% 100%; }

    /* Custom Style CKEditor */
    .ck-editor__editable_inline {
        min-height: 150px;
    }
</style>

<div class="min-h-screen bg-gray-50 p-4 md:p-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div id="dashboard-toggle" class="toggle lg:hidden cursor-pointer p-2 rounded-md hover:bg-gray-200 transition duration-300">
                <div class="hamburger-lines"><span></span><span></span><span></span></div>
            </div>
            <div>
                <h1 class="text-2xl font-bold font-heading text-gray-800">Edit Soal</h1>
                <p class="text-xs text-gray-500">Perbarui pertanyaan dan pilihan jawaban</p>
            </div>
        </div>
        <div class="flex items-center gap-6 w-full md:w-auto justify-end">
            <div class="text-right hidden sm:block">
                <span class="block text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                <span class="block text-xs text-[#ffc800] font-semibold">Administrator</span>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto animate-[fadeIn_0.5s_ease-out]">
        
        <div class="mb-6">
            <a href="{{ route('utbk.show', $judulutbk->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-[#ffc800] font-bold transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Soal
            </a>
        </div>

        <form action="{{ route('utbk.update', [$judulutbk->id, $utbk->id]) }}" method="POST">
            @csrf
            @method('PUT')
        
            <div class="bg-white rounded-2xl shadow-lg border-t-4 border-[#ffc800] overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center gap-3">
                    <h2 class="text-lg font-bold text-gray-800">Form Editor Soal</h2>
                </div>
        
                <div class="p-8 space-y-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan / Soal</label>
                        <textarea id="editor-pertanyaan" name="pertanyaan">{!! $utbk->pertanyaan !!}</textarea>
                    </div>
        
                    <div class="border-t border-gray-100 my-6"></div>
        
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-4">Pilihan Jawaban</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="relative">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">A</span>
                                <textarea id="editor-opsi_a" name="opsi_a">{!! $utbk->opsi_a !!}</textarea>
                            </div>
        
                            <div class="relative">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">B</span>
                                <textarea id="editor-opsi_b" name="opsi_b">{!! $utbk->opsi_b !!}</textarea>
                            </div>
        
                            <div class="relative">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">C</span>
                                <textarea id="editor-opsi_c" name="opsi_c">{!! $utbk->opsi_c !!}</textarea>
                            </div>
        
                            <div class="relative">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">D</span>
                                <textarea id="editor-opsi_d" name="opsi_d">{!! $utbk->opsi_d !!}</textarea>
                            </div>
                            <div class="relative">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">E</span>
                                <textarea id="editor-opsi_e" name="opsi_e">{!! $utbk->opsi_e !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">

                    <div class="mt-6">
                         <label class="block text-sm font-bold text-gray-700 mb-2">Kunci Jawaban</label>
                         <select name="jawaban_benar" class="border p-2 rounded w-full md:w-1/4 bg-gray-50">
                             <option value="A" {{ $utbk->jawaban_benar == 'A' ? 'selected' : '' }}>A</option>
                             <option value="B" {{ $utbk->jawaban_benar == 'B' ? 'selected' : '' }}>B</option>
                             <option value="C" {{ $utbk->jawaban_benar == 'C' ? 'selected' : '' }}>C</option>
                             <option value="D" {{ $utbk->jawaban_benar == 'D' ? 'selected' : '' }}>D</option>
                             <option value="E" {{ $utbk->jawaban_benar == 'E' ? 'selected' : '' }}>E</option>
                         </select>
                    </div>
        
                </div>
        
                <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('utbk.show', $utbk->id) }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition text-sm">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-[#ffc800] text-white rounded-xl font-bold shadow-lg hover:bg-yellow-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
        
            </div>
        </form>
    </div>

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>

<script>
    // 1. SAFE CSRF TOKEN RETRIEVAL
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
                    'insertImage', '|', 
                    'undo', 'redo', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },

            // --- HTML SUPPORT (To show images visually) ---
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            },

            // --- UPLOAD CONFIGURATION ---
            simpleUpload: {
                uploadUrl: "{{ route('mapel.upload') }}",
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            },

            // --- IMAGE RESIZE ---
            image: {
                resizeUnit: "%",
                resizeOptions: [
                    { name: 'resizeImage:original', value: null, label: 'Original' },
                    { name: 'resizeImage:50', value: '50', label: '50%' },
                    { name: 'resizeImage:75', value: '75', label: '75%' }
                ],
                toolbar: [
                    'imageResize', '|', 'toggleImageCaption', 'imageTextAlternative'
                ]
            },

            // --- REMOVE PLUGINS (CRITICAL STEP) ---
            // We must remove ALL premium/paid plugins to stop the "invalid-license-key" error
            removePlugins: [
                // 1. Plugins that cause layout errors
                'DocumentOutline', 
                'TableOfContents', 
                'Pagination',
                'WProofreader',
                'MathType',
                
                // 2. PREMIUM PLUGINS (These cause the "License Key" errors)
                'AIAssistant',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'ExportPdf',
                'ExportWord',
                'FormatPainter',
                'ImportWord',
                'MultiLevelList',
                'PasteFromOfficeEnhanced',
                'PasteFromOffice',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'SlashCommand',
                'Template',
                'TextPartLanguage',
                'Toc'
            ]
        })
        .then(editor => {
            console.log("Success load editor: " + id);
            editor.ui.view.editable.element.style.minHeight = "150px";
        })
        .catch(error => {
            console.error("Failed to initialize editor " + id, error);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initEditor('editor-pertanyaan');
        initEditor('editor-opsi_a');
        initEditor('editor-opsi_b');
        initEditor('editor-opsi_c');
        initEditor('editor-opsi_d');
        initEditor('editor-opsi_e');
        initEditor('jawaban_benar');
    });
</script>

@endsection