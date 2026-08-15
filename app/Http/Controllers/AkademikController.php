<?php

namespace App\Http\Controllers;

use App\Models\Akademik;
use App\Models\Mandiri;
use App\Models\Mapel;
use Illuminate\Http\Request;

class AkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('akademik.semester');
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

    public function kelas($semester)
    {

    
        $kelasList = Mandiri::where('semester', $semester)
                            ->select('kelas')
                            ->distinct()
                            ->orderBy('kelas')
                            ->get();
        return view('akademik.kelas', compact('semester', 'kelasList'));
    }

     public function mapel($semester, $kelas)
    {
            $mapels = Mandiri::where('semester', $semester)
                     ->where('kelas', $kelas)
                     ->select('pelajaran')
                     ->distinct()
                     ->orderBy('pelajaran')
                     ->get();


    return view('akademik.mapel', compact('mapels', 'semester', 'kelas'));
    }
    

     public function ujian($nama_ujian)
    {

    
        $kelasList = Mandiri::where('nama_ujian', $nama_ujian)
                            ->select('kelas')
                            ->distinct()
                            ->orderBy('kelas')
                            ->get();
        return view('akademik.kelas', compact('semester', 'kelasList'));
    }
    /**
     * Display the specified resource.
     */
    public function show(Akademik $akademik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Akademik $akademik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Akademik $akademik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Akademik $akademik)
    {
        //
    }
}
