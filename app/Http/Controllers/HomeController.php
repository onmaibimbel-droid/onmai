<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Mandiri;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($semester, $kelas, $pelajaran)
    {
        $mandiri = Mandiri::where('semester', $semester)
                      ->where('kelas', $kelas)
                      ->where('pelajaran', $pelajaran)
                      ->get();
        return view('index.soal', compact('mandiri', 'semester', 'kelas', 'pelajaran'));
    }


    /// About (folder about/about)
    public function about()
    {
        return view('about.about');
    }


    /// Fasilitas (folder detail/fasilitas)    
    public function fasilitas()
    {
        return view('detail.fasilitas');
    }

    /// Program
    public function program()
    {
        return view('program');
    }

    /// Pengajar Detail/pengajar

    public function pengajar()
    {
        return view('detail.pengajar');
    }

    /// Pembelajaran Detail/Pembelajaran

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:51200', // 50MB
        ]);

        if ($request->hasFile('video')) {

            $video = $request->file('video');
            $namaFile = time() . '_' . $video->hashName();

            $video->move(
                public_path('landing-page/assets/vid'),
                $namaFile
            );

            return view('detail.pembelajaran', [
                'video' => $namaFile
            ]);
        }

        return back()->with('error', 'Video gagal diupload');
    }
    public function belajar()
    {
        return view('detail.pembelajaran');
    }

    public function baca()
    {
        return view('about.baca');
    }

    public function lihat(Mandiri $mandiri)
    {
        $mapel = $mandiri->mapels()->orderBy('id')->get();

        if ($mapel->isEmpty()) {
            return redirect()->back()->with('error', 'Mapel ini belum memiliki soal.');
        }

        return view('index.lihat-soal', compact('mandiri', 'mapel'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(home $home)
    {
        return view('home.show', compact('home'));
    }

    public function edit(home $home)
    {
        //
    }

    public function update(Request $request, home $home)
    {
        //
    }

    public function destroy(home $home)
    {
        //
    }
}