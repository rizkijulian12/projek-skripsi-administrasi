<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class PesertaController extends Controller
{
    /**
     * Menampilkan daftar semua peserta untuk pelatihan tertentu.
     */
    public function index(Request $request, Pelatihan $pelatihan)
    {
        // Ambil input dari request
    $searchTerm = $request->input('search');
    $sortOption = $request->input('sort');

    // Mulai query dari relasi untuk memastikan hanya peserta dari pelatihan ini yang diambil
    $query = $pelatihan->pesertas();

    // --- BLOK KODE UNTUK SEARCH ---
    if ($searchTerm) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('nama_peserta', 'like', '%' . $searchTerm . '%')
              ->orWhere('asal_peserta', 'like', '%' . $searchTerm . '%')
              ->orWhere('nomor_telepon', 'like', '%' . $searchTerm . '%');
        });
    }

    // --- BLOK KODE BARU UNTUK SORTING ---
    switch ($sortOption) {
        case 'nama_az':
            $query->orderBy('nama_peserta', 'asc');
            break;
        case 'nama_za':
            $query->orderBy('nama_peserta', 'desc');
            break;
        case 'terbaru':
            $query->orderBy('created_at', 'desc');
            break;
        case 'terlama':
            $query->orderBy('created_at', 'asc');
            break;
        default:
            // Urutan default jika tidak ada pilihan
            $query->orderBy('id', 'asc');
            break;
    }

    // Ambil data dengan pagination
    $pesertas = $query->paginate(10);

    // Kirim data ke view
    return view('peserta.index', compact('pelatihan', 'pesertas'));
    }

    /**
     * Menampilkan form untuk membuat peserta baru.
     */
    public function create(Pelatihan $pelatihan)
    {
        return view('peserta.create', compact('pelatihan'));

        
    }

    /**
     * Menyimpan data peserta baru ke dalam database.
     */
    public function store(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'asal_peserta' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
        ]);

        $pelatihan->pesertas()->create($request->all());

        return redirect()->route('peserta.index', $pelatihan->id)
                         ->with('success', 'Peserta baru berhasil ditambahkan.');
    }
    
    /**
     * Membuat dan menampilkan sertifikat PDF untuk peserta tertentu.
     */
    public function cetakSertifikat(Peserta $peserta)
    {
       $peserta->load('pelatihan');
    
    // ==========================================================
    // LANGKAH 1: Buat variabel yang berisi tanggal & waktu saat ini
        $tanggalCetak = Carbon::now();
    // ==========================================================
        $semuaPesertaDalamPelatihan = $peserta->pelatihan->pesertas()->orderBy('id', 'asc')->get();

        // 2. Cari posisi (index) peserta saat ini di dalam daftar tersebut.
        //    Collection di Laravel memiliki fungsi search() yang mengembalikan index (0, 1, 2, ...).
        $indexPeserta = $semuaPesertaDalamPelatihan->search(function($item) use ($peserta) {
            return $item->id == $peserta->id;
        });

        // 3. Nomor urut yang kita inginkan adalah index + 1 (karena index dimulai dari 0).
        $nomorUrut = $indexPeserta + 1;

        // --- AKHIR LOGIKA BARU ---
        // ==========================================================
        
        // Ambil data lain seperti biasa
        $kodePelatihan = $peserta->pelatihan->kode_pelatihan;
        $tahunTerbit = $tanggalCetak->year;
        
        // Gabungkan dengan nomor urut yang BARU kita hitung
        $nomorSertifikat = "Nomor: SERT/{$kodePelatihan}/{$tahunTerbit}/{$nomorUrut}";

        // Kirim semua data ke view (tidak ada yang berubah di sini)
        $pdf = PDF::loadView('peserta.sertifikat_pdf', compact('peserta', 'tanggalCetak', 'nomorSertifikat'));
        
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('sertifikat-' . $peserta->nama_peserta . '.pdf');
}

    public function edit(Peserta $peserta)
    {
        return view('peserta.edit', compact('peserta'));
    }

    /**
     * Memperbarui data peserta di database.
     */
    public function update(Request $request, Peserta $peserta)
    {
        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'asal_peserta' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
        ]);

        $peserta->update($request->all());

        // Redirect kembali ke halaman daftar peserta dari pelatihan asalnya
        return redirect()->route('peserta.index', $peserta->pelatihan_id)
                         ->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Menghapus data peserta.
     */
    public function destroy(Peserta $peserta)
    {
        // Simpan dulu id pelatihannya untuk redirect
        $pelatihan_id = $peserta->pelatihan_id;
        
        $peserta->delete();

        return redirect()->route('peserta.index', $pelatihan_id)
                         ->with('success', 'Data peserta berhasil dihapus.');
    }
}