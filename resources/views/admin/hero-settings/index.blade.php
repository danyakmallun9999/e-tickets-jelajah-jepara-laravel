<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">
                    Pengaturan Hero
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-green-50/50 border border-green-200 flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-green-800">Berhasil</h3>
                        <p class="text-xs font-medium text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50/50 border border-red-200">
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
                  x-data="{ 
                      type: '{{ old('type', $setting->type ?? 'map') }}',
                      previewLang: 'id',
                      badge_id: {{ \Illuminate\Support\Js::from(old('badge_id', $setting->badge_id ?? '')) }},
                      badge_en: {{ \Illuminate\Support\Js::from(old('badge_en', $setting->badge_en ?? '')) }},
                      title_id: {{ \Illuminate\Support\Js::from(old('title_id', $setting->title_id ?? '')) }},
                      title_en: {{ \Illuminate\Support\Js::from(old('title_en', $setting->title_en ?? '')) }},
                      subtitle_id: {{ \Illuminate\Support\Js::from(old('subtitle_id', $setting->subtitle_id ?? '')) }},
                      subtitle_en: {{ \Illuminate\Support\Js::from(old('subtitle_en', $setting->subtitle_en ?? '')) }},
                      btn_id: {{ \Illuminate\Support\Js::from(old('button_text_id', $setting->button_text_id ?? '')) }},
                      btn_en: {{ \Illuminate\Support\Js::from(old('button_text_en', $setting->button_text_en ?? '')) }},
                      
                      previewVideo: '{{ ($setting->type === 'video' && !empty($setting->media_paths)) ? Storage::url($setting->media_paths[0]) : '' }}',
                      previewImages: {{ ($setting->type === 'image' && !empty($setting->media_paths)) ? json_encode(array_values(array_map(fn($p) => Storage::url($p), $setting->media_paths))) : '[]' }},
                      currentSlide: 0,
                      
                      init() {
                          if(this.previewImages.length > 1) {
                              setInterval(() => {
                                  this.currentSlide = (this.currentSlide + 1) % this.previewImages.length;
                              }, 3000);
                          }
                      },

                      handleVideoUpload(event) {
                          const file = event.target.files[0];
                          if(file) {
                              this.type = 'video';
                              this.previewVideo = URL.createObjectURL(file);
                          }
                      },
                      
                      updatePreviewImages(event) {
                          // This will be called when the gallery-picker-updated event is dispatched
                          if (event && event.detail) {
                              this.previewImages = [...event.detail.urls, ...event.detail.filePreviews];
                              this.currentSlide = 0;
                          }
                      },
                      
                      isTranslating: false,
                      async autoTranslate() {
                          this.isTranslating = true;
                          const translateUrl = '{{ route('admin.posts.translate') }}';
                          
                          const translateText = async (text, targetKey) => {
                              if (!text) return;
                              try {
                                  const response = await fetch(translateUrl, {
                                      method: 'POST',
                                      headers: {
                                          'Content-Type': 'application/json',
                                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                          'Accept': 'application/json' 
                                      },
                                      body: JSON.stringify({
                                          text: text,
                                          source: 'id',
                                          target: 'en'
                                      })
                                  });

                                  if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                                  const data = await response.json();
                                  
                                  if (data.success) {
                                      this[targetKey] = data.translation;
                                  }
                              } catch (e) {
                                  console.error('Translation error:', e);
                              }
                          };

                          await Promise.all([
                              translateText(this.badge_id, 'badge_en'),
                              translateText(this.title_id, 'title_en'),
                              translateText(this.subtitle_id, 'subtitle_en'),
                              translateText(this.btn_id, 'btn_en')
                          ]);

                          this.isTranslating = false;
                          window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjemahan berhasil!', type: 'success' } }));
                      }
                  }" 
                  @gallery-picker-updated="updatePreviewImages"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="max-w-5xl mx-auto space-y-8">
                    
                    <!-- 1. Background Media Type -->
                    <div class="space-y-6">
                        <!-- Type Selection -->
                        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-500"></i>
                                Tipe Latar Belakang
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative flex flex-col items-center gap-3 p-6 rounded-3xl border-2 cursor-pointer transition-all text-center"
                                    :class="type === 'map' ? 'border-blue-500 bg-blue-50/50 text-blue-900 shadow-md transform -translate-y-1' : 'border-gray-100 bg-white hover:border-blue-200 hover:bg-gray-50 text-gray-500'">
                                    <input type="radio" name="type" value="map" x-model="type" class="sr-only">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-colors" :class="type === 'map' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400'">
                                        <i class="fa-solid fa-map-location-dot text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold mb-1" :class="type === 'map' ? 'text-blue-900' : 'text-gray-900'">Peta 3D (Default)</span>
                                        <span class="block text-[11px] leading-relaxed opacity-80">Integrasi MapLibre interaktif.</span>
                                    </div>
                                </label>
                                
                                <label class="relative flex flex-col items-center gap-3 p-6 rounded-3xl border-2 cursor-pointer transition-all text-center"
                                    :class="type === 'video' ? 'border-blue-500 bg-blue-50/50 text-blue-900 shadow-md transform -translate-y-1' : 'border-gray-100 bg-white hover:border-blue-200 hover:bg-gray-50 text-gray-500'">
                                    <input type="radio" name="type" value="video" x-model="type" class="sr-only">
                                     <div class="w-12 h-12 rounded-full flex items-center justify-center transition-colors" :class="type === 'video' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400'">
                                        <i class="fa-solid fa-film text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold mb-1" :class="type === 'video' ? 'text-blue-900' : 'text-gray-900'">Video Latar</span>
                                        <span class="block text-[11px] leading-relaxed opacity-80">Berputar otomatis (Maks 50MB).</span>
                                    </div>
                                </label>
                                
                                <label class="relative flex flex-col items-center gap-3 p-6 rounded-3xl border-2 cursor-pointer transition-all text-center"
                                    :class="type === 'image' ? 'border-blue-500 bg-blue-50/50 text-blue-900 shadow-md transform -translate-y-1' : 'border-gray-100 bg-white hover:border-blue-200 hover:bg-gray-50 text-gray-500'">
                                    <input type="radio" name="type" value="image" x-model="type" class="sr-only">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-colors" :class="type === 'image' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400'">
                                        <i class="fa-solid fa-images text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold mb-1" :class="type === 'image' ? 'text-blue-900' : 'text-gray-900'">Gambar Slider</span>
                                        <span class="block text-[11px] leading-relaxed opacity-80">Carousel dinamis memudar.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 2. Media Uploads -->
                        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm" x-show="type !== 'map'">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-photo-film text-blue-500"></i>
                                Berkas Media
                            </h3>
                            
                            <!-- Video Target -->
                            <div x-show="type === 'video'" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Desktop Video -->
                                    <div class="relative group rounded-[2rem] border border-gray-100 bg-gray-50 shadow-sm overflow-hidden flex flex-col hover:border-blue-200 transition-colors">
                                        <div class="px-6 py-5 border-b border-gray-100 bg-white flex flex-wrap items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                                    <i class="fa-solid fa-tv"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900">Layar Lebar</h4>
                                                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Lanskap 16:9</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-widest">Wajib</span>
                                        </div>
                                        <div class="p-6 flex-1 flex flex-col justify-center">
                                            <input type="file" name="video_file" @change="handleVideoUpload" accept="video/mp4,video/webm,video/ogg" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border file:border-gray-200 file:text-xs file:font-bold file:bg-white file:text-gray-700 hover:file:bg-gray-50 transition-colors cursor-pointer bg-white rounded-xl border border-dashed border-gray-200 hover:border-blue-300 focus:outline-none">
                                            
                                            @if($setting->type === 'video' && !empty($setting->media_paths))
                                                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                                                    <div class="relative rounded-2xl overflow-hidden bg-slate-900 aspect-video ring-1 ring-gray-900/5 shadow-inner">
                                                        <video src="{{ Storage::url($setting->media_paths[0]) }}" controls class="w-full h-full object-cover"></video>
                                                    </div>
                                                    <label class="inline-flex items-center gap-2 cursor-pointer mt-2 group/del">
                                                        <input type="checkbox" name="remove_media" value="1" class="text-red-500 focus:ring-red-500 border-gray-300 rounded transition-colors group-hover/del:border-red-400">
                                                        <span class="text-[11px] text-red-600 font-bold group-hover/del:text-red-700">Hapus Video Ini</span>
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Mobile Video -->
                                    <div class="relative group rounded-[2rem] border border-gray-100 bg-gray-50 shadow-sm overflow-hidden flex flex-col hover:border-indigo-200 transition-colors">
                                        <div class="px-6 py-5 border-b border-gray-100 bg-white flex flex-wrap items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                    <i class="fa-solid fa-mobile-screen"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900">Layar Genggam</h4>
                                                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Potret 9:16</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-full uppercase tracking-widest">Opsional</span>
                                        </div>
                                        <div class="p-6 flex-1 flex flex-col justify-center">
                                            <input type="file" name="mobile_video_file" accept="video/mp4,video/webm,video/ogg" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border file:border-gray-200 file:text-xs file:font-bold file:bg-white file:text-gray-700 hover:file:bg-gray-50 transition-colors cursor-pointer bg-white rounded-xl border border-dashed border-gray-200 hover:border-indigo-300 focus:outline-none">
                                            <p class="text-[11px] text-gray-400 mt-4 leading-relaxed flex items-start gap-2">
                                                <i class="fa-solid fa-circle-info mt-0.5 text-indigo-400"></i>
                                                Disarankan agar area pinggir video utama (Layar Lebar) tidak terpotong di HP.
                                            </p>

                                            @if($setting->type === 'video' && !empty($setting->mobile_media_paths))
                                                <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col items-center">
                                                    <div class="relative rounded-2xl overflow-hidden bg-slate-900 w-[50%] aspect-[9/16] ring-1 ring-gray-900/5 shadow-inner">
                                                        <video src="{{ Storage::url($setting->mobile_media_paths[0]) }}" controls class="w-full h-full object-cover"></video>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Target -->
                            <div x-show="type === 'image'" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Desktop Images -->
                                    <div class="relative group rounded-[2rem] border border-gray-100 bg-gray-50 shadow-sm overflow-hidden flex flex-col hover:border-blue-200 transition-colors">
                                        <div class="px-6 py-5 border-b border-gray-100 bg-white flex flex-wrap items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                                    <i class="fa-solid fa-tv"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900">Slider Layar Lebar</h4>
                                                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Lanskap 16:9</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-widest">Wajib</span>
                                        </div>
                                        <div class="p-6 flex-1 flex flex-col">
                                            <div class="bg-white p-5 rounded-2xl border border-dashed border-gray-200 hover:border-blue-300 hover:bg-blue-50/10 transition-all flex flex-col cursor-pointer">
                                                <x-admin.gallery-picker-multiple 
                                                    name="image_files" 
                                                    :values="isset($setting->media_paths) && $setting->type === 'image' ? array_map(fn($p) => Storage::url($p), $setting->media_paths) : []" 
                                                    label="Pilih Galeri Lanskap" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile Images -->
                                    <div class="relative group rounded-[2rem] border border-gray-100 bg-gray-50 shadow-sm overflow-hidden flex flex-col hover:border-indigo-200 transition-colors">
                                         <div class="px-6 py-5 border-b border-gray-100 bg-white flex flex-wrap items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                    <i class="fa-solid fa-mobile-screen"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900">Slider Layar Genggam</h4>
                                                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Potret 9:16</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-full uppercase tracking-widest">Opsional</span>
                                        </div>
                                        <div class="p-6 flex-1 flex flex-col">
                                            <div class="bg-white p-5 rounded-2xl border border-dashed border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/10 transition-all flex flex-col cursor-pointer">
                                                <x-admin.gallery-picker-multiple 
                                                    name="mobile_image_files" 
                                                    :values="isset($setting->mobile_media_paths) && $setting->type === 'image' ? array_map(fn($p) => Storage::url($p), $setting->mobile_media_paths) : []" 
                                                    label="Pilih Galeri Potret" />
                                            </div>
                                            <p class="text-[11px] text-gray-400 mt-4 leading-relaxed flex items-start gap-2">
                                                <i class="fa-solid fa-circle-info mt-0.5 text-indigo-400"></i>
                                                Jumlah gambar potret di atas *harus sama* dengan jumlah gambar lanskap di sebelahnya.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Text Content -->
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-font text-blue-500"></i>
                                Konten Teks
                            </h3>
                            <button type="button" 
                                    @click="autoTranslate()"
                                    :disabled="isTranslating"
                                    class="px-4 py-2 bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2 disabled:opacity-50">
                                <template x-if="!isTranslating">
                                    <div class="flex items-center gap-2"><i class="fa-solid fa-wand-magic-sparkles"></i> Terjemahkan ke English</div>
                                </template>
                                 <template x-if="isTranslating">
                                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-notch fa-spin"></i> Sedang Menerjemahkan...</div>
                                </template>
                            </button>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-gray-50/50 text-gray-500 text-xs p-4 rounded-2xl flex gap-3 border border-gray-100">
                                 <i class="fa-solid fa-circle-info mt-0.5"></i>
                                 <p class="font-medium leading-relaxed">Seluruh teks opsional. Jika dikosongkan, Hero hanya akan menampilkan gambar/video tanpa tulisan.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <!-- Badge -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Badge <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                    <input type="text" name="badge_id" value="{{ old('badge_id', $setting->badge_id) }}" x-model="badge_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium" placeholder="Contoh: Jelajahi Jepara">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Badge <span class="text-gray-400 font-normal ml-1">English</span></label>
                                    <input type="text" name="badge_en" value="{{ old('badge_en', $setting->badge_en) }}" x-model="badge_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium" placeholder="Ex: Explore Jepara">
                                </div>

                                <!-- Title -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Judul Utama <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                    <textarea name="title_id" rows="2" x-model="title_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium" placeholder="Temukan Keajaiban">{{ old('title_id', $setting->title_id) }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Judul Utama <span class="text-gray-400 font-normal ml-1">English</span></label>
                                    <textarea name="title_en" rows="2" x-model="title_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium" placeholder="Discover Wonders">{{ old('title_en', $setting->title_en) }}</textarea>
                                </div>

                                <!-- Subtitle -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Subjudul <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                    <textarea name="subtitle_id" rows="3" x-model="subtitle_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium" placeholder="Deskripsi pendek...">{{ old('subtitle_id', $setting->subtitle_id) }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Subjudul <span class="text-gray-400 font-normal ml-1">English</span></label>
                                    <textarea name="subtitle_en" rows="3" x-model="subtitle_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium" placeholder="Short description...">{{ old('subtitle_en', $setting->subtitle_en) }}</textarea>
                                </div>
                                
                                <!-- Button Link -->
                                <div class="md:col-span-2 space-y-2 pt-4 border-t border-gray-100">
                                    <label class="block text-sm font-semibold text-gray-700">URL / Link Tombol</label>
                                    <input type="text" name="button_link" value="{{ old('button_link', $setting->button_link) }}" class="block w-full md:w-1/2 px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium" placeholder="Misal: #jelajah">
                                </div>

                                <!-- Button Texts -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Teks Tombol <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                    <input type="text" name="button_text_id" value="{{ old('button_text_id', $setting->button_text_id) }}" x-model="btn_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium" placeholder="Mulai Petualangan">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Teks Tombol <span class="text-gray-400 font-normal ml-1">English</span></label>
                                    <input type="text" name="button_text_en" value="{{ old('button_text_en', $setting->button_text_en) }}" x-model="btn_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium" placeholder="Start Adventure">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 4. Realtime Preview -->
                    <div class="mt-8 pt-6 border-t border-gray-200 relative">
                        <div class="bg-gray-100 rounded-[2.5rem] border border-gray-200 shadow-sm overflow-hidden relative">
                            <!-- Preview Settings Bar (Floating at Top) -->
                            <div class="absolute top-4 left-4 right-4 z-50 flex items-center justify-between pointer-events-none">
                                <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm border border-white/20 pointer-events-auto">
                                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                        <i class="fa-solid fa-eye text-blue-500"></i> PREVIEW
                                    </h3>
                                </div>
                                <div class="flex gap-2 pointer-events-auto">
                                    <button type="button" @click="previewLang = 'id'" :class="previewLang === 'id' ? 'bg-white shadow-sm font-bold text-gray-900' : 'text-gray-500 hover:bg-white/50'" class="px-4 py-2 text-xs font-medium rounded-xl transition-all border border-transparent" :class="previewLang === 'id' && 'border-gray-200'">ID</button>
                                    <button type="button" @click="previewLang = 'en'" :class="previewLang === 'en' ? 'bg-white shadow-sm font-bold text-gray-900' : 'text-gray-500 hover:bg-white/50'" class="px-4 py-2 text-xs font-medium rounded-xl transition-all border border-transparent" :class="previewLang === 'en' && 'border-gray-200'">EN</button>
                                </div>
                            </div>
                            <div class="relative w-full h-[350px] md:h-[450px] rounded-[2rem] overflow-hidden bg-slate-900 ring-1 ring-slate-900/10 shadow-lg flex flex-col items-center justify-center transition-all duration-500">
                                <!-- Dynamic Simulated Background based on current Type selection -->
                                <div class="absolute inset-0 z-0 flex items-center justify-center transition-opacity duration-300"
                                     :class="type === 'map' ? 'bg-[#1e293b]' : (type === 'video' ? 'bg-[#0f172a]' : 'bg-[#334155]')">
                                    
                                    <!-- Map Fallback -->
                                    <div x-show="type === 'map'" class="absolute inset-0 flex flex-col items-center justify-center opacity-30 gap-3">
                                        <i class="fa-solid fa-map-location-dot text-6xl text-white"></i>
                                        <span class="text-white text-xs font-bold uppercase tracking-widest">MAP Background (Simulated)</span>
                                    </div>
                                    
                                    <!-- Video Preview -->
                                    <div x-show="type === 'video'" class="absolute inset-0 w-full h-full">
                                        <template x-if="previewVideo">
                                            <video :src="previewVideo" autoplay loop muted playsinline class="w-full h-full object-cover"></video>
                                        </template>
                                        <template x-if="!previewVideo">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center opacity-30 gap-3">
                                                <i class="fa-solid fa-film text-6xl text-white"></i>
                                                <span class="text-white text-xs font-bold uppercase tracking-widest">Video Background (Upload Video)</span>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Image Preview -->
                                    <div x-show="type === 'image'" class="absolute inset-0 w-full h-full bg-slate-900">
                                         <template x-if="previewImages.length > 0">
                                             <div>
                                                 <template x-for="(imgSrc, idx) in previewImages" :key="idx">
                                                     <div x-show="currentSlide === idx" 
                                                          x-transition:enter="transition-opacity ease-in-out duration-1000"
                                                          x-transition:enter-start="opacity-0"
                                                          x-transition:enter-end="opacity-100"
                                                          x-transition:leave="transition-opacity ease-in-out duration-1000"
                                                          x-transition:leave-start="opacity-100"
                                                          x-transition:leave-end="opacity-0"
                                                          class="absolute inset-0 w-full h-full">
                                                          <img :src="imgSrc" class="w-full h-full object-cover">
                                                     </div>
                                                 </template>
                                             </div>
                                         </template>
                                         <template x-if="previewImages.length === 0">
                                             <div class="absolute inset-0 flex flex-col items-center justify-center opacity-30 gap-3">
                                                 <i class="fa-solid fa-images text-6xl text-white"></i>
                                                 <span class="text-white text-xs font-bold uppercase tracking-widest">Image Background (Upload Image)</span>
                                             </div>
                                         </template>
                                    </div>
                                </div>
                                
                                <!-- Overlay to darken text background -->
                                <div class="absolute inset-0 z-10 bg-gradient-to-t from-slate-900 via-slate-900/40 to-slate-900/40" x-show="badge_id || title_id || subtitle_id"></div>

                                <!-- Texts overlay -->
                                <div class="absolute inset-0 z-20 flex flex-col items-center justify-center px-4 text-center pointer-events-none p-6">
                                    <div class="w-full max-w-4xl mx-auto space-y-6 flex flex-col items-center">
                                        
                                        <!-- Badge -->
                                        <div class="h-8 flex items-center justify-center">
                                            <span x-show="(previewLang === 'id' && badge_id) || (previewLang === 'en' && badge_en)" 
                                                  x-transition.opacity
                                                  x-text="previewLang === 'id' ? badge_id : badge_en" 
                                                  class="inline-block px-5 py-2 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 text-white text-xs font-bold uppercase tracking-widest shadow-lg">
                                            </span>
                                        </div>
                                        
                                        <!-- Title -->
                                        <div class="h-auto min-h-[4rem] flex items-center justify-center w-full">
                                            <h1 x-show="(previewLang === 'id' && title_id) || (previewLang === 'en' && title_en)" 
                                                x-transition.opacity
                                                x-html="(previewLang === 'id' ? title_id : title_en).replace(/\n/g, '<br>')" 
                                                class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-black leading-tight tracking-tight drop-shadow-2xl selection:bg-blue-500/30 text-center">
                                            </h1>
                                        </div>
                                        
                                        <!-- Subtitle -->
                                        <div class="h-auto min-h-[3rem] flex items-center justify-center w-full">
                                            <p x-show="(previewLang === 'id' && subtitle_id) || (previewLang === 'en' && subtitle_en)" 
                                               x-transition.opacity
                                               x-html="(previewLang === 'id' ? subtitle_id : subtitle_en).replace(/\n/g, '<br>')" 
                                               class="text-slate-200 text-sm md:text-lg font-medium max-w-2xl mx-auto leading-relaxed drop-shadow-lg shadow-black/50 text-center">
                                            </p>
                                        </div>
                                        
                                        <!-- Button -->
                                        <div class="h-14 flex items-center justify-center pt-2">
                                            <div x-show="(previewLang === 'id' && btn_id) || (previewLang === 'en' && btn_en)" x-transition.opacity>
                                                <span class="inline-flex items-center justify-center h-12 px-8 rounded-full bg-blue-600 text-white text-sm font-bold shadow-xl overflow-hidden relative group">
                                                    <span class="relative z-10" x-text="previewLang === 'id' ? btn_id : btn_en"></span>
                                                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Action -->
                    <div class="flex justify-between items-center mt-8 pt-8 border-t border-gray-200">
                        <p class="text-xs text-gray-500">Selalu pastikan hasil <strong>Preview</strong> sudah sesuai ekspektasi sebelum menyimpan.</p>
                        <button type="submit" class="inline-flex items-center px-8 py-4 bg-gray-900 rounded-2xl font-bold text-sm text-white hover:bg-gray-800 active:bg-black focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
