<?php

namespace App\Http\Controllers;

use App\Models\Toutbk;
use App\Models\Judulutbk;
use App\Models\Hasil;
use App\Models\Jawaban;
use App\Models\Jawabanutbk;
use App\Models\Utbk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToutbkController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index()
    {
         $juduls = Judulutbk::latest()->get();
         $utbk = Utbk::latest()->get();
         

        return view('to-utbk.to_utbk', compact('juduls', 'utbk'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Judulutbk $juduls, Utbk $utbk)
    {
        
         return view('to-utbk.show', compact('juduls', 'utbk'));
    }

    public function kerjakan(Request $request, Judulutbk $judulutbk, $index = 0)
    {
        
        // Cek hasil menggunakan kolom 'selesai' (tinyint)
        $hasil = Hasil::where('user_id', Auth::id())
            ->where('judulutbk_id', $judulutbk->id)
            ->first();

        // Jika selesai == 1, maka redirect
        if ($hasil && $hasil->selesai == 1) {
            return redirect()->route('to-utbk.hasil', $judulutbk->id)
                ->with('warning', 'Anda sudah menyelesaikan ujian ini.');
        }

        $soal_list = $judulutbk->utbks()->get();

        if ($soal_list->isEmpty()) {
            return redirect()->route('to-utbk.to_utbk')
                ->with('error', 'Soal untuk ujian ini belum tersedia.');
        }

        if (!isset($soal_list[$index])) {
        return redirect()->route('to-utbk.kerjakan', [
                'judulutbk' => $judulutbk->id,
                'index' => 0
        ])->with('error', 'Soal tidak ditemukan.');
        }

        $utbk = $soal_list[$index];

        $jawaban_user = Jawabanutbk::where('user_id', Auth::id())
            ->where('judulutbk_id', $judulutbk->id)
            ->where('utbk_id', $utbk->id)
            ->first();

        $jawaban_all = Jawabanutbk::where('user_id', Auth::id())
            ->where('judulutbk_id', $judulutbk->id)
            ->pluck('jawaban', 'utbk_id');

        return view('to-utbk.kerjakan', compact(
            'judulutbk',
            'soal_list',
            'utbk',
            'index',
            'jawaban_user',
            'jawaban_all'
        ));
    }
    public function akhiri(Request $request, Judulutbk $judulutbk)
    {
        $total_soal = $judulutbk->utbk()->count();
        $jawaban_benar = 0;

        $jawabans = Jawabanutbk::where('user_id', Auth::id())
            ->where('judulutbk_id', $judulutbk->id)
            ->get();

        foreach ($jawabans as $jawab) {
            if ($jawab->utbk && $jawab->jawaban == $jawab->utbk->jawaban_benar) {
                $jawaban_benar++;
            }
        }

        $skor = ($total_soal > 0) ? ($jawaban_benar / $total_soal) * 700 : 0;

        // Gunakan updateOrCreate agar aman
        Hasil::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'judulutbk_id' => $judulutbk->id,
            ],
            [
                'skor' => $skor,       // SESUAI DB: 'skor'
                'selesai' => 1,        // SESUAI DB: 1 (Tinyint True)
                // peringatan tidak diupdate disini agar history pelanggaran tidak hilang
            ]
        );

        return redirect()->route('to-utbk.hasil', $judulutbk->id)
            ->with('success', 'Ujian telah diselesaikan. Skor Anda: ' . round($skor, 2));
    }

   

    public function pelanggaran(Request $request)
    {
        $judulutbk_id = $request->input('judulutbk_id');
        $user_id = Auth::id();

        // 1. Cari Data Hasil secara Manual
        $hasil = Hasil::where('user_id', $user_id)
                      ->where('judulutbk_id', $judulutbk_id)
                      ->first();

        // 2. Jika Belum Ada, Buat Baru
        if (!$hasil) {
            $hasil = new Hasil();
            $hasil->user_id = $user_id;
            $hasil->judulutbk_id = $judulutbk_id;
            $hasil->skor = 0;          // SESUAI DB
            $hasil->peringatan = 0;    // KOLOM BARU
            $hasil->selesai = 0;       // FALSE
            $hasil->save();
        }

        // 3. Jika Sudah Selesai, Return selesai
        if ($hasil->selesai == 1) {
            return response()->json(['status' => 'selesai']);
        }

        // 4. Tambah Peringatan
        $hasil->peringatan += 1;

        // 5. Cek Batas Pelanggaran (>= 3)
        if ($hasil->peringatan >= 3) {
            
            // Hitung Skor Akhir
            $ujianutbk = Judulutbk::find($judulutbk_id);
            if ($ujianutbk) {
                $total_soal = $ujianutbk->utbk()->count();
                $jawaban_benar = 0;
                
                $jawabans = Jawabanutbk::where('user_id', $user_id)
                    ->where('judulutbk_id', $ujianutbk->id)
                    ->get();

                foreach ($jawabans as $jawab) {
                    if ($jawab->utbk && $jawab->jawaban == $jawab->utbk->jawaban_benar) {
                        $jawaban_benar++;
                    }
                }
                
                $persen = $total_soal > 0 ? ($jawaban_benar / $total_soal) : 0;

                $skor = 300 + ($persen * 400);

                $hasil->skor = round($skor, 2);
                    } else {
                        $hasil->skor = 300; // Skor Minimum jika data ujian tidak ditemukan
                    }

            $hasil->selesai = 1; // Set Selesai = True
            $hasil->save();

            return response()->json(['status' => 'selesai']);
        }

        // Simpan Peringatan
        $hasil->save();

        return response()->json([
            'status' => 'peringatan',
            'peringatan' => $hasil->peringatan
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toutbk $toutbk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toutbk $toutbk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toutbk $toutbk)
    {
        //
    }
}
