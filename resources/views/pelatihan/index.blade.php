<x-app-layout>
    {{-- BAGIAN HEADER HALAMAN --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Pelatihan') }}
            </h2>
            
            <a href="{{ route('pelatihan.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Tambah Pelatihan Baru
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Pelatihan</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalPelatihan }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Peserta</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalPeserta }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pelatihan Aktif</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $pelatihanAktif }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Peserta Bulan Ini</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $pesertaBulanIni }}</p>
                </div>
            </div>
        </div>
    {{-- BAGIAN KONTEN UTAMA HALAMAN --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    

                    {{-- WADAH UNTUK SEARCH & SORT --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0 sm:space-x-4">
                        
                        <form action="{{ route('dashboard') }}" method="GET" class="w-full sm:w-1/2">
                            <div class="flex">
                                <input type="text" id="searchInput" name="search" placeholder="Cari Nama, Kode Pelatihan Atau Nama Trainer"
                                    class="w-full rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    value="{{ request('search') }}">
                                <button type="submit" id="searchButton" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none">
                                    Cari
                                </button>
                            </div>
                        </form>

                        <form action="{{ route('dashboard') }}" method="GET" class="w-full sm:w-auto">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="sort" onchange="this.form.submit()" class="w-full sm:w-auto rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="tanggal_terbaru" @if(request('sort', 'tanggal_terbaru') == 'tanggal_terbaru') selected @endif>Urutan: Tanggal Terbaru</option>
                                <option value="tanggal_terlama" @if(request('sort') == 'tanggal_terlama') selected @endif>Urutan: Tanggal Terlama</option>
                                <option value="nama_az" @if(request('sort') == 'nama_az') selected @endif>Urutan: Nama A-Z</option>
                                <option value="nama_za" @if(request('sort') == 'nama_za') selected @endif>Urutan: Nama Z-A</option>
                                <option value="instruktur_az" @if(request('sort') == 'instruktur_az') selected @endif>Urutan: Instruktur A-Z</option>
                                <option value="status" @if(request('sort') == 'status') selected @endif>Urutan: Berdasarkan Status</option>
                            </select>
                        </form>

                    </div>

                    {{-- PESAN SUKSES --}}
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                  

                    {{-- TABEL DATA --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Pelatihan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Peserta</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kuota</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Trainer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($pelatihans as $pelatihan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- RUMUS PENOMORAN PAGINATION YANG BENAR --}}
                                            {{ ($pelatihans->currentPage() - 1) * $pelatihans->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->kode_pelatihan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->nama_pelatihan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->deskripsi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $pelatihan->tanggal_pelatihan ? $pelatihan->tanggal_pelatihan->translatedFormat('d M Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->pesertas_count }} orang</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->kuota }} orang</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->nama_instruktur }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelatihan->lokasi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pelatihan->status == 'Selesai')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $pelatihan->status }}
                                            </span>
                                        @elseif($pelatihan->status == 'Berlangsung')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $pelatihan->status }}
                                            </span>
                                        @elseif($pelatihan->status == 'Dibatalkan')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $pelatihan->status }}
                                            </span>
                                        @else {{-- Defaultnya untuk 'Segera Hadir' --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $pelatihan->status }}
                                            </span>
                                        @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('peserta.index', $pelatihan->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3">Lihat Peserta</a>
                                            <a href="{{ route('pelatihan.edit', $pelatihan->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-3">Edit</a>
                                            <form action="{{ route('pelatihan.destroy', $pelatihan->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" onclick="return confirm('Anda yakin ingin menghapus pelatihan ini? Semua data peserta di dalamnya juga akan terhapus.')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="px-6 py-4 whitespace-nowrap text-center">Belum ada data pelatihan atau data tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- LINK PAGINATION --}}
                        <div class="mt-4">
                            {{ $pelatihans->appends(request()->query())->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT DITEMPATKAN DI SINI, SEBELUM PENUTUP </x-app-layout> --}}
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