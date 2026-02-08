<x-app-layout :full-width="true">
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">Admin Panel · Lokasi</p>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Tambah Lokasi Baru
            </h2>
        </div>
    </x-slot>

    @include('admin.places.partials.form', [
        'action' => route('admin.places.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Lokasi'
    ])
</x-app-layout>

