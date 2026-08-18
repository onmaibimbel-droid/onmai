@extends('belajar')

@section('content')

<style>
    .ck-editor__editable_inline { min-height: 150px; }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    
    <div class="max-w-5xl mx-auto animate-[fadeIn_0.5s_ease-out]">
        
        <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="{{ route('ujian.show', $ujian->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-[#ffc800] font-bold transition group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Detail Ujian
            </a>
            <div class="text-sm text-gray-500 font-semibold">
                Ujian: <span class="text-gray-800">{{ $ujian->nama_ujian }}</span>
            </div>
        </div>

        <form action="{{ route('soal.store', $ujian->id) }}" method="POST" data-encode-html>
            @csrf

            <div class="bg-white rounded-2xl shadow-lg border-t-4 border-[#ffc800] overflow-hidden">
                
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 text-[#ffc800] flex items-center justify-center shadow-sm">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Tambah Soal Baru</h2>
                        <p class="text-xs text-gray-500">Silakan isi pertanyaan dan pilihan jawaban</p>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan / Soal</label>
                        <textarea id="editor-pertanyaan" name="pertanyaan">{{ old('pertanyaan') }}</textarea>
                        @error('pertanyaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="border-t border-gray-100 my-6"></div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-4">Pilihan Jawaban</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">A</span>
                                <textarea id="editor-a" name="opsi_a">{{ old('opsi_a') }}</textarea>
                                @error('opsi_a') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">B</span>
                                <textarea id="editor-b" name="opsi_b">{{ old('opsi_b') }}</textarea>
                                @error('opsi_b') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">C</span>
                                <textarea id="editor-c" name="opsi_c">{{ old('opsi_c') }}</textarea>
                                @error('opsi_c') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="relative group">
                                <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-[#ffc800] text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">D</span>
                                <textarea id="editor-d" name="opsi_d">{{ old('opsi_d') }}</textarea>
                                @error('opsi_d') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Kunci Jawaban Benar</label>
                        <select name="jawaban_benar" class="w-full md:w-1/3 px-4 py-3 rounded-xl border border-gray-300 focus:border-[#ffc800] outline-none bg-white font-bold text-gray-700 shadow-sm cursor-pointer transition focus:ring-2 focus:ring-yellow-400/20">
                            <option value="">-- Pilih Jawaban Benar --</option>
                            <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>Jawaban A</option>
                            <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>Jawaban B</option>
                            <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>Jawaban C</option>
                            <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>Jawaban D</option>
                        </select>
                        @error('jawaban_benar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('ujian.show', $ujian->id) }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition text-sm">
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

<script>
const editors = {};

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
            allow: [
                {
                    name: /^(p|b|i|strong|em|ul|ol|li|br|sub|sup|table|tr|td|th|img)$/,
                    attributes: ['src', 'alt'],
                    classes: false,
                    styles: false
                }
            ]
        },

        simpleUpload: {
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

        removePlugins: [
            'DocumentOutline','TableOfContents','Pagination','WProofreader','MathType',
            'AIAssistant','CKBox','CKFinder','EasyImage','ExportPdf','ExportWord',
            'FormatPainter','ImportWord','MultiLevelList',
            'RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges',
            'RealTimeCollaborativeRevisionHistory','PresenceList','Comments','TrackChanges',
            'TrackChangesData','RevisionHistory','SlashCommand','Template','TextPartLanguage','Toc'
        ]

    })
    .then(editor => {
        editors[id] = editor;
    })
    .catch(error => {
        console.error("Gagal init editor " + id, error);
    });
}


// 🔥 INIT SEMUA EDITOR
document.addEventListener("DOMContentLoaded", function() {
    initEditor('editor-pertanyaan');
    initEditor('editor-a');
    initEditor('editor-b');
    initEditor('editor-c');
    initEditor('editor-d');
});


// 🔥 SANITIZE WORD
function sanitizeWordHtml(html) {
    const temp = document.createElement('div');
    temp.innerHTML = html;

    temp.querySelectorAll('*').forEach(el => {
        el.removeAttribute('style');
        el.removeAttribute('class');
        el.removeAttribute('lang');
        el.removeAttribute('align');

        if (el.tagName.toLowerCase() === 'o:p') {
            el.remove();
        }
    });

    return temp.innerHTML;
}


// 🔥 PALING PENTING: BERSIHKAN SAAT SUBMIT
document.querySelector('form').addEventListener('submit', function (e) {

    Object.keys(editors).forEach(id => {
        let data = editors[id].getData();

        // ❗ HAPUS BASE64 IMAGE (INI PENYEBAB ERROR KAMU)
        data = data.replace(/<img[^>]+src="data:image[^"]+"[^>]*>/g, '');

        // bersihkan HTML Word
        data = sanitizeWordHtml(data);

        // set kembali ke editor
        editors[id].setData(data);
    });

});
</script>

@include('partials.encode-html-form')

@endsection
