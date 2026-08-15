<?php

namespace App\Http\Controllers;

use App\Models\Mapel;   // PERBAIKAN: Huruf depan harus Besar
use App\Models\Mandiri; // PERBAIKAN: Huruf depan harus Besar
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Mandiri $mandiri)
    {
        $mapels = Mapel::all();
        return view('mandiri.index', compact('mapels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Mandiri $mandiri)
    {
        return view('mandiri.mapel', compact('mandiri'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Mandiri $mandiri)
    {
        
        $data = $request->validate([
            'pertanyaan' => 'required',
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
            'kunci' => 'required',
            'pembahasan' => 'nullable'
        ]);

        $mandiri->mapels()->create($data);

        return redirect()
            ->route('mandiri.show', $mandiri->id)
            ->with('success', 'Soal berhasil ditambahkan');
    }

    /**
     * Import Excel
     */
    public function importExcel(Request $request, Mandiri $mandiri)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray();

            // header baris pertama
            $header = array_map(fn($h) => strtolower(trim($h)), $rows[0]);
            unset($rows[0]);

            $count = 0;
            
            // Tag HTML yang diizinkan agar gambar tidak hilang
            $allowed_tags = '<img><p><br><b><i><u><strong><em><span><div><ul><ol><li><table><tr><td><th><tbody><thead>';

            foreach ($rows as $row) {
                if (count($row) < count($header)) {
                    continue;
                }

                // Gunakan array_slice untuk mencegah error jika kolom data melebihi header
                $data = array_combine($header, array_slice($row, 0, count($header)));

                // Ambil Kunci Jawaban
                $kunci = strtoupper($data['kunci'] ?? $data['jawaban'] ?? $data['key'] ?? 'A');

                // Bersihkan soal tapi biarkan gambar
                $pertanyaan = strip_tags($data['soal'] ?? '', $allowed_tags);
                $pertanyaan = preg_replace('/text-align\s*:\s*center;?/i', '', $pertanyaan);
                $pertanyaan = strip_tags($data['soal'] ?? '', $allowed_tags);

                // Skip jika soal kosong / cuma spasi
                if (empty(trim($pertanyaan))) {
                    continue;
                }
                
                // Jika tidak ada div, bungkus biar rapi (opsional)
                if (!str_contains($pertanyaan, '<div')) {
                    $pertanyaan = '<div style="text-align:left">' . $pertanyaan . '</div>';
                }

                $mandiri->mapels()->create([
                    'pertanyaan' => $pertanyaan,
                    'a' => strip_tags($data['a'] ?? '', $allowed_tags),
                    'b' => strip_tags($data['b'] ?? '', $allowed_tags),
                    'c' => strip_tags($data['c'] ?? '', $allowed_tags),
                    'd' => strip_tags($data['d'] ?? '', $allowed_tags),
                    'kunci' => $kunci,
                    'pembahasan' => strip_tags($data['pembahasan'] ?? '', $allowed_tags)
                ]);
                   
                $count++;
            }

            return back()->with('success', "$count soal berhasil diimport");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Upload Gambar CKEditor
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $filename . '_' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('soal_images', $filename, 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'error' => ['message' => 'Upload gagal']
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mapel $mapel) // PERBAIKAN: Mapel (Besar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mandiri $mandiri, Mapel $mapel) // PERBAIKAN: Huruf Besar
    {
        return view('mandiri.edit', compact('mandiri', 'mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mandiri $mandiri, Mapel $mapel) // PERBAIKAN: Huruf Besar
    {
        $data = $request->validate([
            'pertanyaan' => 'required',
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
            'kunci' => 'required',
            'pembahasan' => 'nullable'
        ]);

        $mapel->update($data);

        return redirect()
            ->route('mandiri.show', ['mandiri' => $mandiri->id])
            ->with('success', 'Soal berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mandiri $mandiri, Mapel $mapel) // PERBAIKAN: Huruf Besar
    {
        $mapel->delete();

        return redirect()
            ->route('mandiri.show', $mandiri->id)
            ->with('success', 'Soal berhasil dihapus');
    }
}