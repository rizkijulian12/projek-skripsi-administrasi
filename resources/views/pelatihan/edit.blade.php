<x-app-layout>
    {{-- Bagian Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pelatihan') }}
        </h2>
    </x-slot>

    {{-- Bagian Konten Utama Halaman --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Formulir Edit Pelatihan</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Perbarui detail pelatihan di bawah ini.
                    </p>

                    <div class="mt-6">
                        <form method="POST" action="{{ route('pelatihan.update', $pelatihan->id) }}">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="nama_pelatihan" :value="__('Nama Pelatihan')" />
                                <x-text-input id="nama_pelatihan" class="block mt-1 w-full" type="text" name="nama_pelatihan" :value="old('nama_pelatihan', $pelatihan->nama_pelatihan)" required autofocus />
                                <x-input-error :messages="$errors->get('nama_pelatihan')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="kode_pelatihan" :value="__('Kode Pelatihan')" />
                                <x-text-input id="kode_pelatihan" class="block mt-1 w-full" type="text" name="kode_pelatihan" :value="old('kode_pelatihan', $pelatihan->kode_pelatihan)" required />
                                <x-input-error :messages="$errors->get('kode_pelatihan')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="tanggal_pelatihan" :value="__('Tanggal Pelatihan')" />
                                <x-text-input id="tanggal_pelatihan" class="block mt-1 w-full" type="date" name="tanggal_pelatihan" :value="old('tanggal_pelatihan', $pelatihan->tanggal_pelatihan ? $pelatihan->tanggal_pelatihan->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('tanggal_pelatihan')" class="mt-2" />
                            </div>

                            <div class="mt-4">
    <x-input-label for="deskripsi" :value="__('Deskripsi Singkat')" />
    <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi', $pelatihan->deskripsi ?? '') }}</textarea>
</div>

<div class="mt-4">
    <x-input-label for="lokasi" :value="__('Lokasi')" />
    <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi', $pelatihan->lokasi ?? '')" />
</div>

<div class="mt-4">
    <x-input-label for="nama_instruktur" :value="__('Nama Instruktur')" />
    <x-text-input id="nama_instruktur" class="block mt-1 w-full" type="text" name="nama_instruktur" :value="old('nama_instruktur', $pelatihan->nama_instruktur ?? '')" />
</div>

<div class="mt-4">
    <x-input-label for="kuota" :value="__('Kuota Peserta (0 untuk tidak terbatas)')" />
    <x-text-input id="kuota" class="block mt-1 w-full" type="number" name="kuota" :value="old('kuota', $pelatihan->kuota ?? 0)" required />
</div>

<div class="mt-4">
    <x-input-label for="status" :value="__('Status Pelatihan')" />
    <select name="status" id="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        <option value="Segera Hadir" @selected(old('status', $pelatihan->status ?? '') == 'Segera Hadir')>Segera Hadir</option>
        <option value="Berlangsung" @selected(old('status', $pelatihan->status ?? '') == 'Berlangsung')>Berlangsung</option>
        <option value="Selesai" @selected(old('status', $pelatihan->status ?? '') == 'Selesai')>Selesai</option>
        <option value="Dibatalkan" @selected(old('status', $pelatihan->status ?? '') == 'Dibatalkan')>Dibatalkan</option>
    </select>
</div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('dashboard') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Batal') }}
                                </a>

                                <x-primary-button class="ms-4">
                                    {{ __('Simpan Perubahan') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>