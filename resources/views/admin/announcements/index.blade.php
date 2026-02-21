<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="hidden md:block text-sm text-gray-500 mb-0.5">Admin Panel</p>
                <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">
                    Kelola Pengumuman
                </h2>
            </div>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 md:px-5 md:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl font-semibold text-xs md:text-sm text-white hover:shadow-blue-500/40 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:-translate-y-0.5" wire:navigate>
                <i class="fa-solid fa-plus text-xs"></i>
                <span class="hidden md:inline">Tambah Pengumuman</span>
                <span class="md:hidden">Tambah</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                    <div class="flex items-center gap-4 p-5 rounded-[2rem] border border-gray-100 bg-gray-50/30">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100">
                            <i class="fa-solid fa-bullhorn text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $announcements->total() }}</p>
                            <p class="text-sm text-gray-500 font-medium">Total Pengumuman</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                    <div class="flex items-center gap-4 p-5 rounded-[2rem] border border-gray-100 bg-gray-50/30">
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center border border-green-100">
                            <i class="fa-solid fa-circle-check text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $announcements->where('is_active', true)->count() }}</p>
                            <p class="text-sm text-gray-500 font-medium">Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                <div class="rounded-[2rem] border border-gray-100 overflow-hidden bg-white">
                    {{-- Desktop --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Dibuat</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($announcements as $announcement)
                                    <tr class="hover:bg-blue-50/30 transition-colors duration-200 {{ $loop->even ? 'bg-gray-50/30' : 'bg-white' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($announcement->image)
                                                    <img src="{{ Storage::url($announcement->image) }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100" alt="">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                                        <i class="fa-solid fa-bullhorn text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">{{ $announcement->title }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit(strip_tags($announcement->content), 60) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('admin.announcements.toggle-active', $announcement) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200
                                                        {{ $announcement->is_active ? 'bg-green-100 text-green-700 border border-green-200 hover:bg-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' }}"
                                                        title="{{ $announcement->is_active ? 'Klik untuk Nonaktifkan' : 'Klik untuk Aktifkan' }}">
                                                    <i class="fa-solid {{ $announcement->is_active ? 'fa-eye' : 'fa-eye-slash' }} text-[10px]"></i>
                                                    {{ $announcement->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500">
                                                @if($announcement->starts_at || $announcement->ends_at)
                                                    <span>{{ $announcement->starts_at?->format('d M Y') ?? '∞' }}</span>
                                                    <span class="mx-1 text-gray-300">→</span>
                                                    <span>{{ $announcement->ends_at?->format('d M Y') ?? '∞' }}</span>
                                                @else
                                                    <span class="text-gray-400">Selalu aktif</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500">
                                                <p>{{ $announcement->creator?->name ?? 'N/A' }}</p>
                                                <p class="text-gray-400">{{ $announcement->created_at->format('d M Y') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                                   class="p-2.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200"
                                                   title="Edit" wire:navigate>
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200"
                                                            title="Hapus">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                                    <i class="fa-solid fa-bullhorn text-3xl text-gray-300"></i>
                                                </div>
                                                <p class="text-gray-600 font-medium mb-1">Belum ada pengumuman</p>
                                                <p class="text-sm text-gray-400 mb-4">Buat pengumuman pertama untuk ditampilkan di website</p>
                                                <a href="{{ route('admin.announcements.create') }}"
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition-colors" wire:navigate>
                                                    <i class="fa-solid fa-plus"></i> Tambah Pengumuman
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden space-y-4 p-4">
                        @forelse ($announcements as $announcement)
                            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                                <div class="flex gap-3">
                                    @if($announcement->image)
                                        <img src="{{ Storage::url($announcement->image) }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" alt="">
                                    @else
                                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-bullhorn text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 text-sm truncate">{{ $announcement->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit(strip_tags($announcement->content), 50) }}</p>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-lg {{ $announcement->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $announcement->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end mt-3 pt-3 border-t border-gray-50 gap-2">
                                    <form action="{{ route('admin.announcements.toggle-active', $announcement) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold {{ $announcement->is_active ? 'text-orange-600 bg-orange-50' : 'text-green-600 bg-green-50' }} rounded-lg">
                                            {{ $announcement->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-gray-100 rounded-lg" wire:navigate>Edit</a>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 rounded-lg">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="fa-solid fa-bullhorn text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">Belum ada pengumuman</p>
                            </div>
                        @endforelse
                    </div>

                    @if($announcements->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                            {{ $announcements->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
