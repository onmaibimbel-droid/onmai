<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\Ujian;
use App\Services\SanitizedHtml;
use Illuminate\Http\Request;

class AdminSoalController extends Controller
{
    /**
     * Form Tambah Soal untuk Ujian tertentu
     */
    public function create($ujian_id)
    {
        $ujian = Ujian::findOrFail($ujian_id);

        return view('admin.soal.create', compact('ujian'));
    }

    /**
     * Simpan Soal Baru
     */
    public function store(Request $request, $ujian_id, SanitizedHtml $html)
    {
        $ujian = Ujian::findOrFail($ujian_id);
        $html->prepare($request, ['pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d']);

        $request->validate([
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'jawaban_benar' => 'required|in:A,B,C,D', // Sesuai field database
        ]);

        // Simpan ke database menggunakan relasi
        $ujian->soals()->create([
            'pertanyaan' => $request->pertanyaan,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        // Redirect kembali ke halaman detail ujian
        return redirect()->route('admin.ujian.show', $ujian->id)
            ->with('success', 'Soal berhasil ditambahkan');
    }

    /**
     * Form Edit Soal
     */
    public function edit($id)
    {
        $soal = Soal::with('ujian')->findOrFail($id);
        // Kita butuh data ujian juga untuk tombol 'Kembali'
        $ujian = $soal->ujian;

        return view('admin.soal.edit', compact('soal', 'ujian'));
    }

    /**
     * Update Soal
     */
    public function update(Request $request, $id, SanitizedHtml $html)
    {
        $soal = Soal::findOrFail($id);
        $html->prepare($request, ['pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d']);

        $request->validate([
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        $soal->update([
            'pertanyaan' => $request->pertanyaan,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        return redirect()->route('admin.ujian.show', $soal->ujian_id)
            ->with('success', 'Soal berhasil diperbarui');
    }

    /**
     * Hapus Soal
     */
    public function destroy($id)
    {
        $soal = Soal::findOrFail($id);
        $ujian_id = $soal->ujian_id; // Simpan ID ujian sebelum dihapus untuk redirect

        $soal->delete();

        return redirect()->route('admin.ujian.show', $ujian_id)
            ->with('success', 'Soal berhasil dihapus');
    }

    /**
     * Upload Gambar dari CKEditor
     * (Sama persis dengan logika yang sudah berhasil sebelumnya)
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

        return response()->json(['error' => ['message' => 'Upload gagal']], 400);
    }
}
