@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="mediaGallery()">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Galeri Foto</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola semua gambar yang diupload dalam sistem</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">
                <span class="font-semibold text-gray-700" x-text="totalCount">{{ $media->total() }}</span> gambar
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm" 
         x-data="{ show: true }" x-show="show" x-transition>
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        {{ session('success') }}
        <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    {{-- Upload Area --}}
    <div class="mb-8 relative" 
         @dragover.prevent="isDragging = true" 
         @dragleave.prevent="isDragging = false"
         @drop.prevent="handleDrop($event)">
        <div class="border-2 border-dashed rounded-2xl p-8 text-center transition-all duration-300"
             :class="isDragging ? 'border-blue-400 bg-blue-50 scale-[1.01]' : 'border-gray-200 bg-gray-50/50 hover:border-gray-300'">
            
            {{-- Upload Progress --}}
            <template x-if="uploading">
                <div class="space-y-4">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-blue-500 animate-bounce"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Mengupload...</p>
                        <p class="text-sm text-gray-500 mt-1" x-text="uploadProgress"></p>
                    </div>
                    <div class="w-64 mx-auto bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" :style="'width: ' + uploadPercent + '%'"></div>
                    </div>
                </div>
            </template>

            {{-- Default Upload UI --}}
            <template x-if="!uploading">
                <div>
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4 transition-colors"
                         :class="isDragging ? 'bg-blue-100' : ''">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl transition-colors" :class="isDragging ? 'text-blue-500' : 'text-gray-400'"></i>
                    </div>
                    <p class="font-semibold text-gray-700">Drag & drop gambar di sini</p>
                    <p class="text-sm text-gray-500 mt-1">atau</p>
                    <label class="mt-3 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 cursor-pointer transition-colors shadow-sm hover:shadow">
                        <i class="fa-solid fa-plus"></i>
                        Pilih File
                        <input type="file" multiple accept="image/*" class="hidden" @change="handleFiles($event.target.files)">
                    </label>
                    <p class="text-xs text-gray-400 mt-3">JPG, PNG, GIF, WEBP — Maks 5MB per file — Maks 20 file</p>
                </div>
            </template>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" 
                   placeholder="Cari gambar..." 
                   x-model.debounce.300ms="searchQuery"
                   @input="fetchMedia(1)"
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
        </div>
        <select x-model="sourceFilter" 
                @change="fetchMedia(1)"
                class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 bg-white min-w-[160px]">
            <option value="">Semua Sumber</option>
            @foreach($sources as $source)
                <option value="{{ $source }}">{{ ucfirst($source) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" id="media-grid">
        <template x-for="item in mediaItems" :key="item.id">
            <div class="group relative bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5"
                 @click="previewMedia(item)">
                <div class="aspect-square overflow-hidden bg-gray-100">
                    <img :src="item.url" 
                         :alt="item.filename"
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                         loading="lazy">
                </div>
                <div class="p-2">
                    <p class="text-xs text-gray-600 truncate font-medium" x-text="item.filename"></p>
                    <p class="text-[10px] text-gray-400 mt-0.5" x-text="item.human_size || formatSize(item.size)"></p>
                </div>

                {{-- Hover Actions --}}
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button @click.stop="confirmDelete(item)" 
                            class="w-7 h-7 bg-red-500/90 backdrop-blur-sm text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition text-xs shadow-sm">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>

                {{-- Source Badge --}}
                <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <span class="px-2 py-0.5 bg-black/50 backdrop-blur-sm text-white text-[10px] rounded-full font-medium" x-text="item.source"></span>
                </div>
            </div>
        </template>
    </div>

    {{-- Empty State --}}
    <template x-if="mediaItems.length === 0 && !loading">
        <div class="text-center py-16">
            <div class="w-20 h-20 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-photo-film text-3xl text-gray-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-500">Belum ada gambar</h3>
            <p class="text-sm text-gray-400 mt-1">Upload gambar pertama Anda menggunakan area di atas</p>
        </div>
    </template>

    {{-- Loading --}}
    <template x-if="loading">
        <div class="flex items-center justify-center py-12">
            <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
        </div>
    </template>

    {{-- Pagination --}}
    <div class="mt-8 flex items-center justify-center gap-2" x-show="lastPage > 1">
        <button @click="fetchMedia(currentPage - 1)" 
                :disabled="currentPage <= 1"
                :class="currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg transition">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>
        <span class="px-4 py-2 text-sm text-gray-600">
            Hal <span x-text="currentPage" class="font-semibold"></span> dari <span x-text="lastPage" class="font-semibold"></span>
        </span>
        <button @click="fetchMedia(currentPage + 1)" 
                :disabled="currentPage >= lastPage"
                :class="currentPage >= lastPage ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg transition">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
    </div>

    {{-- Preview Modal --}}
    <div x-show="previewItem" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="previewItem = null"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         style="display: none;">
        <div x-show="previewItem"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.stop
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
            {{-- Preview Image --}}
            <div class="bg-gray-100 flex items-center justify-center" style="max-height: 60vh;">
                <img :src="previewItem?.url" :alt="previewItem?.filename" class="max-w-full max-h-[60vh] object-contain">
            </div>
            {{-- Info --}}
            <div class="p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate" x-text="previewItem?.filename"></h3>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-500">
                            <span><i class="fa-solid fa-hard-drive mr-1"></i> <span x-text="formatSize(previewItem?.size)"></span></span>
                            <span><i class="fa-solid fa-tag mr-1"></i> <span x-text="previewItem?.source"></span></span>
                            <span><i class="fa-solid fa-clock mr-1"></i> <span x-text="formatDate(previewItem?.created_at)"></span></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button @click="copyUrl(previewItem?.url)"
                                class="px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-600">
                            <i class="fa-solid fa-copy mr-1"></i> Salin URL
                        </button>
                        <button @click="confirmDelete(previewItem); previewItem = null"
                                class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-100">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                        </button>
                        <button @click="previewItem = null"
                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                {{-- URL Display --}}
                <div class="mt-3 flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-100">
                    <i class="fa-solid fa-link text-xs text-gray-400"></i>
                    <input type="text" :value="previewItem?.url" readonly 
                           class="flex-1 bg-transparent text-xs text-gray-600 border-0 p-0 focus:ring-0 truncate">
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteItem" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="deleteItem = null"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
         style="display: none;">
        <div x-show="deleteItem"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop
             class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            <div class="w-14 h-14 mx-auto bg-red-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Hapus Gambar?</h3>
            <p class="text-sm text-gray-500 mt-2">Gambar akan dihapus permanen dari storage dan galeri.</p>
            <div class="flex gap-3 mt-6">
                <button @click="deleteItem = null" class="flex-1 px-4 py-2.5 text-sm font-medium border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button @click="deleteMedia()" class="flex-1 px-4 py-2.5 text-sm font-medium bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-sm">
                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Copy Success Toast --}}
    <div x-show="copied" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-[9999] bg-gray-900 text-white px-4 py-3 rounded-xl text-sm shadow-lg flex items-center gap-2"
         style="display: none;">
        <i class="fa-solid fa-circle-check text-emerald-400"></i>
        URL berhasil disalin!
    </div>

</div>

<script>
function mediaGallery() {
    return {
        mediaItems: @json($media->items()),
        totalCount: {{ $media->total() }},
        currentPage: {{ $media->currentPage() }},
        lastPage: {{ $media->lastPage() }},
        loading: false,
        uploading: false,
        uploadProgress: '',
        uploadPercent: 0,
        searchQuery: '{{ request('search') }}',
        sourceFilter: '{{ request('source') }}',
        isDragging: false,
        previewItem: null,
        deleteItem: null,
        copied: false,

        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            this.handleFiles(files);
        },

        async handleFiles(files) {
            if (!files.length) return;

            const formData = new FormData();
            let validCount = 0;
            for (let i = 0; i < Math.min(files.length, 20); i++) {
                if (files[i].type.startsWith('image/')) {
                    formData.append('images[]', files[i]);
                    validCount++;
                }
            }

            if (!validCount) return;

            this.uploading = true;
            this.uploadProgress = `Mengupload ${validCount} file...`;
            this.uploadPercent = 10;

            try {
                const response = await fetch('{{ route("admin.media.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                this.uploadPercent = 80;

                if (response.ok) {
                    this.uploadPercent = 100;
                    this.uploadProgress = 'Selesai!';
                    await new Promise(r => setTimeout(r, 500));
                    this.fetchMedia(1);
                } else {
                    const err = await response.json();
                    alert(err.message || 'Gagal mengupload gambar.');
                }
            } catch (e) {
                alert('Terjadi kesalahan saat upload.');
            } finally {
                this.uploading = false;
                this.uploadPercent = 0;
            }
        },

        async fetchMedia(page = 1) {
            if (page < 1 || page > this.lastPage && page !== 1) return;
            this.loading = true;

            const params = new URLSearchParams();
            params.set('page', page);
            if (this.searchQuery) params.set('search', this.searchQuery);
            if (this.sourceFilter) params.set('source', this.sourceFilter);

            try {
                const response = await fetch('{{ route("admin.media.api") }}?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.mediaItems = data.data;
                this.totalCount = data.total;
                this.currentPage = data.current_page;
                this.lastPage = data.last_page;
            } catch (e) {
                console.error('Failed to fetch media:', e);
            } finally {
                this.loading = false;
            }
        },

        previewMedia(item) {
            this.previewItem = item;
        },

        confirmDelete(item) {
            this.deleteItem = item;
        },

        async deleteMedia() {
            if (!this.deleteItem) return;

            try {
                const response = await fetch(`{{ url('admin/media') }}/${this.deleteItem.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    this.mediaItems = this.mediaItems.filter(m => m.id !== this.deleteItem.id);
                    this.totalCount--;
                    this.deleteItem = null;
                }
            } catch (e) {
                alert('Gagal menghapus gambar.');
            }
        },

        copyUrl(url) {
            navigator.clipboard.writeText(url);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        },

        formatSize(bytes) {
            if (!bytes) return '0 B';
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return bytes + ' B';
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        },
    }
}
</script>
@endsection
