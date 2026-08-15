<?php

namespace App\Http\Controllers;

use App\Models\Mandiri;   // PERBAIKAN: Huruf depan harus Besar
use App\Models\Mapel; // PERBAIKAN: Huruf depan harus Besar
use App\Services\DocxSoalParser;
use App\Services\SanitizedHtml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public function store(Request $request, Mandiri $mandiri, SanitizedHtml $html)
    {
        $html->prepare($request, ['pertanyaan', 'a', 'b', 'c', 'd', 'pembahasan']);

        $data = $request->validate([
            'pertanyaan' => 'required',
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
            'kunci' => 'required',
            'pembahasan' => 'nullable',
        ]);

        $mandiri->mapels()->create($data);

        return redirect()
            ->route('mandiri.show', $mandiri->id)
            ->with('success', 'Soal berhasil ditambahkan');
    }

    /**
     * Import Excel
     */
    public function importExcel(Request $request, Mandiri $mandiri, SanitizedHtml $html)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // header baris pertama
            $header = array_map(fn ($h) => strtolower(trim($h)), $rows[0]);
            unset($rows[0]);

            $count = 0;

            foreach ($rows as $row) {
                if (count($row) < count($header)) {
                    continue;
                }

                // Gunakan array_slice untuk mencegah error jika kolom data melebihi header
                $data = array_combine($header, array_slice($row, 0, count($header)));

                // Ambil Kunci Jawaban
                $kunci = strtoupper($data['kunci'] ?? $data['jawaban'] ?? $data['key'] ?? 'A');

                $pertanyaan = $html->sanitize($data['soal'] ?? '');

                // Skip jika soal kosong / cuma spasi
                if (empty(trim($pertanyaan))) {
                    continue;
                }

                // Jika tidak ada div, bungkus biar rapi (opsional)
                if (! str_contains($pertanyaan, '<div')) {
                    $pertanyaan = '<div>'.$pertanyaan.'</div>';
                }

                $mandiri->mapels()->create([
                    'pertanyaan' => $pertanyaan,
                    'a' => $html->sanitize($data['a'] ?? ''),
                    'b' => $html->sanitize($data['b'] ?? ''),
                    'c' => $html->sanitize($data['c'] ?? ''),
                    'd' => $html->sanitize($data['d'] ?? ''),
                    'kunci' => $kunci,
                    'pembahasan' => $html->sanitize($data['pembahasan'] ?? ''),
                ]);

                $count++;
            }

            return back()->with('success', "$count soal berhasil diimport");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    /**
     * Import Word: baca file, simpan hasil bacanya, lalu tampilkan preview.
     *
     * Hasil parsing sengaja dititipkan ke file sementara, bukan dikirim balik
     * lewat form. Selain payload MathML-nya besar, mengirim ulang lewat POST
     * berisiko diblokir firewall hosting seperti yang terjadi waktu copy-paste.
     */
    public function importWord(Request $request, Mandiri $mandiri, DocxSoalParser $parser)
    {
        $request->validate([
            'file' => 'required|file|mimes:docx|max:10240',
        ], [
            'file.mimes' => 'File harus berformat .docx. Kalau filenya masih .doc lama, buka di Word lalu Save As ke .docx.',
        ]);

        try {
            $soal = $parser->parse($request->file('file')->getRealPath());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membaca file: '.$e->getMessage());
        }

        if (! $soal) {
            return back()->with('error', 'Tidak ada soal yang terbaca dari file itu.');
        }

        $token = Str::uuid()->toString();

        Storage::put("word-import/{$token}.json", json_encode([
            'mandiri_id' => $mandiri->id,
            'nama_file' => $request->file('file')->getClientOriginalName(),
            'soal' => $soal,
        ]));

        return redirect()->route('mapel.import-word.preview', [$mandiri->id, $token]);
    }

    /**
     * Preview hasil baca sebelum benar-benar disimpan.
     */
    public function importWordPreview(Mandiri $mandiri, string $token)
    {
        $data = $this->ambilHasilBaca($mandiri, $token);

        return view('mandiri.import-word', [
            'mandiri' => $mandiri,
            'token' => $token,
            'namaFile' => $data['nama_file'],
            'daftarSoal' => $data['soal'],
        ]);
    }

    /**
     * Simpan soal yang dicentang guru di halaman preview.
     */
    public function importWordSimpan(Request $request, Mandiri $mandiri, string $token, SanitizedHtml $html)
    {
        $data = $this->ambilHasilBaca($mandiri, $token);

        $validated = $request->validate([
            'pilih' => 'required|array|min:1',
            'pilih.*' => 'integer',
            'kunci' => 'required|array',
            'isi' => 'nullable|array',
            'isi.*' => 'array',
            'isi.*.*' => 'nullable|string|max:50000',
        ], [
            'pilih.required' => 'Belum ada soal yang dicentang.',
        ]);

        $jumlah = 0;
        $dilewati = [];

        foreach ($validated['pilih'] as $index) {
            $soal = $data['soal'][$index] ?? null;

            if (! $soal) {
                continue;
            }

            foreach (['a', 'b', 'c', 'd'] as $huruf) {
                if (trim((string) ($soal[$huruf] ?? '')) === '') {
                    $soal[$huruf] = $html->sanitize($validated['isi'][$index][$huruf] ?? '');
                }
            }

            $kosong = array_values(array_filter(
                ['a', 'b', 'c', 'd'],
                fn ($huruf) => trim((string) ($soal[$huruf] ?? '')) === ''
            ));

            if ($kosong) {
                $dilewati[] = ($index + 1).' (opsi '.strtoupper(implode(', ', $kosong)).' masih kosong)';

                continue;
            }

            $kunci = $validated['kunci'][$index] ?? null;

            // Tanpa kunci jawaban soalnya tidak bisa dinilai, jadi lebih baik
            // dilewati dan dilaporkan daripada masuk dalam keadaan cacat.
            if (! in_array($kunci, ['a', 'b', 'c', 'd'], true)) {
                $dilewati[] = $index + 1;

                continue;
            }

            $mandiri->mapels()->create([
                'pertanyaan' => $html->sanitize($soal['pertanyaan']),
                'a' => $html->sanitize($soal['a']),
                'b' => $html->sanitize($soal['b']),
                'c' => $html->sanitize($soal['c']),
                'd' => $html->sanitize($soal['d']),
                'kunci' => $kunci,
                'pembahasan' => null,
            ]);

            $jumlah++;
        }

        Storage::delete("word-import/{$token}.json");

        $pesan = "{$jumlah} soal berhasil diimport dari Word";

        if ($dilewati) {
            $pesan .= '. Soal nomor '.implode(', ', $dilewati).' dilewati karena belum lengkap atau kunci jawabannya belum dipilih';
        }

        return redirect()
            ->route('mandiri.show', $mandiri->id)
            ->with($jumlah ? 'success' : 'error', $pesan);
    }

    /** Ambil hasil baca yang dititipkan, sekaligus pastikan tokennya milik mandiri ini. */
    private function ambilHasilBaca(Mandiri $mandiri, string $token): array
    {
        abort_unless(Str::isUuid($token), 404);

        $path = "word-import/{$token}.json";

        abort_unless(Storage::exists($path), 404, 'Hasil import sudah kedaluwarsa. Silakan upload ulang filenya.');

        $data = json_decode(Storage::get($path), true);

        abort_unless(is_array($data) && ($data['mandiri_id'] ?? null) === $mandiri->id, 404);

        return $data;
    }

    /**
     * Upload Gambar CKEditor
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $filename.'_'.time().'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs('soal_images', $filename, 'public');

            return response()->json([
                'url' => asset('storage/'.$path),
            ]);
        }

        return response()->json([
            'error' => ['message' => 'Upload gagal'],
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
    public function update(Request $request, Mandiri $mandiri, Mapel $mapel, SanitizedHtml $html) // PERBAIKAN: Huruf Besar
    {
        $html->prepare($request, ['pertanyaan', 'a', 'b', 'c', 'd', 'pembahasan']);

        $data = $request->validate([
            'pertanyaan' => 'required',
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
            'kunci' => 'required',
            'pembahasan' => 'nullable',
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
