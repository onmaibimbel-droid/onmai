@extends('belajar')

@section('content')

<style>
    .soal-preview math { font-size: 1.05em; }
    .soal-preview table { border-collapse: collapse; margin: 8px 0; }
    .soal-preview table td { border: 1px solid #d1d5db; padding: 4px 10px; font-size: 0.9em; }
    .kartu-soal { transition: border-color .15s, background-color .15s; }
    .kartu-soal:has(input[name="pilih[]"]:not(:checked)) { opacity: .55; }
</style>

@php
    $totalSoal   = count($daftarSoal);
    $totalSiap   = collect($daftarSoal)->where('siap', true)->count();
    $totalDicek  = $totalSoal - $totalSiap;
@endphp

<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6">
        <a href="{{ route('mandiri.show', $mandiri->id) }}"
           class="text-sm text-gray-500 hover:text-gray-700 font-semibold">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke bank soal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Preview Import Word</h1>
        <p class="text-gray-500 text-sm mb-5">
            Dibaca dari <span class="font-semibold text-gray-700">{{ $namaFile }}</span>
        </p>

        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-2xl font-bold text-gray-800">{{ $totalSoal }}</div>
                <div class="text-xs text-gray-500 font-semibold mt-0.5">soal terbaca</div>
            </div>
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <div class="text-2xl font-bold text-green-700">{{ $totalSiap }}</div>
                <div class="text-xs text-green-600 font-semibold mt-0.5">lengkap</div>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                <div class="text-2xl font-bold text-amber-700">{{ $totalDicek }}</div>
                <div class="text-xs text-amber-600 font-semibold mt-0.5">perlu dicek</div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded-lg text-sm">
            <p class="font-bold mb-1"><i class="fas fa-info-circle mr-1"></i> Sebelum menyimpan</p>
            <p>
                Dokumen Word tidak memuat kunci jawaban, jadi <span class="font-semibold">kunci setiap soal harus dipilih di sini</span>.
                Soal yang kuncinya belum dipilih akan dilewati. Hilangkan centang pada soal yang tidak ingin diimport.
            </p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-sm mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-sm mb-6">
            @foreach($errors->all() as $pesan)
                <div>{{ $pesan }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('mapel.import-word.simpan', [$mandiri->id, $token]) }}" method="POST">
        @csrf

        <div class="space-y-5">
            @foreach($daftarSoal as $i => $soal)
                <div class="kartu-soal bg-white rounded-2xl shadow-sm border {{ $soal['siap'] ? 'border-gray-200' : 'border-amber-300' }} overflow-hidden">

                    <div class="bg-gray-50/70 px-5 py-3 border-b border-gray-100 flex items-center justify-between gap-3">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="pilih[]" value="{{ $i }}"
                                   {{ $soal['siap'] ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-[#ffc800] focus:ring-[#ffc800]">
                            <span class="bg-[#ffc800] text-white text-xs font-bold px-3 py-1.5 rounded-lg">
                                Soal {{ $i + 1 }}
                            </span>
                        </label>

                        @unless($soal['siap'])
                            <span class="text-xs font-bold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-lg">
                                <i class="fas fa-triangle-exclamation mr-1"></i> perlu dicek
                            </span>
                        @endunless
                    </div>

                    <div class="p-5">
                        <div class="soal-preview text-gray-800 mb-4 leading-relaxed">
                            {!! $soal['pertanyaan'] !!}
                        </div>

                        @if($soal['catatan'])
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4 space-y-1">
                                @foreach($soal['catatan'] as $catatan)
                                    <p class="text-xs text-amber-800 flex gap-2">
                                        <i class="fas fa-circle-exclamation mt-0.5"></i>
                                        <span>{{ $catatan }}</span>
                                    </p>
                                @endforeach
                            </div>
                        @endif

                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                            Pilih kunci jawaban
                        </p>

                        <div class="grid md:grid-cols-2 gap-2">
                            @foreach(['a', 'b', 'c', 'd'] as $huruf)
                                <div class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50">
                                    <input type="radio" name="kunci[{{ $i }}]" value="{{ $huruf }}"
                                           class="mt-1 w-4 h-4 border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="font-bold text-gray-500 text-sm">{{ strtoupper($huruf) }}.</span>
                                    <div class="soal-preview text-sm text-gray-800 flex-1">
                                        @if(trim($soal[$huruf]) === '')
                                            <label for="isi-{{ $i }}-{{ $huruf }}" class="block text-red-500 font-semibold mb-2">
                                                Pilihan ini belum terbaca. Lengkapi sebelum mengimpor.
                                            </label>
                                            <textarea id="isi-{{ $i }}-{{ $huruf }}" name="isi[{{ $i }}][{{ $huruf }}]"
                                                      rows="3" class="w-full rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-sm text-gray-800 focus:border-amber-500 focus:ring-amber-500"
                                                      placeholder="Tulis pilihan {{ strtoupper($huruf) }}...">{{ old("isi.$i.$huruf") }}</textarea>
                                        @else
                                            {!! $soal[$huruf] !!}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="sticky bottom-0 mt-6 bg-white/95 backdrop-blur border border-gray-200 rounded-2xl shadow-lg p-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Soal yang kuncinya belum dipilih akan dilewati.
            </p>
            <div class="flex items-center gap-3">
                <a href="{{ route('mandiri.show', $mandiri->id) }}"
                   class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-green-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-check mr-1"></i> Simpan soal terpilih
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    window.MathJax = {
        options: { enableMenu: false },
        startup: { typeset: true }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/mml-chtml.js" id="MathJax-script" async></script>

@endsection
