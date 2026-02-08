<x-app-layout :full-width="true">
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">Admin Panel · Lokasi</p>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Edit Lokasi: {{ $place->name }}
            </h2>
        </div>
    </x-slot>

    @include('admin.places.partials.form', [
        'action' => route('admin.places.update', $place),
        'method' => 'PUT',
        'submitLabel' => 'Perbarui Lokasi'
    ])
</x-app-layout>

