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
                    <span class="block text-xs text-[#ffc800] font-semibold">Tentor</span>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/imgs/customer01.jpg') }}" alt="Profile" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md">
                </div>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 animate-[fadeIn_0.5s_ease-out]">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-layer-group text-[#ffc800]"></i> Daftar Mata Pelajaran
                    </h2>

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded text-sm flex items-center shadow-sm animate-pulse">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('judulutbk.store') }}" method="POST" class="flex flex-wrap gap-3 items-center">
                        @csrf
                        <div class="relative flex-grow">
                            <div class="w-full md:w-auto flex-1 min-w-[180px]">
                                <input type="text" name="judul" placeholder="Judul..." required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 
                                        focus:border-[#ffc800] focus:ring-2 focus:ring-[#ffc800]/20 
                                        outline-none text-sm transition shadow-sm">
                            </div>
                        </div>
                        
                        <!-- Kelas (Manual Input) -->
                        <div class="w-full md:w-auto min-w-[150px]">
                            <input type="text" name="tahun" placeholder="Contoh: 2026" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm 
                                    focus:border-[#ffc800] focus:ring-2 focus:ring-[#ffc800]/20">
                        </div>
                        
                        <div class="w-full md:w-auto">
                            <button type="submit"
                                class="w-full md:w-auto bg-[#ffc800] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-md hover:bg-yellow-500 hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> 
                                <span class="hidden sm:inline">Tambah</span><span></span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold border-b border-gray-200">Nama</th>
                                <th class="p-4">Tahun</th>
                                <th class="p-4 font-bold border-b border-gray-200 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($juduls as $judul)
                                <tr class="hover:bg-yellow-50/30 transition duration-200 group">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shadow-sm">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <span class="font-semibold text-gray-700">{{ $judul->judul }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-600">
                                            {{ $judul->tahun }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('utbk.show', $judul->id) }}" 
                                               class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white transition shadow-sm" 
                                               title="Tambah Soal">
                                                <i class="fas fa-plus text-xs"></i>
                                            </a>
                                            <a href="{{ route('utbk.judul-edit', $judul->id) }}" 
                                               class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm" 
                                               title="Edit Mapel">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('judulutbk.destroy', $judul->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition shadow-sm"
                                                        onclick="return confirm('Yakin hapus mapel ini beserta semua soalnya?')"
                                                        title="Hapus Mapel">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="p-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-folder-open text-2xl"></i>
                                            </div>
                                            <p class="font-bold text-gray-600">Belum ada Mata Pelajaran</p>
                                            <p class="text-xs">Silakan tambahkan mata pelajaran baru di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

@endsection