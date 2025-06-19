<x-app-layout>
    {{-- BAGIAN HEADER HALAMAN --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            {{-- Bagian Kiri: Judul dan Tombol Kembali --}}
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Daftar Peserta
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Pelatihan: <strong>{{ $pelatihan->nama_pelatihan }} ({{ $pelatihan->kode_pelatihan }})</strong>
                </p>
                <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mt-2 inline-block">
                    &larr; Kembali ke Daftar Pelatihan
                </a>
            </div>
            
            {{-- Bagian Kanan: Kumpulan Tombol Aksi --}}
            <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                @if($pelatihan->kuota == 0 || $pelatihan->pesertas->count() < $pelatihan->kuota)
                <a href="{{ route('peserta.create', $pelatihan->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Tambah Peserta
                </a>
                @else
                <span class="px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest cursor-not-allowed">
                    Kuota Penuh
                </span>
                @endif
                {{-- Hanya tampilkan tombol jika ada peserta --}}
                @if($pesertas->count() > 0)
                    <a href="{{ route('pelatihan.pilihKolomAbsensi', $pelatihan->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" target="_blank">
                        Cetak Absensi
                    </a>
                    <a href="{{ route('pelatihan.downloadSemuaSertifikat', $pelatihan->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Download (.zip)
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- BAGIAN KONTEN UTAMA HALAMAN --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- WADAH UNTUK SEARCH & SORT --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0 sm:space-x-4">
                        <form action="{{ route('peserta.index', $pelatihan) }}" method="GET" class="w-full sm:w-1/2">
                            <div class="flex">
                                <input type="text" id="searchInput" name="search" placeholder="Cari Nama, Asal, atau No. Telepon..."
                                    class="w-full rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    value="{{ request('search') }}">
                                <button type="submit" id="searchButton" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none">
                                    Cari
                                </button>
                            </div>
                        </form>
                        <form action="{{ route('peserta.index', $pelatihan) }}" method="GET" class="w-full sm:w-auto">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="sort" onchange="this.form.submit()" class="w-full sm:w-auto rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="terlama" @if(request('sort', 'terlama') == 'terlama') selected @endif>Urutan: Pendaftaran Terlama</option>
                                <option value="terbaru" @if(request('sort') == 'terbaru') selected @endif>Urutan: Pendaftaran Terbaru</option>
                                <option value="nama_az" @if(request('sort') == 'nama_az') selected @endif>Urutan: Nama A-Z</option>
                                <option value="nama_za" @if(request('sort') == 'nama_za') selected @endif>Urutan: Nama Z-A</option>
                            </select>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Peserta</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. Telepon</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($pesertas as $peserta)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ($pesertas->currentPage() - 1) * $pesertas->perPage() + $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $peserta->nama_peserta }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $peserta->asal_peserta }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $peserta->nomor_telepon }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('peserta.cetakSertifikat', $peserta->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-3" target="_blank">Sertifikat</a>
                                            <a href="{{ route('peserta.edit', $peserta->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3">Edit</a>
                                            <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" onclick="return confirm('Anda yakin ingin menghapus peserta ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(request('search'))
                                                Peserta dengan kata kunci tersebut tidak ditemukan.
                                            @else
                                                Belum ada peserta di pelatihan ini.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $pesertas->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <script>
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');

        const checkInput = () => {
            if (searchInput.value.trim() !== '') {
                searchButton.disabled = false;
                searchButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                searchButton.disabled = true;
                searchButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        };

        searchInput.addEventListener('keyup', checkInput);
        window.addEventListener('load', checkInput);
    </script>
</x-app-layout>