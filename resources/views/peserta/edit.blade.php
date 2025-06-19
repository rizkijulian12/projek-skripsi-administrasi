<x-app-layout>
    {{-- Bagian Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Peserta') }}
        </h2>
    </x-slot>

    {{-- Bagian Konten Utama Halaman --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Judul di dalam konten --}}
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Formulir Edit Peserta</h3>
                    @if($peserta->pelatihan)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Pelatihan: <strong>{{ $peserta->pelatihan->nama_pelatihan }}</strong>
                        </p>
                    @endif

                    <div class="mt-6">
                        <form method="POST" action="{{ route('peserta.update', $peserta->id) }}">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="nama_peserta" :value="__('Nama Lengkap Peserta')" />
                                <x-text-input id="nama_peserta" class="block mt-1 w-full" type="text" name="nama_peserta" :value="old('nama_peserta', $peserta->nama_peserta)" required autofocus />
                                <x-input-error :messages="$errors->get('nama_peserta')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="asal_peserta" :value="__('Asal Instansi/Kota')" />
                                <x-text-input id="asal_peserta" class="block mt-1 w-full" type="text" name="asal_peserta" :value="old('asal_peserta', $peserta->asal_peserta)" required />
                                <x-input-error :messages="$errors->get('asal_peserta')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="nomor_telepon" :value="__('Nomor Telepon (WA)')" />
                                <x-text-input id="nomor_telepon" class="block mt-1 w-full" type="text" name="nomor_telepon" :value="old('nomor_telepon', $peserta->nomor_telepon)" required />
                                <x-input-error :messages="$errors->get('nomor_telepon')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('peserta.index', $peserta->pelatihan_id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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