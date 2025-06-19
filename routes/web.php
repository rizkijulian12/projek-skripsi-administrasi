<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController; // <-- TAMBAHKAN INI JIKA BELUM ADA

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [PelatihanController::class, 'index'])->name('dashboard');

    // ==========================================================
    // --- TAMBAHKAN RUTE PROFIL INI ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // --- AKHIR BAGIAN BARU ---
    // ==========================================================

    // Semua route aplikasi Anda yang sudah ada
    Route::resource('pelatihan', PelatihanController::class);
    // ... sisa rute Anda yang lain ...
    Route::get('pelatihan/{pelatihan}/peserta', [PesertaController::class, 'index'])->name('peserta.index');
    Route::get('pelatihan/{pelatihan}/peserta/create', [PesertaController::class, 'create'])->name('peserta.create');
    Route::post('pelatihan/{pelatihan}/peserta', [PesertaController::class, 'store'])->name('peserta.store');
    Route::get('peserta/{peserta}/edit', [PesertaController::class, 'edit'])->name('peserta.edit');
    Route::put('peserta/{peserta}', [PesertaController::class, 'update'])->name('peserta.update');
    Route::delete('peserta/{peserta}', [PesertaController::class, 'destroy'])->name('peserta.destroy');
    Route::get('pelatihan/{pelatihan}/absensi/cetak', [PelatihanController::class, 'cetakAbsensi'])->name('pelatihan.cetakAbsensi');
    Route::get('peserta/{peserta}/sertifikat/cetak', [PesertaController::class, 'cetakSertifikat'])->name('peserta.cetakSertifikat');
    Route::get('pelatihan/{pelatihan}/download-semua-sertifikat', [PelatihanController::class, 'downloadSemuaSertifikat'])->name('pelatihan.downloadSemuaSertifikat');
    // Rute ini yang akan menampilkan halaman form kustomisasi
    Route::get('pelatihan/{pelatihan}/absensi/pilih-kolom', [PelatihanController::class, 'pilihKolomAbsensi'])->name('pelatihan.pilihKolomAbsensi');
});


require __DIR__.'/auth.php';