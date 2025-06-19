<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat - {{ $peserta->nama_peserta }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            font-family: "Times New Roman", Times, serif;
        }

        .sertifikat-container {
            position: relative; /* Wajib ada sebagai acuan untuk elemen absolute */
            width: 100%;
            height: 100%;
        }

        /* --- KODE BARU UNTUK GAMBAR LATAR --- */
        .sertifikat-bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            /*
            | Properti ini adalah kuncinya.
            | 'contain' akan memastikan seluruh gambar terlihat (zoom out),
            | meskipun mungkin ada sedikit spasi putih jika rasio tidak 100% pas.
            | Ini akan mengatasi masalah 'ngezoom'.
            */
            object-fit: contain;

            /* Letakkan gambar ini di lapisan paling belakang */
            z-index: -1;
        }
        /* --- AKHIR KODE BARU --- */


        /* Kode untuk posisi teks tidak banyak berubah */
        .nomor-sertifikat, .nama-peserta, .deskripsi-pelatihan, .lokasi-tanggal {
            position: absolute;
            color: #000000;
        }

        .nomor-sertifikat {
            top: 232px;
            left: 440px;
            font-size: 22px;
        }

        .nama-peserta {
            top: 340px;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            font-size: 45px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .deskripsi-pelatihan {
            top: 440px;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            font-size: 20px;
            line-height: 1.4;
        }
        
        .nama-pelatihan-spesifik {
            font-weight: bold;
            font-style: italic;
        }

        .lokasi-tanggal {
            top: 573px;
            left: 0px;
            width: 100%;
            text-align: center;
            font-size: 20px;
        }

    </style>
</head>
<body>
    <div class="sertifikat-container">

        <img src="{{ public_path('images/sertifikat-background.png') }}" class="sertifikat-bg-image">

        <div class="nomor-sertifikat">
            {{ $nomorSertifikat }}
        </div>

        <div class="nama-peserta">
            {{ $peserta->nama_peserta }}
        </div>
        
        <div class="deskripsi-pelatihan">
            Telah mengikuti pelatihan <span class="nama-pelatihan-spesifik">"{{ $peserta->pelatihan->nama_pelatihan }}"</span>
            <br>Dengan Baik
        </div>

        <div class="lokasi-tanggal">
            Banjarbaru, {{ $tanggalCetak->translatedFormat('d F Y') }}
        </div>

    </div>
</body>
</html>