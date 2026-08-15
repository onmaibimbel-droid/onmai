@extends('tryout.index')

@section('content')

<style>
    /* Sembunyikan Radio Button Asli */
    .custom-radio { appearance: none; -webkit-appearance: none; }
    
    /* Style saat opsi dipilih */
    .custom-radio:checked + div {
        border-color: #ffc800;
        background-color: #fffbeb; /* yellow-50 */
        box-shadow: 0 4px 6px -1px rgba(255, 200, 0, 0.1), 0 2px 4px -1px rgba(255, 200, 0, 0.06);
    }
    .custom-radio:checked + div .option-label {
        background-color: #ffc800;
        color: white;
        border-color: #ffc800;
    }
</style>

<div class="min-h-screen pb-12">

    <div class="lg:hidden bg-gray-900 text-white p-4 rounded-xl shadow-lg mb-6 sticky top-20 z-30 border-b-4 border-[#ffc800] flex justify-between items-center">
        <div>
            <span class="text-xs text-gray-400 uppercase tracking-widest">Soal Nomor</span>
            <h2 class="text-2xl font-bold text-[#ffc800]">{{ session('soal_index') + 1 }}</h2>
        </div>
        <div class="text-right">
            <span class="text-xs text-gray-400"><i class="far fa-clock"></i> Sisa Waktu</span>
            <p class="font-mono font-bold text-lg">01:45:00</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 animate-[fadeIn_0.5s_ease-out]">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
                
                <div class="h-1 bg-gradient-to-r from-gray-200 via-[#ffc800] to-gray-200"></div>

                <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold mb-2">
                            Pertanyaan No. {{ session('soal_index') + 1 }}
                        </span>
                        <div class="text-gray-800 text-lg md:text-xl font-medium leading-relaxed">
                            {!! $utbk->pertanyaan !!}
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8 bg-gray-50/50">
                    <form method="POST" action="{{ route('to-utbk.jawab') }}">
                        @csrf
                        
                        <div class="space-y-4">
                            @php
                                $opsi = [
                                    'A' => $utbk->opsi_a,
                                    'B' => $utbk->opsi_b,
                                    'C' => $utbk->opsi_c,
                                    'D' => $utbk->opsi_d,
                                ];
                            @endphp

                            @foreach($opsi as $key => $value)
                                <label class="cursor-pointer block group">
                                    <input type="radio" name="jawaban" value="{{ $key }}" class="custom-radio hidden"
                                           {{ isset($jawaban_user) && $jawaban_user->jawaban == $key ? 'checked' : '' }}>
                                    
                                    <div class="flex items-center p-4 rounded-xl border-2 border-transparent bg-white shadow-sm hover:border-gray-300 transition-all duration-200 group-hover:shadow-md">
                                        <div class="option-label w-10 h-10 rounded-lg border-2 border-gray-200 text-gray-500 font-bold flex items-center justify-center shrink-0 mr-4 transition-colors group-hover:border-gray-400">
                                            {{ $key }}
                                        </div>
                                        <div class="text-gray-700 font-medium">
                                            {{ $value }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" name="prev" 
                                    class="px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2
                                    {{ session('soal_index') == 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:border-gray-400 shadow-sm' }}"
                                    {{ session('soal_index') == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                            </button>

                            <button type="submit" name="next" 
                                    class="px-8 py-3 bg-[#ffc800] text-white rounded-xl font-bold shadow-lg hover:bg-yellow-500 hover:shadow-yellow-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                                <span class="hidden sm:inline">Selanjutnya</span> <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 bg-gray-900 text-white flex justify-between items-center">
                        <h3 class="font-bold text-sm uppercase tracking-wide"><i class="fas fa-th-large mr-2"></i> Navigasi Soal</h3>
                        <span class="font-mono font-bold text-[#ffc800]">01:45:00</span>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-5 gap-2 mb-6">
                            @for ($i = 0; $i < 50; $i++)
                                @php
                                    $isActive = (session('soal_index') == $i);
                                    // Logic warna: Aktif (Kuning), Sudah Jawab (Hijau), Belum (Putih)
                                    // Disini saya buat sederhana: Aktif vs Biasa
                                @endphp
                                
                                <button class="aspect-square flex items-center justify-center rounded-lg text-sm font-bold border transition-all duration-200
                                    {{ $isActive 
                                        ? 'bg-[#ffc800] text-white border-[#ffc800] ring-2 ring-offset-2 ring-[#ffc800]' 
                                        : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50 hover:border-gray-400' 
                                    }}">
                                    {{ $i + 1 }}
                                </button>
                            @endfor
                        </div>

                        <div class="flex flex-wrap gap-3 text-[10px] text-gray-500 justify-center">
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-[#ffc800] rounded-sm"></span> Saat ini</div>
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-white border border-gray-300 rounded-sm"></span> Belum</div>
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-green-500 rounded-sm"></span> Terjawab</div>
                        </div>
                    </div>
                </div>

                <form action="#" method="POST"> @csrf
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengakhiri ujian?')" 
                        class="w-full group relative flex justify-center py-4 px-4 border border-transparent rounded-xl text-white bg-red-600 hover:bg-red-700 font-bold shadow-lg transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-[150%] skew-x-12 transition-transform duration-1000 group-hover:translate-x-[150%] ease-in-out"></div>
                        <span class="flex items-center gap-2 relative z-10">
                            <i class="fas fa-flag-checkered"></i> AKHIRI UJIAN
                        </span>
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection