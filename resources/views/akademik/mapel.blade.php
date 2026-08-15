@extends('mandiri')

@section('title', 'Daftar Mapel')

@section('content')
<div class="min-h-screen bg-gray-50 py-28 px-4">
    <div class="container mx-auto max-w-6xl">

        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">
                Mapel Kelas {{ $kelas }} - Semester {{ $semester }}
            </h2>
            <div class="w-20 h-1 bg-yellow-400 mx-auto mt-3 rounded-full"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @forelse($mapels as $mapel)
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center">

                    <h3 class="text-xl font-bold mb-4">
                        {{ $mapel->pelajaran }}
                    </h3>

                    <a href="{{ route('index.soal', [$semester, $kelas, $mapel->pelajaran]) }}"
                       class="inline-block bg-yellow-400 text-white px-4 py-2 rounded-lg font-semibold hover:scale-105 transition">
                        Lihat Soal
                    </a>

                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    Tidak ada mapel untuk kelas ini.
                </p>
            @endforelse

        </div>

    </div>
</div>
@endsection