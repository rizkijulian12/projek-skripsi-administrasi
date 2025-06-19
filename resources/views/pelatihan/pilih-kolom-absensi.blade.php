<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kustomisasi Template Absensi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Atur kolom yang akan ditampilkan pada absensi untuk pelatihan <strong>{{ $pelatihan->nama_pelatihan }}</strong>.
                    </p>
                    
                    {{-- Form ini akan mengirim semua pilihan ke route yang mencetak PDF --}}
                    <form action="{{ route('pelatihan.cetakAbsensi', $pelatihan->id) }}" method="GET" target="_blank">
                        
                        {{-- BAGIAN A: PILIH KOLOM BERISI DATA DARI DATABASE --}}
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2 dark:text-gray-200">1. Pilih Kolom Data Peserta:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label class="flex items-center space-x-2 p-2 border dark:border-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <input type="checkbox" name="kolom_data[]" value="nama_peserta" class="rounded" checked> <span>Nama Peserta</span>
                                </label>
                                <label class="flex items-center space-x-2 p-2 border dark:border-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <input type="checkbox" name="kolom_data[]" value="asal_peserta" class="rounded" checked> <span>Asal Peserta</span>
                                </label>
                                <label class="flex items-center space-x-2 p-2 border dark:border-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <input type="checkbox" name="kolom_data[]" value="nomor_telepon" class="rounded"> <span>No. Telepon</span>
                                </label>
                                <label class="flex items-center space-x-2 p-2 border dark:border-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <input type="checkbox" name="kolom_data[]" value="tanggal_lahir" class="rounded"> <span>Tanggal Lahir</span>
                                </label>
                            </div>
                        </div>

                        {{-- BAGIAN B: INPUT MANUAL UNTUK KOLOM KOSONG --}}
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2 dark:text-gray-200">2. Tambahkan Kolom Kosong Kustom (Opsional):</h4>
                            <div id="kolom-kustom-container" class="space-y-3">
                                {{-- Kolom Tanda Tangan default --}}
                                <div class="flex items-center space-x-2">
                                    <x-text-input type="text" name="kolom_kustom[]" value="Tanda Tangan" class="w-full"/>
                                    <button type="button" class="hapus-kolom font-bold text-red-500 hover:text-red-700 text-2xl">&times;</button>
                                </div>
                            </div>
                            <button type="button" id="tambah-kolom" class="mt-2 text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">+ Tambah Kolom Kosong</button>
                        </div>

                        <hr class="my-6 dark:border-gray-700">

                        <div class="flex justify-end">
                            <a href="{{ route('peserta.index', $pelatihan->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 self-center me-4">
                                Batal
                            </a>
                            <x-primary-button>
                                Buat & Cetak PDF
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MEMBUAT FORM MENJADI DINAMIS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('kolom-kustom-container');
            const addButton = document.getElementById('tambah-kolom');

            // Fungsi saat tombol "Tambah Kolom" diklik
            addButton.addEventListener('click', function () {
                const newField = document.createElement('div');
                newField.className = 'flex items-center space-x-2';
                newField.innerHTML = `
                    <input type="text" name="kolom_kustom[]" placeholder="Nama Kolom Baru (misal: TTD Sesi 2)" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <button type="button" class="hapus-kolom font-bold text-red-500 hover:text-red-700 text-2xl">&times;</button>
                `;
                container.appendChild(newField);
            });

            // Fungsi saat tombol "Hapus" (silang) diklik
            container.addEventListener('click', function (e) {
                // Cek apakah yang diklik adalah tombol hapus
                if (e.target.classList.contains('hapus-kolom')) {
                    // Hapus elemen div yang membungkus input dan tombol
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
</x-app-layout>