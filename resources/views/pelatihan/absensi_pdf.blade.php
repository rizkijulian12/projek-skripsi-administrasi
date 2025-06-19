<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir - {{ $pelatihan->nama_pelatihan }}</title>
    <style>
        /* CSS Anda dari sebelumnya sudah bagus, kita gunakan lagi di sini */
        @page { margin: 40px 50px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { display: table; width: 100%; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { display: table-cell; width: 100px; vertical-align: middle; }
        .logo img { width: 90px; height: auto; }
        .header-text { display: table-cell; vertical-align: middle; text-align: center; }
        .header-text h1 { margin: 0; font-size: 20px; }
        .header-text p { margin: 5px 0 0 0; font-size: 14px; }
        .info-pelatihan { margin-bottom: 20px; }
        .info-pelatihan td { padding: 2px 0; }
        .data-peserta { width: 100%; border-collapse: collapse; }
        .data-peserta th, .data-peserta td { border: 1px solid #888; padding: 8px; text-align: left; }
        .data-peserta th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 40px; width: 100%; }
        .signature-box { width: 300px; float: right; text-align: center; }
        .signature-box .signature-space { height: 80px; border-bottom: 1px solid black; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            {{-- Pastikan logo.png ada di public/images --}}
            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        </div>
        <div class="header-text">
            <h1>DAFTAR HADIR PESERTA</h1>
            <p>AS ACADEMY</p>
        </div>
    </div>

    <table class="info-pelatihan">
        <tr>
            <td width="150px"><strong>Nama Pelatihan</strong></td>
            <td width="10px">:</td>
            <td>{{ $pelatihan->nama_pelatihan }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pelaksanaan</strong></td>
            <td>:</td>
            <td>{{ $pelatihan->tanggal_pelatihan ? $pelatihan->tanggal_pelatihan->translatedFormat('d F Y') : 'N/A' }}</td>
        </tr>
    </table>

    <table class="data-peserta">
        <thead>
            <tr>
                {{-- Bagian Header Tabel Dibuat Dinamis --}}
                <th style="width: 5%; text-align: center;">No.</th>

                {{-- Loop untuk header dari kolom DATA --}}
                @foreach ($kolomData as $key)
                    <th>{{ $labels[$key] ?? 'Kolom tidak dikenal' }}</th>
                @endforeach

                {{-- Loop untuk header dari kolom KUSTOM (kosong) --}}
                @foreach ($kolomKustom as $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- Loop untuk menampilkan data peserta --}}
            @foreach ($pesertas as $peserta)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>

                {{-- Loop untuk mengisi sel DATA sesuai pilihan checkbox --}}
                @foreach ($kolomData as $key)
                    <td>
                        @if($key == 'tanggal_lahir' && $peserta->{$key})
                            {{ $peserta->{$key}->format('d/m/Y') }}
                        @else
                            {{ $peserta->{$key} }}
                        @endif
                    </td>
                @endforeach

                {{-- Loop untuk membuat sel KOSONG sesuai inputan manual --}}
                @foreach ($kolomKustom as $label)
                    <td></td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>