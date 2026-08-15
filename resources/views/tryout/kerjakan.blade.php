@extends('tryout.index')

@section('content')

<style>
    .custom-radio:checked + div {
        background-color: #fefce8;
        border-color: #ffc800;
        box-shadow: 0 0 0 1px #ffc800 inset;
    }
    .custom-radio:checked + div .radio-dot {
        background-color: #ffc800;
        border-color: #ffc800;
        color: white;
    }
    .soal-content img, .jawaban-content img {
        display: inline-block !important;
        vertical-align: middle;
        max-width: 100%;
        height: auto;
        margin: 5px 0;
        border-radius: 6px;
    }
    .soal-content p {
        display: inline-block;
        margin-bottom: 0.5rem;
    }
</style>

<div class="min-h-screen pb-12">

    {{-- Header Bar --}}
    <div class="bg-gray-900 text-white p-4 rounded-xl shadow-lg mb-6 flex flex-col md:flex-row justify-between items-center sticky top-20 z-40 border-b-4 border-[#ffc800]">
        <div class="mb-2 md:mb-0">
            <h2 class="text-lg font-bold font-heading">{{ $ujian->nama_ujian }}</h2>
            <p class="text-xs text-gray-400">{{ $ujian->mapel }} | Kelas {{ $ujian->kelas }}</p>
        </div>
        <div class="text-center md:text-right flex items-center gap-4">
            <div id="warning-badge" class="hidden px-3 py-1 bg-red-600 rounded text-xs font-bold animate-pulse">
                PERINGATAN: <span id="warning-count">0</span>/{{ config('exam.warning_limit') }}
            </div>
            <div>
                <span class="block text-[10px] text-gray-400 uppercase tracking-widest font-bold">Sisa Waktu</span>
                <span id="countdown-timer" class="text-2xl font-mono font-bold text-[#ffc800] tracking-wider">--:--:--</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Soal Container (rendered by JS) --}}
        <div class="lg:col-span-2 animate-[fadeIn_0.5s_ease-out]">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <span class="bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        Soal No. <span id="exam-soal-number">{{ $index + 1 }}</span>
                    </span>
                    <span class="text-xs text-gray-500 font-medium">
                        Total: <span id="exam-soal-total">{{ count($soal_list) }}</span> soal
                    </span>
                </div>

                <div class="p-6 md:p-8">
                    <div id="exam-soal-container">
                        {{-- Initial server-rendered content (replaced by JS on load) --}}
                        <div class="soal-content text-gray-800 text-lg font-medium leading-relaxed mb-8 prose max-w-none">
                            {!! $soal->pertanyaan !!}
                        </div>
                        <div class="space-y-4">
                            @php
                                $opsi = [
                                    'A' => $soal->opsi_a ?? $soal->a,
                                    'B' => $soal->opsi_b ?? $soal->b,
                                    'C' => $soal->opsi_c ?? $soal->c,
                                    'D' => $soal->opsi_d ?? $soal->d,
                                ];
                            @endphp
                            @foreach($opsi as $key => $value)
                                @if(!empty($value))
                                <label class="cursor-pointer block group">
                                    <div class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 hover:border-[#ffc800] hover:bg-yellow-50/50 transition-all duration-200">
                                        <div class="radio-dot w-8 h-8 rounded-lg bg-gray-100 border border-gray-300 flex items-center justify-center shrink-0 text-sm font-bold text-gray-500 transition-colors group-hover:bg-[#ffc800] group-hover:text-white group-hover:border-[#ffc800]
                                            {{ ($jawaban_user && $jawaban_user->jawaban === $key) ? '!bg-[#ffc800] !border-[#ffc800] !text-white' : '' }}">
                                            {{ $key }}
                                        </div>
                                        <div class="jawaban-content text-gray-700 text-sm md:text-base prose max-w-none pt-1">
                                            {!! $value !!}
                                        </div>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Navigation buttons --}}
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-100">
                        <button id="exam-prev-btn" {{ $index === 0 ? 'disabled' : '' }}
                            class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition text-sm flex items-center gap-2 {{ $index === 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>

                        @if($index < count($soal_list) - 1)
                            <button id="exam-next-btn" class="px-5 py-2.5 bg-[#ffc800] text-white rounded-lg font-bold shadow-md hover:bg-yellow-500 hover:shadow-lg transition text-sm flex items-center gap-2">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </button>
                            <button id="exam-finish-btn-inline" class="hidden px-6 py-2.5 bg-green-600 text-white rounded-lg font-bold shadow-md hover:bg-green-700 transition text-sm flex items-center gap-2">
                                Selesai <i class="fas fa-check"></i>
                            </button>
                        @else
                            <button id="exam-next-btn" class="hidden px-5 py-2.5 bg-[#ffc800] text-white rounded-lg font-bold shadow-md hover:bg-yellow-500 hover:shadow-lg transition text-sm flex items-center gap-2">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </button>
                            <button id="exam-finish-btn-inline" class="px-6 py-2.5 bg-green-600 text-white rounded-lg font-bold shadow-md hover:bg-green-700 transition text-sm flex items-center gap-2">
                                Selesai <i class="fas fa-check"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-40 space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Navigasi Soal</h3>
                    <div id="exam-nav-container" class="grid grid-cols-5 gap-2">
                        {{-- Initial server-rendered nav (replaced by JS on load) --}}
                        @foreach($soal_list as $i => $s)
                            @php
                                $sudah_dijawab = isset($jawaban_all[$s->id]);
                                $is_active = $i == $index;
                                $bgClass = $is_active
                                    ? 'bg-[#ffc800] text-white border-[#ffc800] ring-2 ring-yellow-200 font-extrabold scale-110 shadow-md'
                                    : ($sudah_dijawab
                                        ? 'bg-green-500 text-white border-green-500 hover:bg-green-600'
                                        : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400 hover:bg-gray-50');
                            @endphp
                            <span class="w-full aspect-square flex items-center justify-center rounded-lg border text-sm font-bold transition-all duration-200 {{ $bgClass }}">
                                {{ $i + 1 }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('tryout.selesai', $ujian->id) }}" id="form-selesai">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm flex items-center justify-center gap-2 group">
                        <i class="fas fa-flag-checkered group-hover:scale-110 transition-transform"></i> AKHIRI UJIAN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    window.__examAppUrl = "{{ url('/') }}";
</script>

<script type="module">
    import ExamSession from '/js/exam-session.js';

    const exam = ExamSession({
        ujianId: {{ $ujian->id }},
        csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        sessionUrl: "{{ route('tryout.session', $ujian->id) }}",
        answerUrl: "{{ route('tryout.answer', $ujian->id) }}",
        finishUrl: "{{ route('tryout.finish', $ujian->id) }}",
        violationUrl: "{{ route('tryout.violation-events', $ujian->id) }}",
        resultUrl: "{{ route('tryout.hasil', $ujian->id) }}",
        warningLimit: {{ config('exam.warning_limit') }},
        hiddenThresholdMs: {{ config('exam.hidden_threshold_ms') }},
    });

    // Timer (stays in Blade — needs server time)
    const waktuSelesai = new Date("{{ $ujian->waktu_selesai }}").getTime();
    const timerInterval = setInterval(function() {
        const now = new Date().getTime();
        const distance = waktuSelesai - now;
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        const display = String(hours).padStart(2, '0') + ":" + String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
        const timerEl = document.getElementById("countdown-timer");
        if(timerEl) {
            timerEl.innerHTML = display;
            if(distance < 300000) {
                timerEl.classList.remove('text-[#ffc800]');
                timerEl.classList.add('text-red-500', 'animate-ping');
            }
        }
        if (distance < 0) {
            clearInterval(timerInterval);
            exam.doFinish();
        }
    }, 1000);

    // Init exam session
    exam.init();
</script>

@endsection
