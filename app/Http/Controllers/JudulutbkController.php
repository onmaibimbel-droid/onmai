<?php

namespace App\Http\Controllers;

use App\Models\Judulutbk;
use App\Models\Utbk;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;

class JudulutbkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $juduls = Judulutbk::all();
        return view('to-utbk.index', compact('juduls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function import(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Ambil header baris pertama, bersihkan spasi, dan jadikan huruf kecil
        // Contoh header Excel: "Soal", "A", "B", "C", "D", "Kunci"
        $header = array_map(fn($h) => strtolower(trim($h)), $rows[0]);
        unset($rows[0]); // Hapus baris header dari data

        // DAFTAR TAG YANG DIIZINKAN (Agar gambar <img> tidak terhapus)
        $allowed_tags = '<img><p><br><b><i><u><strong><em><span><div><ul><ol><li><table><tr><td><th><tbody><thead>'; 

        foreach ($rows as $row) {
            // Skip baris kosong atau tidak lengkap
            if (count($row) < count($header)) continue;

            // Gabungkan header dengan data baris ini
            // Gunakan array_slice untuk menghindari error jika jumlah kolom data > header
            $data = array_combine($header, array_slice($row, 0, count($header)));

            // Simpan ke database (Relasi ke tabel Mapel/Soal)
            // Pastikan Anda memiliki Model 'Mapel' yang terhubung ke tabel soal
            Utbk::create([
                'mandiri_id' => $id, // ID Mapel Induk
                'pertanyaan' => strip_tags($data['soal'] ?? '', $allowed_tags),
                'a'          => strip_tags($data['a'] ?? '', $allowed_tags),
                'b'          => strip_tags($data['b'] ?? '', $allowed_tags),
                'c'          => strip_tags($data['c'] ?? '', $allowed_tags),
                'd'          => strip_tags($data['d'] ?? '', $allowed_tags),
                'e'          => strip_tags($data['e'] ?? '', $allowed_tags),
                'kunci_jawaban' => strtoupper($data['kunci'] ?? $data['jawaban'] ?? $data['key'] ?? null),
            ]);
        }

        return redirect()->route('utbk.soal', $id)
            ->with('success', 'Soal berhasil diimport!');
    }
    public function create()
    {
        return view('juduls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'tahun'  => 'required|string|max:100',
        ]);

        Judulutbk::create([
            'judul' => $request->judul,
            'tahun' => $request->tahun,
        ]);

        return redirect()->back()
            ->with('success', 'Judul UTBK berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Judulutbk $judulutbk)
    {
        
        return view('utbk.show', compact('judulutbk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Judulutbk $judulutbk)
    {
        
        return view('utbk.judul-edit', compact('judulutbk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Judulutbk $judulutbk)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
        ]);

        $judulutbk->update($request->only('judul', 'tahun'));

        return redirect()->route('juduls.index')
            ->with('success', 'Judul UTBK berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $judul = Judulutbk::findOrFail($id);
        $judul->delete();
        
        return redirect()->route('utbk.soal')
            ->with('success', 'Judul UTBK berhasil dihapus');
    }
}
