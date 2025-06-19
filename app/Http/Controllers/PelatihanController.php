<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

$dataPelatihan = Pelatihan::find(1);
$semuaPelatihan = Pelatihan::all();


class PelatihanController extends Controller
{
    public function index(Request $request)
{
    // --- BAGIAN 1: MENGHITUNG DATA UNTUK KARTU STATISTIK ---
    $totalPelatihan = Pelatihan::count();
    $totalPeserta = Peserta::count();
    $pelatihanAktif = Pelatihan::where('status', 'Berlangsung')->orWhere('status', 'Segera Hadir')->count();
    $pesertaBulanIni = Peserta::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

    // --- BAGIAN 2: MENYIAPKAN DATA UNTUK GRAFIK (6 BULAN TERAKHIR) ---
    $chartData = Peserta::select(
            DB::raw('COUNT(*) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

    $chartLabels = $chartData->map(function ($item) {
        return Carbon::createFromFormat('Y-m', $item->month)->translatedFormat('F Y');
    });
    $chartValues = $chartData->pluck('count');


    // --- BAGIAN 3: LOGIKA UNTUK TABEL (SEARCH & SORT) ---
    $query = Pelatihan::query();
    $searchTerm = $request->input('search');
    $sortOption = $request->input('sort', 'tanggal_terbaru');

    if ($searchTerm) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('nama_pelatihan', 'like', '%' . $searchTerm . '%')
              ->orWhere('kode_pelatihan', 'like', '%' . $searchTerm . '%');
        });
    }

    switch ($sortOption) {
        case 'nama_az': $query->orderBy('nama_pelatihan', 'asc'); break;
        case 'nama_za': $query->orderBy('nama_pelatihan', 'desc'); break;
        case 'instruktur_az': $query->orderBy('nama_instruktur', 'asc'); break;
        case 'status': $query->orderBy('status', 'asc'); break;
        case 'tanggal_terlama': $query->orderBy('tanggal_pelatihan', 'asc'); break;
        default: $query->orderBy('tanggal_pelatihan', 'desc'); break;
    }

    $pelatihans = $query->withCount('pesertas')->paginate(10);

    // --- BAGIAN 4: MENGIRIM SEMUA DATA KE VIEW ---
    return view('pelatihan.index', compact(
        'pelatihans',
        'totalPelatihan',
        'totalPeserta',
        'pelatihanAktif',
        'pesertaBulanIni',
        'chartLabels',
        'chartValues'
    ));
}
    public function create()
    {
        return view('pelatihan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'kode_pelatihan' => 'required|string|unique:pelatihans,kode_pelatihan',
            'tanggal_pelatihan' => 'nullable|date',
        ]);

        Pelatihan::create($request->all());

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    public function cetakAbsensi(Request $request, Pelatihan $pelatihan)
    {
         $kolomData = $request->input('kolom_data', []);
        $kolomKustom = $request->input('kolom_kustom', []);
        $pesertas = $pelatihan->pesertas;
        $labels = [
            'nama_peserta' => 'Nama Peserta',
            'asal_peserta' => 'Asal Peserta',
            'nomor_telepon' => 'No. Telepon',
            'tanggal_lahir' => 'Tanggal Lahir',
        ];

        $pdf = PDF::loadView('pelatihan.absensi_pdf', compact('pelatihan', 'pesertas', 'kolomData', 'kolomKustom', 'labels'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('absensi_kustom_' . $pelatihan->kode_pelatihan . '.pdf');
    }
    /**
     * Mengunduh semua sertifikat peserta dalam satu file zip.
     */
    public function downloadSemuaSertifikat(Pelatihan $pelatihan)
    {
        set_time_limit(300);
        // 1. Buat instance ZipArchive
        $zip = new ZipArchive;

        // 2. Buat nama file zip yang unik
        $namaFileZip = 'Sertifikat_' . preg_replace('/[^A-Za-z0-9\-]/', '', $pelatihan->kode_pelatihan) . '.zip';
        $pathFileZip = storage_path('app/public/' . $namaFileZip);

        // 3. Pastikan direktori temp ada
        if (!File::isDirectory(storage_path('app/public'))) {
            File::makeDirectory(storage_path('app/public'), 0755, true);
        }

        // 4. Buka file zip untuk ditulis
        if ($zip->open($pathFileZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            
            // 5. Ambil semua peserta dan data terkait
            $semuaPeserta = $pelatihan->pesertas()->orderBy('id', 'asc')->get();
            $tanggalCetak = Carbon::now();

            // 6. Loop untuk setiap peserta
            foreach ($semuaPeserta as $index => $peserta) {
                
                // --- Logika penomoran sertifikat ---
                $nomorUrut = $index + 1;
                $kodePelatihan = $pelatihan->kode_pelatihan;
                $tahunTerbit = $tanggalCetak->year;
                $nomorSertifikat = "Nomor: SERT/{$kodePelatihan}/{$tahunTerbit}/{$nomorUrut}";

                // --- Buat PDF di memori ---
                $pdf = PDF::loadView('peserta.sertifikat_pdf', compact('peserta', 'tanggalCetak', 'nomorSertifikat'));
                $pdf->setPaper('a4', 'landscape');
                
                $kontenPdf = $pdf->output();
                
                $namaFilePdfDiDalamZip = 'Sertifikat_' . $nomorUrut . '_' . $peserta->nama_peserta . '.pdf';
                
                $zip->addFromString($namaFilePdfDiDalamZip, $kontenPdf);
            }

            // 7. Setelah loop selesai, tutup file zip
            $zip->close();
        } else {
            return back()->with('error', 'Gagal membuat file arsip.');
        }

        // 8. Unduh file zip ke browser dan hapus setelah diunduh
        return response()->download($pathFileZip)->deleteFileAfterSend(true);
    }
    public function edit(Pelatihan $pelatihan)
    {
        return view('pelatihan.edit', compact('pelatihan'));
    }

    /**
     * Memperbarui data di dalam database.
     */
    public function update(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            // Pastikan kode pelatihan unik, tapi abaikan id pelatihan ini sendiri
            'kode_pelatihan' => ['required', 'string', Rule::unique('pelatihans')->ignore($pelatihan->id)],
            'tanggal_pelatihan' => 'nullable|date',
        ]);

        $pelatihan->update($request->all());

        return redirect()->route('pelatihan.index')->with('success', 'Data pelatihan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(Pelatihan $pelatihan)
    {
        $pelatihan->delete();

        return redirect()->route('pelatihan.index')->with('success', 'Data pelatihan berhasil dihapus.');
    }
    public function pilihKolomAbsensi(Pelatihan $pelatihan)
    {
        return view('pelatihan.pilih-kolom-absensi', compact('pelatihan'));
    }
}