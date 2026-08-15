<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Hasil;
use App\Models\Judulutbk;
use App\Models\Ujian;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JawabanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $index = 0)
    {
        
        $jumlah_soal = Soal::where('ujian_id', $request->ujian_id)->count();

        $index = max(0, min($index, $jumlah_soal - 1));
        return view('tryout.kerjakan', compact('index', 'jumlah_soal'));
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

        Jawaban::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'soal_id' => $request->soal_id,
        ],
        [
            'ujian_id' => $request->ujian_id,
            'jawaban'  => $request->jawaban,
        ]
    );

    return redirect()->route('tryout.mulai', $request->ujian_id);

    }

    /**
     * Display the specified resource.
     */
    public function show(Jawaban $jawaban)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jawaban $jawaban)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jawaban $jawaban)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jawaban $jawaban)
    {
        
    }


    public function jawab(Request $request)
    {
    $request->validate([
        'soal_id'  => 'required|exists:soals,id',
        'ujian_id' => 'required|exists:ujians,id',
        
        'jawaban'  => 'required|in:A,B,C,D',
        'index'    => 'required|integer',
    ]);

    Jawaban::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'soal_id' => $request->soal_id,
            'ujian_id'=> $request->ujian_id,
        ],
        [
            'jawaban' => $request->jawaban
        ]
    );

    $hasil = Hasil::where('user_id', Auth::id())
    ->where('ujian_id', $request->ujian_id)
    ->first();

    if ($hasil && $hasil->selesai) {
        return redirect()->route('tryout.hasil', $request->ujian_id);
    }


    $index = $request->index;

    if ($request->has('next')) $index++;
    elseif ($request->has('prev')) $index--;

    $jumlah_soal = Soal::where('ujian_id', $request->ujian_id)->count();
    $index = max(0, min($index, $jumlah_soal - 1));

    return redirect()->route('tryout.kerjakan', [
        'ujian' => $request->ujian_id,
        'index' => $index
    ]);
}

public function selesai(Ujian $ujian)
{
    $user_id = Auth::id();

    // Cegah dobel submit
    $hasil = Hasil::where('user_id', $user_id)
        ->where('ujian_id', $ujian->id)
        ->first();

    if ($hasil && $hasil->selesai) {
        return redirect()->route('tryout.hasil', $ujian->id);
    }

    $jawaban_user = Jawaban::where('user_id', $user_id)
        ->where('ujian_id', $ujian->id)
        ->get();

    $soal_list = $ujian->soals;

    if ($soal_list->count() === 0) {
        return redirect()->back()->with('error', 'Soal belum tersedia.');
    }

    $jumlah_benar = 0;

    foreach ($soal_list as $soal) {
        $jawaban = $jawaban_user
            ->firstWhere('soal_id', $soal->id)
            ->jawaban ?? null;

        if ($jawaban === $soal->jawaban_benar) {
            $jumlah_benar++;
        }
    }

    $skor = round(($jumlah_benar / $soal_list->count()) * 100);

    // SIMPAN HASIL (WAJIB SEBELUM PINDAH HALAMAN)
    Hasil::updateOrCreate(
        [
            'user_id' => $user_id,
            'ujian_id' => $ujian->id,
        ],
        [
            'skor' => $skor,
            'selesai' => true,
        ]
    );

    // 🔥 INI KUNCINYA
    return redirect()->route('tryout.hasil', $ujian->id);
}



}