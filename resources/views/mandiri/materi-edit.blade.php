@extends('belajar')

@section('content')

<style>
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
</style>

<div class="min-h-screen bg-gray-50 p-4 md:p-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div id="dashboard-toggle" class="toggle lg:hidden cursor-pointer p-2 rounded-md hover:bg-gray-200 transition duration-300">
                <div class="hamburger-lines"><span></span><span></span><span></span></div>
            </div>
            <div>
                <h1 class="text-2xl font-bold font-heading text-gray-800">Belajar Mandiri</h1>
                <p class="text-xs text-gray-500">Kelola bank soal dan materi pelajaran</p>
            </div>
        </div>

        <div class="flex items-center gap-6 w-full md:w-auto justify-end">
            <div class="flex items-center gap-3 pl-0 md:pl-6 md:border-l border-gray-200 shrink-0">
                <div class="text-right sm:block">
                    <span class="block text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                    <span class="block text-xs text-[#ffc800] font-semibold">Administrator</span>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/imgs/customer01.jpg') }}" alt="Profile" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="max-w-xl mx-auto bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Edit Mata Pelajaran
    </h2>

    <form action="{{ route('mandiri.update', $mandiri->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Nama Mapel -->
        <div>
            <label class="block text-sm font-semibold mb-2">
                Nama Mapel
            </label>
            <input type="text"
                name="nama_mapel"
                value="{{ $mandiri->nama_mapel }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#ffc800]/20 focus:border-[#ffc800]"
                required>
        </div>

        <!-- Semester -->
        <div>
            <label class="block text-sm font-semibold mb-2">
                Semester
            </label>
            <select name="semester"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#ffc800]/20 focus:border-[#ffc800]"
                required>
                <option value="1" {{ $mandiri->semester == 1 ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ $mandiri->semester == 2 ? 'selected' : '' }}>Semester 2</option>
            </select>
        </div>

        <!-- Kelas -->
        <div>
            <label class="block text-sm font-semibold mb-2">
                Kelas
            </label>
            <input type="text"
                name="kelas"
                value="{{ $mandiri->kelas }}"
                placeholder="Contoh: X IPA 1"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#ffc800]/20 focus:border-[#ffc800]"
                required>
        </div>

        <!-- Pelajaran -->
        <div>
            <label class="block text-sm font-semibold mb-2">
                Pelajaran
            </label>
            <input type="text"
                name="pelajaran"
                value="{{ $mandiri->pelajaran }}"
                placeholder="Contoh: Matematika"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#ffc800]/20 focus:border-[#ffc800]"
                required>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('mandiri.materi') }}"
               class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                Batal
            </a>

            <button type="submit"
                    class="bg-[#ffc800] text-white px-5 py-2 rounded-lg font-bold hover:bg-yellow-500 transition">
                Update
            </button>
        </div>
    </form>
</div>
    </div>
@endsection
