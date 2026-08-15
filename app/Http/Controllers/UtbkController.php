<?php

namespace App\Http\Controllers;

use App\Models\Utbk;
use App\Models\Judulutbk;
use Illuminate\Http\Request;

class UtbkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $judulutbk = Judulutbk::with('utbks')->get();
        $utbks = $judulutbk->utbks; // relasi
        return view('utbk.index', compact('judulutbk', 'utbks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Judulutbk $judulutbk)
    {
        
        return view('utbk.create', compact('judulutbk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Judulutbk $judulutbk)
    {
        $data = $request->validate([
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'opsi_e' => 'required',
            'jawaban_benar' => 'required',
        ]);

        $judulutbk->utbks()->create($data);

        return redirect()
            ->route('utbk.show', $judulutbk->id)
            ->with('success', 'Soal berhasil ditambahkan');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Utbk $utbk)
    {
        //
    }
    public function soal(Judulutbk $judulutbk)
    {
        $juduls = Judulutbk::with('utbks')->get();

        return view('utbk.soal', compact('juduls', 'judulutbk'));
        
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Judulutbk $judulutbk, Utbk $utbk)
    {
        return view('utbk.edit', compact('utbk', 'judulutbk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Judulutbk $judulutbk, Utbk $utbk)
    {
        $data = $request->validate([
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'opsi_e' => 'required',
            'jawaban_benar' => 'required',
        ]);

        $utbk->update($data);

        return redirect()
            ->route('utbk.show', $judulutbk->id)
            ->with('success', 'Soal berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Judulutbk $judulutbk, Utbk $utbk)
    {
        $utbk->delete();

        return redirect()
        ->route('utbk.show', $judulutbk->id)
        ->with('success', 'Soal berhasil dihapus');
    }
}
