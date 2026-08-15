<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Judulutbk;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function hasil(Ujian $ujian)
    {
       $hasil = Hasil::where('user_id', Auth::id())
        ->where('ujian_id', $ujian->id)
        ->first();

    if (!$hasil) {
        return redirect()->route('tryout.index')
            ->with('error', 'Hasil ujian belum tersedia.');
    }

    return view('tryout.hasil', compact('hasil', 'ujian'));
    }

    public function nilai(Judulutbk $judulutbk)
    {
        $nilai = Hasil::where('user_id', Auth::id())
            ->where('judulutbk_id', $judulutbk->id)
            ->first();

        if (!$nilai) {
            return redirect()->route('to-utbk.to_utbk')
                ->with('error', 'Hasil ujian belum tersedia.');
        }

        return view('to-utbk.hasil', [
            'judulutbk' => $judulutbk,
            'nilai' => $nilai
        ]);
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
    public function show(Hasil $hasil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hasil $hasil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hasil $hasil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hasil $hasil)
    {
        //
    }
}
