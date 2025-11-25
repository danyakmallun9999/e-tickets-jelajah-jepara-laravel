<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">Admin Panel · Lokasi</p>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Tambah Lokasi Baru
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                @include('admin.places.partials.form', [
                    'action' => route('admin.places.store'),
                    'method' => 'POST',
                    'submitLabel' => 'Simpan Lokasi'
                ])
            </div>
        </div>
    </div>
</x-app-layout>

