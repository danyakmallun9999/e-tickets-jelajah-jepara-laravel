<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.events.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-sm text-gray-500 mb-0.5">Edit Event</p>
                    <h2 class="font-bold text-xl text-gray-900 leading-tight line-clamp-1">
                        {{ Str::limit($event->title, 40) }}
                    </h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($event->is_published)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Published
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                        Draft
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Event Info Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-star text-violet-500"></i>
                                    <h3 class="font-bold text-gray-900">Informasi Event</h3>
                                </div>
                            </div>
                            <div class="p-6 space-y-5">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-heading text-gray-400 mr-1.5"></i>
                                        Nama Event
                                    </label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $event->title) }}"
                                           class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-lg font-medium placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all"
                                           placeholder="Contoh: Festival Baratan 2026"
                                           required>
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-align-left text-gray-400 mr-1.5"></i>
                                        Deskripsi Event
                                    </label>
                                    <textarea id="description" 
                                              name="description" 
                                              class="settings-tiny">{{ old('description', $event->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- TinyMCE Initialization -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                tinymce.init({
                                    selector: '.settings-tiny',
                                    height: 400,
                                    menubar: false,
                                    plugins: 'lists link image table code wordcount',
                                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
                                    content_style: 'body { font-family:Figtree,sans-serif; font-size:16px; overflow-x: hidden; word-wrap: break-word; } img { max-width: 100%; height: auto; }',
                                    relative_urls: false,
                                    remove_script_host: false,
                                    document_base_url: '{{ url('/') }}',
                                });
                            });
                        </script>
                    </div>

                    <!-- Right Column: Sidebar -->
                    <div class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                        <!-- Event Details Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-gear text-violet-500"></i>
                                    <h3 class="font-bold text-gray-900">Detail Event</h3>
                                </div>
                            </div>
                            <div class="p-5 space-y-4">
                                <!-- Location -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fa-solid fa-location-dot text-gray-400 mr-1"></i>
                                        Lokasi
                                    </label>
                                    <input type="text" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location', $event->location) }}"
                                           class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all"
                                           placeholder="Contoh: Alun-alun Jepara"
                                           required>
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>

                                <!-- Date Range -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                        <input type="date" 
                                               id="start_date" 
                                               name="start_date" 
                                               value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}"
                                               class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all text-sm"
                                               required>
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                        <input type="date" 
                                               id="end_date" 
                                               name="end_date" 
                                               value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '') }}"
                                               class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all text-sm">
                                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                        <p class="text-xs text-gray-400 mt-1">Opsional</p>
                                    </div>
                                </div>

                                <!-- Is Published Toggle -->
                                <div class="flex items-center justify-between p-4 bg-violet-50 rounded-xl border border-violet-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-eye text-violet-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Tampilkan di Kalender</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-violet-300/25 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-500"></div>
                                    </label>
                                </div>

                                <hr class="border-gray-100">

                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-700 focus:ring-4 focus:ring-amber-500/25 transition-all shadow-lg shadow-amber-500/25">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Simpan Perubahan
                                    </button>
                                    <a href="{{ route('admin.events.index') }}" 
                                       class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-all">
                                        <i class="fa-solid fa-xmark"></i>
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Poster Image Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-image text-purple-500"></i>
                                    <h3 class="font-bold text-gray-900">Poster Event</h3>
                                </div>
                            </div>
                            <div class="p-5" x-data="{ preview: '{{ $event->image ? Storage::url($event->image) : '' }}' }">
                                <div class="relative w-full aspect-[3/4] bg-gray-100 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden hover:border-violet-400 hover:bg-violet-50/30 transition-all cursor-pointer group"
                                     @click="$refs.fileInput.click()">
                                    
                                    <template x-if="!preview">
                                        <div class="text-center p-4">
                                            <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-violet-100 transition-colors">
                                                <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl group-hover:text-violet-500 transition-colors"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-600 group-hover:text-violet-600 transition-colors">Upload Poster</p>
                                            <p class="text-xs text-gray-400 mt-1">Portrait (3:4) recommended</p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="preview">
                                        <div class="relative w-full h-full">
                                            <img :src="preview" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-white font-medium text-sm"><i class="fa-solid fa-pen mr-1"></i> Ganti</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <input type="file" 
                                       x-ref="fileInput" 
                                       id="image" 
                                       name="image" 
                                       class="hidden" 
                                       accept="image/*"
                                       @change="const file = $event.target.files[0]; 
                                                const reader = new FileReader(); 
                                                reader.onload = (e) => preview = e.target.result; 
                                                reader.readAsDataURL(file)">
                                <p class="text-xs text-gray-400 mt-3 text-center">Opsional • PNG, JPG maksimal 2MB</p>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Event Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="text-xs text-gray-500 space-y-1">
                                <div class="flex justify-between">
                                    <span>Dibuat:</span>
                                    <span class="font-medium text-gray-700">{{ $event->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Diperbarui:</span>
                                    <span class="font-medium text-gray-700">{{ $event->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
