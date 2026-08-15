@extends('tryout.index')

@section('content')

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 animate-[fadeIn_0.5s_ease-out]">
        
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-[#ffc800] relative">
            
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-yellow-50 rounded-full blur-3xl -z-10 opacity-50"></div>

            <div class="p-8 text-center relative z-10">
                
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-yellow-100 to-white rounded-full flex items-center justify-center mb-6 shadow-inner border-4 border-white ring-1 ring-yellow-100">
                    <i class="fas fa-trophy text-4xl text-[#ffc800] drop-shadow-sm"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 font-heading mb-1">Hasil Ujian</h2>
                <p class="text-sm font-medium text-gray-500 mb-8">{{ $judulutbk->judul }}</p>

                <div class="mb-8 relative">
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nilai Akhir Anda</span>
                    <div class="relative inline-block px-6 py-2">
                        <span class="text-6xl font-extrabold text-gray-800 tracking-tighter">{{ $nilai->skor }}</span>
                        <span class="text-xl text-gray-400 font-medium">/700</span>
                        
                        <div class="absolute -top-2 -right-2 w-3 h-3 bg-[#ffc800] rounded-full animate-ping"></div>
                        <div class="absolute -bottom-1 -left-2 w-2 h-2 bg-blue-400 rounded-full"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 mb-8">
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 hover:border-[#ffc800]/30 transition duration-300">
                        <div class="text-[#ffc800] mb-2"><i class="fas fa-book"></i></div>
                        <span class="block text-xs text-gray-400 uppercase tracking-wide">PREDIKSI SOAL TRYOUT UTBK</span>
                        <span class="font-bold text-gray-700 text-sm truncate block">{{ $judulutbk->tahun }}</span>
                    </div>
                </div>

                <div class="mb-8">
                    @if($nilai->selesai)
                        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-green-50 text-green-600 text-sm font-bold border border-green-100 shadow-sm">
                            <i class="fas fa-check-circle"></i> Selesai Dikerjakan
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-yellow-50 text-yellow-600 text-sm font-bold border border-yellow-100 shadow-sm">
                            <i class="fas fa-exclamation-circle"></i> Belum Selesai
                        </div>
                    @endif
                </div>

                <a href="{{ route('to-utbk.to_utbk') }}" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl text-white bg-gray-900 hover:bg-gray-800 font-bold shadow-lg transition-all duration-300 hover:scale-[1.02] overflow-hidden">
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-[150%] skew-x-12 transition-transform duration-1000 group-hover:translate-x-[150%] ease-in-out"></div>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Ujian
                    </span>
                </a>

            </div>
        </div>
        
        <div class="text-center text-xs text-gray-400 font-medium">
            &copy; {{ date('Y') }} ONMAI Learning System
        </div>
    </div>
</div>

@endsection