<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MandiriController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\TryoutController;
use App\Http\Controllers\JawabanController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\JawabanutbkController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UjianController as AdminUjianController; // jangan di hapus
use App\Http\Controllers\Admin\AdminSoalController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UtbkController;
use App\Http\Controllers\JudulutbkController;
use App\Http\Controllers\ToutbkController;
use App\Models\Judulutbk;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('home'); })->name('home');
//Route::get('/soal', [HomeController::class, 'index'])->name('index.soal');
Route::get('/about', [HomeController::class, 'about'])->name('about.about');
Route::get('/baca', [HomeController::class, 'baca'])->name('about.baca');
Route::get('/fasilitas', [HomeController::class, 'fasilitas'])->name('detail.fasilitas');
Route::get('/program', [HomeController::class, 'program'])->name('program');
Route::get('/pengajar', [HomeController::class, 'pengajar'])->name('detail.pengajar');
Route::get('/pembelajaran', [HomeController::class, 'belajar'])->name('detail.pembelajaran');
Route::post('/pembelajaran/upload', [HomeController::class, 'uploadVideo'])->name('pembelajaran.upload');
    

// Halaman Statis (Opsional, jika tidak ada controller)
Route::get('/belajar', function () { return view('belajar'); })->name('belajar');

// Mandiri (Public View)
Route::resource('home', HomeController::class);
Route::get('/mandiri/{mandiri}', [HomeController::class, 'show'])->name('index.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

// Login & Register Views
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/daftar', [AuthController::class, 'daftar'])->name('daftar');

// Auth Processes
Route::post('/login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::post('/daftar', [AuthController::class, 'daftarProses'])->name('daftarProses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROLE: GURU (Administrator Ujian)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->group(function () {

    // Dashboard Guru
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Akun Guru
    Route::get('/dashboard/akun-guru', [AuthController::class, 'akunguru'])->name('akun-guru');
    Route::post('/dashboard/akun-guru', [AuthController::class, 'storeGuru'])->name('storeguru');

    // 1. MANAJEMEN MANDIRI (BANK SOAL / LATIHAN)
    Route::get('/mandiri', [MandiriController::class, 'index'])->name('mandiri.materi'); 
    Route::resource('mandiri', MandiriController::class)->except(['index']);
    Route::get('/mandiri/{mandiri}/edit', [MandiriController::class, 'edit'])->name('mandiri.materi-edit');
    Route::put('/mandiri/{mandiri}', [MandiriController::class, 'update'])->name('mandiri.update');

    // Akademik
    
    
    // Import Soal Latihan
    Route::post('/mandiri/latihan/import', [MandiriController::class, 'import']);

    // Mapel (Soal-soal Latihan Mandiri)
    Route::get('/mandiri/{mandiri}/mapel/create', [MapelController::class, 'create'])->name('mandiri.mapel');
    Route::post('/mandiri/{mandiri}/mapel', [MapelController::class, 'store'])->name('mapel.store');
    
    Route::get('/mandiri/{mandiri}/mapel/{mapel}/edit', [MapelController::class, 'edit'])->name('mapel.edit');
    Route::put('/mandiri/{mandiri}/mapel/{mapel}', [MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mandiri/{mandiri}/mapel/{mapel}', [MapelController::class, 'destroy'])->name('mapel.destroy');
    Route::post('/mapel/upload-image', [MapelController::class, 'upload'])->name('mapel.upload');
    Route::post('/mandiri/{mandiri}/mapel/import', [MapelController::class, 'importExcel'])->name('mapel.import');

    // Import bank soal dari file Word (.docx)
    Route::post('/mandiri/{mandiri}/mapel/import-word', [MapelController::class, 'importWord'])->name('mapel.import-word');
    Route::get('/mandiri/{mandiri}/mapel/import-word/{token}', [MapelController::class, 'importWordPreview'])->name('mapel.import-word.preview');
    Route::post('/mandiri/{mandiri}/mapel/import-word/{token}', [MapelController::class, 'importWordSimpan'])->name('mapel.import-word.simpan');

    // 2. MANAJEMEN UJIAN (TRYOUT / EXAM) Jangan di ubah
    Route::resource('ujian', UjianController::class);
    Route::post('/ujian/{ujian}/toggle', [UjianController::class, 'toggle'])->name('ujian.toggle');

    //Jangan di ubah
    Route::get('ujian/{ujian}/soal/create', [SoalController::class, 'create'])->name('soal.create');
    Route::post('/ujian/{ujian}/soal', [SoalController::class, 'store'])->name('soal.store');
    
    //Jangan di ubah
    Route::get('/ujian/{ujian}/soal/{soal}/edit', [SoalController::class, 'edit'])->name('soal.edit');
    Route::put('/ujian/{ujian}/soal/{soal}', [SoalController::class, 'update'])->name('soal.update');
    Route::delete('/ujian/{ujian}/soal/{soal}', [SoalController::class, 'destroy'])->name('soal.destroy');

    //Jangan di ubah
    Route::post('/ujian/{ujian}/soal/import-excel', [SoalController::class, 'importExcel'])->name('soal.import.excel');
    Route::post('soal/upload', [SoalController::class, 'upload'])->name('soal.upload'); 
    Route::get('/ujian/{ujian}/hasil', [UjianController::class, 'hasil'])->name('ujian.hasil');
    Route::delete('/hasil/{id}/reset', [UjianController::class, 'reset'])->name('hasil.reset');

    // ROUTE KHUSUS UTBK (WAJIB DI ATAS)
    Route::get('/soal-utbk', [UtbkController::class, 'soal'])->name('utbk.soal');
    Route::get('/utbk', [UtbkController::class, 'index'])->name('utbk.index');

    // 3. MANAJEMEN UTBK (ADMIN)
    
    // Jangan di ubah
    Route::get('/judulutbk/{judulutbk}/utbk/create', [UtbkController::class, 'create'])->name('utbk.create');
    Route::post('/judulutbk/{judulutbk}/utbk', [UtbkController::class, 'store'])->name('utbk.store');
    Route::get('/judulutbk/{judulutbk}/utbk/{utbk}/edit', [UtbkController::class, 'edit'])->name('utbk.edit');
    Route::put('/judulutbk/{judulutbk}/utbk/{utbk}', [UtbkController::class, 'update'])->name('utbk.update');
    Route::delete('/judulutbk/{judulutbk}/utbk/{utbk}', [UtbkController::class, 'destroy'])->name('utbk.destroy');

    // Jududl UTBK
    Route::post('/judulutbk', [JudulutbkController::class, 'store'])->name('judulutbk.store');
    Route::get('/judulutbk/{judulutbk}', [JudulutbkController::class, 'show'])->name('utbk.show');
    Route::get('/judulutbk/{judulutbk}/judul-edit', [JudulutbkController::class, 'edit'])->name('utbk.judul-edit');
    Route::put('/utbk/{utbk}', [UtbkController::class, 'update'])->name('utbk.update');
    Route::delete('/judulutbk/{judulutbk}', [JudulutbkController::class, 'destroy'])->name('judulutbk.destroy');
    Route::post('/judulutbk/{id}/import', [JudulutbkController::class, 'import'])->name('judulutbk.import');

    });

/*
|--------------------------------------------------------------------------
| ROLE: SISWA (Peserta Ujian)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/soal/{semester}/{kelas}/{pelajaran}', [HomeController::class, 'index'])->name('index.soal');
    Route::get('/mandiri/{mandiri}/lihat-soal', [HomeController::class, 'lihat'])->name('index.lihat-soal');
    Route::get('/ruang/show', [RuangController::class, 'show'])->name('ruang.show');
        Route::get('/ruang/berlatih/materi', [RuangController::class, 'materi'])->name('ruang.berlatih.materi');
    Route::get('/ruang', [RuangController::class, 'index'])->name('ruang.index');
    Route::get('/tryout', [TryoutController::class, 'index'])->name('tryout.index');
    Route::get('/tryout/{ujian}', [TryoutController::class, 'show'])->name('tryout.show');
    Route::middleware(['nocache', 'cek.ujian.selesai'])->group(function () {
        Route::get('/tryout/{ujian}/kerjakan/{index?}', [TryoutController::class, 'kerjakan'])->name('tryout.kerjakan');
        Route::post('/tryout/jawab', [JawabanController::class, 'jawab'])->name('tryout.jawab');
        Route::post('/ujian/pelanggaran', [TryoutController::class, 'pelanggaran'])->name('ujian.pelanggaran');
        Route::post('/ujian/keluar', [TryoutController::class, 'keluar'])->name('ujian.keluar');
    Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik.semester');
    Route::get('/akademik/semester/{semester}/{kelas}', [AkademikController::class, 'mapel'])->name('akademik.mapel');
    Route::get('/akademik/semester/{semester}', [AkademikController::class, 'kelas'])->name('akademik.kelas');
        Route::get('/utbk', [UtbkController::class, 'index'])->name('utbk.index');
        Route::get('/utbk/{judulutbk}', [ToutbkController::class, 'show'])->name('to-utbk.show');
        Route::get('/utbk/{judulutbk}/kerjakan/{index?}', [ToutbkController::class, 'kerjakan'])->name('to-utbk.kerjakan');
        Route::post('/utbk/jawab', [JawabanutbkController::class, 'jawaban'])->name('to-utbk.jawab');
        Route::get('/utbk', [ToutbkController::class, 'index'])->name('to-utbk.to_utbk');
         // JSON API endpoints
        Route::get('/tryout/{ujian}/session', [TryoutController::class, 'session'])->name('tryout.session');
        Route::post('/tryout/{ujian}/answer', [TryoutController::class, 'answer'])->name('tryout.answer');
        Route::post('/tryout/{ujian}/finish', [TryoutController::class, 'finish'])->name('tryout.finish');
        Route::post('/tryout/{ujian}/violation-events', [TryoutController::class, 'violationEvents'])->name('tryout.violation-events');
    
    });

    // Akhiri Ujian
    Route::post('/tryout/{ujian}/selesai', [JawabanController::class, 'selesai'])->name('tryout.selesai');
    Route::post('/utbk/{judulutbk}/selesai', [ToutbkController::class, 'akhiri'])->name('to-utbk.selesai');
    // Lihat Hasil Ujian
    Route::get('/tryout/{ujian}/hasil', [HasilController::class, 'hasil'])->name('tryout.hasil');
    Route::get('/utbk/{judulutbk}/hasil', [HasilController::class, 'nilai'])->name('to-utbk.hasil');
    // Reset Ujian (Opsional / Debugging)
    Route::get('/tryout/{ujian}/reset', [TryoutController::class, 'reset'])->name('tryout.reset');

    Route::get('/ganti-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/ganti-password', [PasswordController::class, 'update'])->name('password.update');

});

/*
|--------------------------------------------------------------------------
| ROLE: ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('admin/guru', GuruController::class)->names('guru');
    Route::resource('admin/siswa', SiswaController::class)->names('siswa');
    Route::resource('admin/ujian', AdminUjianController::class)->names('admin.ujian'); //jangan di ganti
    Route::get('/admin/ujian/{ujian}/hasil', [AdminUjianController::class, 'hasil'])->name('admin.ujian.hasil'); //jangan di ganti
    Route::delete('/admin/hasil/{id}/reset', [AdminUjianController::class, 'reset'])->name('admin.hasil.reset'); //jangan di ganti

    Route::prefix('admin')->name('admin.')->group(function() {
        
        // Buat & Simpan Soal (Butuh ID Ujian)
        Route::get('ujian/{ujian}/soal/create', [AdminSoalController::class, 'create'])->name('soal.create');
        Route::post('ujian/{ujian}/soal', [AdminSoalController::class, 'store'])->name('soal.store');
        
        // Edit, Update, Hapus Soal (Butuh ID Soal)
        Route::get('soal/{soal}/edit', [AdminSoalController::class, 'edit'])->name('soal.edit');
        Route::put('soal/{soal}', [AdminSoalController::class, 'update'])->name('soal.update');
        Route::delete('soal/{soal}', [AdminSoalController::class, 'destroy'])->name('soal.destroy');

        // Upload Gambar CKEditor (Admin)
        Route::post('soal/upload-image', [AdminSoalController::class, 'upload'])->name('soal.upload');
    });
});