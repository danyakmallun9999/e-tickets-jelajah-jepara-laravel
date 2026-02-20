@extends('layouts.app')

@section('title', 'Pengaturan Hero Section')

@section('content')
<div class="px-4 py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center whitespace-normal">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Hero</h1>
            <p class="mt-2 text-sm text-gray-500">
                Atur tampilan awal halaman utama pengunjung. Anda dapat memilih latar belakang berupa peta, sebuah video putar otomatis, atau rangkaian gambar (slider). Semua teks bersifat opsional.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50/50 border border-green-200 flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-bold text-green-800">Berhasil</h3>
                <p class="text-xs font-medium text-green-700 mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50/50 border border-red-200">
            <div class="flex items-center gap-3 mb-2">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan</h3>
            </div>
            <ul class="list-disc pl-8">
                @foreach ($errors->all() as $error)
                    <li class="text-xs font-medium text-red-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.hero-settings.update') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ type: '{{ old('type', $setting->type ?? 'map') }}' }" 
          class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Background Media -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Type Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Tipe Latar Belakang</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <label class="block flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                            :class="type === 'map' ? 'border-primary bg-primary/5' : 'border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="type" value="map" x-model="type" class="mt-1 text-primary focus:ring-primary border-gray-300">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Animasi Peta 3D (Default)</span>
                                <span class="block text-xs text-gray-500 mt-1">Menggunakan integrasi MapLibre yang interaktif sebagai latar belakang.</span>
                            </div>
                        </label>
                        
                        <label class="block flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                            :class="type === 'video' ? 'border-primary bg-primary/5' : 'border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="type" value="video" x-model="type" class="mt-1 text-primary focus:ring-primary border-gray-300">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Video Latar (Auto Play)</span>
                                <span class="block text-xs text-gray-500 mt-1">Satu buah video yang berputar secara dinamis berulang tanpa suara. (Maks 50MB)</span>
                            </div>
                        </label>
                        
                        <label class="block flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                            :class="type === 'image' ? 'border-primary bg-primary/5' : 'border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="type" value="image" x-model="type" class="mt-1 text-primary focus:ring-primary border-gray-300">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Gambar Slider (Carousel)</span>
                                <span class="block text-xs text-gray-500 mt-1">Beberapa gambar yang dapat bergeser otomatis secara pudar (fade).</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Media Uploads -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-show="type !== 'map'">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Berkas Media</h3>
                    </div>
                    <div class="p-5 space-y-5">
                        <!-- Video Target -->
                        <div x-show="type === 'video'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unggah Video Baru</label>
                            <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-xs text-gray-500 mt-2">Format yang disarankan: MP4. Maks: 50MB.</p>

                            @if($setting->type === 'video' && !empty($setting->media_paths))
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <span class="text-xs font-bold block mb-2">Video Aktif:</span>
                                    <video src="{{ Storage::url($setting->media_paths[0]) }}" controls class="w-full h-auto rounded aspect-video object-cover"></video>
                                    <label class="flex items-center gap-2 mt-3 cursor-pointer">
                                        <input type="checkbox" name="remove_media" value="1" class="text-red-500 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="text-xs text-red-600 font-medium">Hapus Video Ini</span>
                                    </label>
                                </div>
                            @endif
                        </div>

                        <!-- Image Target -->
                        <div x-show="type === 'image'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tambah Gambar Baru</label>
                            <input type="file" name="image_files[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-xs text-gray-500 mt-2">Anda dapat memilih lebih dari satu file sekaligus. Maks 10MB/foto.</p>

                            @if($setting->type === 'image' && !empty($setting->media_paths))
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <span class="text-xs font-bold block mb-2">Gambar Slider Masing-Masing:</span>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($setting->media_paths as $path)
                                            <div class="relative group aspect-video">
                                                <img src="{{ Storage::url($path) }}" class="w-full h-full object-cover rounded border border-gray-200">
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition rounded">
                                                     <label class="text-white text-xs cursor-pointer flex flex-col items-center">
                                                         <input type="checkbox" name="existing_media[]" value="{{ $path }}" checked class="mb-1 text-primary focus:ring-primary border-gray-300 rounded">
                                                         Pertahankan
                                                     </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-2 italic">*Hapus centang pada gambar yang ingin dibuang dari putaran.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Content Texts -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Konten Teks (Opsional)</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="bg-blue-50 text-blue-800 text-xs p-3 rounded flex gap-2">
                             <i class="fa-solid fa-circle-info mt-0.5"></i>
                             <p>Seluruh teks bersifat *opsional*. Jika Anda membiarkan formulir di bawah ini kosong, bagian *Hero* hanya akan menampilkan visual media layar penuh (tanpa tulisan apapun).</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-b border-gray-100 pb-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Badge (ID)</label>
                                <input type="text" name="badge_id" value="{{ old('badge_id', $setting->badge_id) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Contoh: Jelajahi Jepara">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Badge (EN)</label>
                                <input type="text" name="badge_en" value="{{ old('badge_en', $setting->badge_en) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Ex: Explore Jepara">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-b border-gray-100 pb-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama (ID)</label>
                                <input type="text" name="title_id" value="{{ old('title_id', $setting->title_id) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Contoh: Temukan Keajaiban">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama (EN)</label>
                                <input type="text" name="title_en" value="{{ old('title_en', $setting->title_en) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Ex: Discover Wonders">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-b border-gray-100 pb-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul (ID)</label>
                                <textarea name="subtitle_id" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Deskripsi pendek...">{{ old('subtitle_id', $setting->subtitle_id) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul (EN)</label>
                                <textarea name="subtitle_en" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Short description...">{{ old('subtitle_en', $setting->subtitle_en) }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol (ID)</label>
                                <input type="text" name="button_text_id" value="{{ old('button_text_id', $setting->button_text_id) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Mulai Petualangan">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol (EN)</label>
                                <input type="text" name="button_text_en" value="{{ old('button_text_en', $setting->button_text_en) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Start Adventure">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL / Link Tombol</label>
                                <input type="text" name="button_link" value="{{ old('button_link', $setting->button_link) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="#jelajah">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex justify-center items-center gap-2 py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="fa-solid fa-save"></i> Simpan Pengaturan Hero
                    </button>
                </div>
            </div>
            
        </div>
    </form>
</div>
@endsection
