@extends('tryout.index')

@section('content')

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl animate-[fadeIn_0.5s_ease-out]">
        
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-2xl font-bold font-heading text-gray-800">Persiapan Ujian</h1>
            <p class="text-gray-500 text-sm">Silakan periksa detail ujian dan baca petunjuk sebelum memulai.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-lg border-t-4 border-[#ffc800] overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Deskripsi Ujian</h2>
                    </div>
                    <div class="p-6 text-gray-600 leading-relaxed text-sm">
                        @if()
                            <p></p>
                        @else
                            <p class="italic text-gray-400">Tidak ada deskripsi khusus untuk ujian ini.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 text-[#ffc800] flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-lg"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Petunjuk Pengerjaan</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-sm text-gray-600">Berdoalah sebelum memulai mengerjakan soal.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-wifi text-blue-500 mt-1"></i>
                                <span class="text-sm text-gray-600">Pastikan koneksi internet Anda stabil selama ujian berlangsung.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-clock text-red-500 mt-1"></i>
                                <span class="text-sm text-gray-600">Perhatikan waktu ujian yang berjalan. Ujian akan tertutup otomatis jika waktu habis.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-ban text-red-500 mt-1"></i>
                                <span class="text-sm text-gray-600 font-bold">Dilarang membuka tab baru atau berpindah aplikasi (Sistem mendeteksi kecurangan).</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden sticky top-24">
                    
                    <div class="bg-gray-900 p-6 text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#ffc800]/20 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
                        
                        <img src="{{ asset('assets/imgs/customer01.jpg') }}" alt="User" class="w-20 h-20 rounded-full border-4 border-white shadow-md mx-auto mb-3 relative z-10">
                        <h3 class="text-white font-bold text-lg relative z-10">{{ Auth::user()->name }}</h3>
                        <span class="inline-block px-3 py-1 bg-[#ffc800] text-gray-900 text-xs font-bold rounded-full mt-1 relative z-10">
                            Peserta Ujian
                        </span>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-xs text-gray-400 uppercase font-bold">Kelas</span>
                            <span class="text-sm font-bold text-gray-800"></span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-xs text-gray-400 uppercase font-bold">Mata Pelajaran</span>
                            <span class="text-sm font-bold text-gray-800"></span>
                        </div>
                        
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-xs text-gray-400 uppercase font-bold">Jumlah Soal</span>
                            <span class="text-sm font-bold text-blue-600">Butir</span>
                        </div>
                        
                    </div>

                    <div class="p-6 pt-0">
                        <a href="" 
                           class="group relative w-full flex justify-center py-4 px-4 border border-transparent rounded-xl text-white bg-[#ffc800] hover:bg-yellow-500 font-bold shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-yellow-500/40 overflow-hidden">
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-[150%] skew-x-12 transition-transform duration-1000 group-hover:translate-x-[150%] ease-in-out"></div>
                            <span class="flex items-center gap-2 relative z-10 text-gray-900 uppercase tracking-wider">
                                <i class="fas fa-play"></i> Mulai Kerjakan
                            </span>
                        </a>
                        
                        <div class="mt-4 text-center">
                            <a href="" class="text-gray-400 text-xs hover:text-gray-600 transition">Batal & Kembali</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection