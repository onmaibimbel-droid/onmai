@extends('mandiri')

@section('title', 'Daftar Kelas')

@section('content')
<div class="min-h-screen bg-gray-50 py-28 px-4">
    <div class="container mx-auto max-w-6xl">

        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">
                Pilih Kelas - Semester {{ $semester }}
            </h2>
            <div class="w-20 h-1 bg-yellow-400 mx-auto mt-3 rounded-full"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @forelse($kelasList as $item)
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center">

                    <h3 class="text-xl font-bold mb-4">
                        Kelas {{ $item->kelas }}
                    </h3>

                    <a href="{{ route('akademik.mapel', ['semester' => $semester, 'kelas' => $item->kelas]) }}"
                       class="inline-block bg-yellow-400 text-white px-4 py-2 rounded-lg font-semibold hover:scale-105 transition">
                        Lihat Mapel
                    </a>

                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    Tidak ada kelas untuk semester ini.
                </p>
            @endforelse

        </div>

    </div>
</div>
@endsection