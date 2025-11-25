<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Lokasi</h3>
                        <a href="{{ route('places.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Lokasi
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nama</th>
                                    <th class="py-2 px-4 border-b text-left">Kategori</th>
                                    <th class="py-2 px-4 border-b text-left">Koordinat</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($places as $place)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $place->name }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $place->category->color }}">
                                            {{ $place->category->name }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-600">
                                        {{ $place->latitude }}, {{ $place->longitude }}
                                    </td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <a href="{{ route('places.edit', $place->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                        <form action="{{ route('places.destroy', $place->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $places->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
