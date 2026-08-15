<?php

namespace App\Http\Controllers;

use App\Models\Jawabanutbk;
use App\Models\Hasil;
use App\Models\Judulutbk;
use App\Models\Utbk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JawabanutbkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Jawabanutbk $jawabanutbk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jawabanutbk $jawabanutbk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jawabanutbk $jawabanutbk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jawabanutbk $jawabanutbk)
    {
        //
    }

    public function jawaban(Request $request)
    {
    $request->validate([
        'utbk_id'  => 'required|exists:utbks,id',
        'judulutbk_id' => 'required|exists:judulutbks,id',
        
        'jawaban'  => 'required|in:A,B,C,D,E',
        'index'    => 'required|integer',
    ]);

    Jawabanutbk::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'utbk_id' => $request->utbk_id,
            'judulutbk_id'=> $request->judulutbk_id,
        ],
        [
            'jawaban' => $request->jawaban
        ]
    );

    $hasil = Hasil::where('user_id', Auth::id())
    ->where('judulutbk_id', $request->judulutbk_id)
    ->first();

    $index = $request->index;

    if ($request->has('next')) $index++;
    elseif ($request->has('prev')) $index--;

    $jumlah_soal = Utbk::where('judulutbk_id', $request->judulutbk_id)->count();
    $index = max(0, min($index, $jumlah_soal - 1));

    return redirect()->route('to-utbk.kerjakan', [
    'judulutbk' => $request->judulutbk_id,
    'index' => $index
]);
}
public function selesai(Judulutbk $judulutbk)
{
    $user_id = Auth::id();

    // Cegah dobel submit
    $hasil = Hasil::where('user_id', $user_id)
        ->where('judulutbk_id', $judulutbk->id)
        ->first();

    if ($hasil && $hasil->selesai) {
        return redirect()->route('to-utbk.hasil', $judulutbk->id)
            ->with('warning', 'Anda sudah menyelesaikan ujian ini.');
    }

    $jawaban_user = Jawabanutbk::where('user_id', $user_id)
        ->where('judulutbk_id', $judulutbk->id)
        ->get();

    $soal_list = $judulutbk->utbk;

    if ($soal_list->count() === 0) {
        return redirect()->back()->with('error', 'Soal belum tersedia.');
    }

    $jumlah_benar = 0;

    foreach ($soal_list as $soal) {
        $jawaban = $jawaban_user
            ->firstWhere('utbk_id', $soal->id)
            ->jawaban ?? null;

        if ($jawaban === $soal->jawaban_benar) {
            $jumlah_benar++;
        }
    }

        $total = $soal_list->count();

        $persen = $total > 0 ? ($jumlah_benar / $total) : 0;

        $skor = round(300 + ($persen * 400));

    // SIMPAN HASIL (WAJIB SEBELUM PINDAH HALAMAN)
    Hasil::updateOrCreate(
        [
            'user_id' => $user_id,
            'judulutbk_id' => $judulutbk->id,
        ],
        [
            'skor' => $skor,
            'sudah' => true,
        ]
    );

    // 🔥 INI KUNCINYA
    return redirect()->route('to-utbk.hasil',  $judulutbk->id)
        ->with('success', 'Ujian telah diselesaikan. Skor Anda: ' . $skor );
}
}
