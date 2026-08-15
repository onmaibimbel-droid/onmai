<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Tambah Soal | ONMAI Admin</title>
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Mendefinisikan warna custom 'darker' yang Anda pakai di sidebar
                        darker: '#1a202c',
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .ck-editor__editable_inline { min-height: 150px; }
    </style>
</head>
<body class="bg-gray-100 font-body text-gray-800 flex h-screen overflow-hidden relative">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden transition-opacity opacity-0 lg:hidden"></div>

    <aside id="sidebar" class="w-64 bg-darker text-white fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 flex flex-col shadow-2xl z-30 transition-transform duration-300 ease-in-out" style="background-color: #1a202c;">
        <div class="h-20 flex items-center justify-center border-b border-gray-700 shrink-0">
            <a href="#" class="flex items-center gap-2 text-xl font-bold font-heading text-yellow-400 tracking-widest">
                <i class="fas fa-crown"></i> ADMIN
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-yellow-400 transition">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.index') }}" class="flex items-center gap-3 px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-yellow-400 transition">
                        <i class="fas fa-user-tie w-5 text-center"></i> Data Guru
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-yellow-400 transition">
                        <i class="fas fa-user-graduate w-5 text-center"></i> Data Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ujian.index') }}" class="flex items-center gap-3 px-6 py-3 bg-gray-800 border-r-4 border-yellow-400 text-white transition">
                        <i class="fas fa-file-alt w-5 text-center"></i> Bank Soal
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="p-4 border-t border-gray-700 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600/20 text-red-500 hover:bg-red-600 hover:text-white rounded-lg transition duration-300 font-bold text-sm">
                    <i class="fas fa-sign-out-alt"></i> LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-4 md:px-8 border-b-4 border-yellow-400 z-10 shrink-0">
            <button id="sidebar-toggle" class="md:hidden text-gray-600 text-2xl focus:outline-none hover:text-yellow-400 transition">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="text-xl font-bold font-heading text-gray-800">Tambah Soal Ujian</h2>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="block text-sm font-bold text-gray-800">{{ Auth::user()->name }}</span>
                    <span class="block text-xs text-yellow-500 font-bold uppercase tracking-wider">Super Admin</span>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random" alt="Profile" class="w-10 h-10 rounded-full border-2 border-gray-200 shadow-sm object-cover">
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-8">
            
            <div class="max-w-5xl mx-auto animate-[fadeIn_0.5s_ease-out]">
                
                <div class="mb-6">
                    <a href="{{ route('admin.ujian.show', $ujian->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-yellow-500 font-bold transition">
                        <i class="fas fa-arrow-left"></i> Kembali ke Detail Ujian
                    </a>
                </div>

                <form action="{{ route('admin.soal.store', $ujian->id) }}" method="POST" data-encode-html>
                    @csrf
        
                    <div class="bg-white rounded-2xl shadow-lg border-t-4 border-yellow-400 overflow-hidden">
                        
                        <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-500 flex items-center justify-center shadow-sm">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Input Soal Baru</h2>
                                <p class="text-xs text-gray-500">Ujian: {{ $ujian->nama_ujian }}</p>
                            </div>
                        </div>
                
                        <div class="p-8 space-y-8">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan / Soal</label>
                                <textarea id="editor-pertanyaan" name="pertanyaan">{!! old('pertanyaan') !!}</textarea>
                                @error('pertanyaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                
                            <div class="border-t border-gray-100 my-6"></div>
                
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-4">Pilihan Jawaban</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    
                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-yellow-400 text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">A</span>
                                        <textarea id="editor-a" name="opsi_a">{!! old('opsi_a') !!}</textarea>
                                        @error('opsi_a') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                
                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-yellow-400 text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">B</span>
                                        <textarea id="editor-b" name="opsi_b">{!! old('opsi_b') !!}</textarea>
                                        @error('opsi_b') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                
                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-yellow-400 text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">C</span>
                                        <textarea id="editor-c" name="opsi_c">{!! old('opsi_c') !!}</textarea>
                                        @error('opsi_c') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                
                                    <div class="relative group">
                                        <span class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-yellow-400 text-white font-bold flex items-center justify-center shadow-md z-10 border-2 border-white">D</span>
                                        <textarea id="editor-d" name="opsi_d">{!! old('opsi_d') !!}</textarea>
                                        @error('opsi_d') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                
                                </div>
                            </div>
        
                            <div class="mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                                <label class="block text-sm font-bold text-gray-800 mb-2">Kunci Jawaban Benar</label>
                                <select name="jawaban_benar" class="w-full md:w-1/3 px-4 py-3 rounded-xl border border-gray-300 focus:border-yellow-400 outline-none bg-white font-bold text-gray-700 shadow-sm cursor-pointer">
                                    <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>Jawaban A</option>
                                    <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>Jawaban B</option>
                                    <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>Jawaban C</option>
                                    <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>Jawaban D</option>
                                </select>
                                @error('jawaban_benar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                
                        </div>
                
                        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('admin.ujian.show', $ujian->id) }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-yellow-400 text-white rounded-xl font-bold shadow-lg hover:bg-yellow-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Soal
                            </button>
                        </div>
                
                    </div>
                </form>
                </div>

        </main>
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
                htmlSupport: {
                    allow: [{ name: /.*/, attributes: true, classes: true, styles: true }]
                },
                
                // --- UPLOAD CONFIG (ROUTE ADMIN) ---
                simpleUpload: {
                    uploadUrl: "{{ route('admin.soal.upload') }}", // Route Khusus Admin
                    headers: { 'X-CSRF-TOKEN': getCsrfToken() }
                },
                
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
                
                // --- HAPUS PLUGIN PREMIUM ---
                removePlugins: [
                    'DocumentOutline', 'TableOfContents', 'Pagination', 'WProofreader', 'MathType',
                    'AIAssistant', 'CKBox', 'CKFinder', 'EasyImage', 'ExportPdf', 'ExportWord', 
                    'FormatPainter', 'ImportWord', 'MultiLevelList', 'PasteFromOfficeEnhanced', 
                    'PasteFromOffice', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges', 
                    'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 
                    'TrackChangesData', 'RevisionHistory', 'SlashCommand', 'Template', 'TextPartLanguage', 'Toc'
                ]
            })
            .then(editor => {
                // Editor berhasil dimuat
            })
            .catch(error => {
                console.error("Gagal init editor " + id, error);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi 5 Editor
            initEditor('editor-pertanyaan');
            initEditor('editor-a');
            initEditor('editor-b');
            initEditor('editor-c');
            initEditor('editor-d');

            // Sidebar Toggle Logic
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                    setTimeout(() => { overlay.classList.remove('opacity-0'); overlay.classList.add('opacity-100'); }, 10);
                } else {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.remove('opacity-100');
                    overlay.classList.add('opacity-0');
                    setTimeout(() => { overlay.classList.add('hidden'); }, 300);
                }
            }
            if (toggleBtn) toggleBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleSidebar(); });
            if (overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
    @include('partials.encode-html-form')
</body>
</html>
