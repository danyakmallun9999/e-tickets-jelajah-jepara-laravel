<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">
                    Pengaturan Profil (Pearl of Peninsula)
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

            <form action="{{ route('admin.profile-settings.update') }}" method="POST" enctype="multipart/form-data" 
                  x-data="{ 
                      previewLang: 'id',
                      label_id: {{ \Illuminate\Support\Js::from(old('label_id', $setting->label_id ?? '')) }},
                      label_en: {{ \Illuminate\Support\Js::from(old('label_en', $setting->label_en ?? '')) }},
                      title_id: {{ \Illuminate\Support\Js::from(old('title_id', $setting->title_id ?? '')) }},
                      title_en: {{ \Illuminate\Support\Js::from(old('title_en', $setting->title_en ?? '')) }},
                      description_id: {{ \Illuminate\Support\Js::from(old('description_id', $setting->description_id ?? '')) }},
                      description_en: {{ \Illuminate\Support\Js::from(old('description_en', $setting->description_en ?? '')) }},
                      stat_count: {{ \Illuminate\Support\Js::from(old('stat_count', $setting->stat_count ?? '')) }},
                      stat_label_id: {{ \Illuminate\Support\Js::from(old('stat_label_id', $setting->stat_label_id ?? '')) }},
                      stat_label_en: {{ \Illuminate\Support\Js::from(old('stat_label_en', $setting->stat_label_en ?? '')) }},
                      pillar_nature_title_id: {{ \Illuminate\Support\Js::from(old('pillar_nature_title_id', $setting->pillar_nature_title_id ?? '')) }},
                      pillar_nature_title_en: {{ \Illuminate\Support\Js::from(old('pillar_nature_title_en', $setting->pillar_nature_title_en ?? '')) }},
                      pillar_nature_desc_id: {{ \Illuminate\Support\Js::from(old('pillar_nature_desc_id', $setting->pillar_nature_desc_id ?? '')) }},
                      pillar_nature_desc_en: {{ \Illuminate\Support\Js::from(old('pillar_nature_desc_en', $setting->pillar_nature_desc_en ?? '')) }},
                      pillar_heritage_title_id: {{ \Illuminate\Support\Js::from(old('pillar_heritage_title_id', $setting->pillar_heritage_title_id ?? '')) }},
                      pillar_heritage_title_en: {{ \Illuminate\Support\Js::from(old('pillar_heritage_title_en', $setting->pillar_heritage_title_en ?? '')) }},
                      pillar_heritage_desc_id: {{ \Illuminate\Support\Js::from(old('pillar_heritage_desc_id', $setting->pillar_heritage_desc_id ?? '')) }},
                      pillar_heritage_desc_en: {{ \Illuminate\Support\Js::from(old('pillar_heritage_desc_en', $setting->pillar_heritage_desc_en ?? '')) }},
                      pillar_arts_title_id: {{ \Illuminate\Support\Js::from(old('pillar_arts_title_id', $setting->pillar_arts_title_id ?? '')) }},
                      pillar_arts_title_en: {{ \Illuminate\Support\Js::from(old('pillar_arts_title_en', $setting->pillar_arts_title_en ?? '')) }},
                      pillar_arts_desc_id: {{ \Illuminate\Support\Js::from(old('pillar_arts_desc_id', $setting->pillar_arts_desc_id ?? '')) }},
                      pillar_arts_desc_en: {{ \Illuminate\Support\Js::from(old('pillar_arts_desc_en', $setting->pillar_arts_desc_en ?? '')) }},
                      
                      get currentData() {
                          const lang = this.previewLang;
                          return {
                              label: lang === 'id' ? this.label_id : this.label_en,
                              title: lang === 'id' ? this.title_id : this.title_en,
                              description: lang === 'id' ? this.description_id : this.description_en,
                              stat_count: this.stat_count,
                              stat_label: lang === 'id' ? this.stat_label_id : this.stat_label_en,
                              nature_title: lang === 'id' ? this.pillar_nature_title_id : this.pillar_nature_title_en,
                              nature_desc: lang === 'id' ? this.pillar_nature_desc_id : this.pillar_nature_desc_en,
                              heritage_title: lang === 'id' ? this.pillar_heritage_title_id : this.pillar_heritage_title_en,
                              heritage_desc: lang === 'id' ? this.pillar_heritage_desc_id : this.pillar_heritage_desc_en,
                              arts_title: lang === 'id' ? this.pillar_arts_title_id : this.pillar_arts_title_en,
                              arts_desc: lang === 'id' ? this.pillar_arts_desc_id : this.pillar_arts_desc_en,
                          };
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
                              translateText(this.label_id, 'label_en'),
                              translateText(this.title_id, 'title_en'),
                              translateText(this.description_id, 'description_en'),
                              translateText(this.stat_label_id, 'stat_label_en'),
                              translateText(this.pillar_nature_title_id, 'pillar_nature_title_en'),
                              translateText(this.pillar_nature_desc_id, 'pillar_nature_desc_en'),
                              translateText(this.pillar_heritage_title_id, 'pillar_heritage_title_en'),
                              translateText(this.pillar_heritage_desc_id, 'pillar_heritage_desc_en'),
                              translateText(this.pillar_arts_title_id, 'pillar_arts_title_en'),
                              translateText(this.pillar_arts_desc_id, 'pillar_arts_desc_en')
                          ]);

                          this.isTranslating = false;
                          window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjemahan berhasil!', type: 'success' } }));
                      }
                  }" 
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="max-w-5xl mx-auto space-y-8">
                    
                    <!-- 1. Text Content -->
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <!-- Label -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Label <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                <input type="text" name="label_id" value="{{ old('label_id', $setting->label_id) }}" x-model="label_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Label <span class="text-gray-400 font-normal ml-1">English</span></label>
                                <input type="text" name="label_en" value="{{ old('label_en', $setting->label_en) }}" x-model="label_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium">
                            </div>

                            <!-- Title -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Judul <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                <textarea name="title_id" rows="2" x-model="title_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">{{ old('title_id', $setting->title_id) }}</textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Judul <span class="text-gray-400 font-normal ml-1">English</span></label>
                                <textarea name="title_en" rows="2" x-model="title_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium">{{ old('title_en', $setting->title_en) }}</textarea>
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Deskripsi <span class="text-gray-400 font-normal ml-1">Indonesia</span></label>
                                <textarea name="description_id" rows="4" x-model="description_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">{{ old('description_id', $setting->description_id) }}</textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Deskripsi <span class="text-gray-400 font-normal ml-1">English</span></label>
                                <textarea name="description_en" rows="4" x-model="description_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium">{{ old('description_en', $setting->description_en) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Statistics & Images -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-chart-simple text-blue-500"></i>
                                Statistik
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Jumlah (Angka)</label>
                                    <input type="text" name="stat_count" value="{{ old('stat_count', $setting->stat_count) }}" x-model="stat_count" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium" placeholder="Contoh: 150">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Label (ID)</label>
                                        <input type="text" name="stat_label_id" value="{{ old('stat_label_id', $setting->stat_label_id) }}" x-model="stat_label_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Label (EN)</label>
                                        <input type="text" name="stat_label_en" value="{{ old('stat_label_en', $setting->stat_label_en) }}" x-model="stat_label_en" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-image text-blue-500"></i>
                                Media Gambar
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl">
                                    <x-admin.gallery-picker name="image_main" 
                                        :value="$setting->image_main ? Storage::url($setting->image_main) : null" 
                                        label="Gambar Utama (Portrait)" />
                                    <x-input-error :messages="$errors->get('image_main')" class="mt-2" />
                                </div>
                                <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl">
                                    <x-admin.gallery-picker name="image_secondary" 
                                        :value="$setting->image_secondary ? Storage::url($setting->image_secondary) : null" 
                                        label="Gambar Sekunder (Kecil)" />
                                    <x-input-error :messages="$errors->get('image_secondary')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Pillars -->
                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-200 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-8 flex items-center gap-2 border-b border-gray-50 pb-4">
                            <i class="fa-solid fa-columns text-blue-500"></i>
                            Pillar (Highlights)
                        </h3>
                        
                        <div class="space-y-12">
                            <!-- Nature -->
                            <div class="relative pl-6 border-l-4 border-emerald-500 py-2">
                                <h4 class="text-sm font-black uppercase tracking-widest text-emerald-600 mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-leaf"></i> Pillar 1: Nature
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Judul (ID)</label>
                                            <input type="text" name="pillar_nature_title_id" value="{{ old('pillar_nature_title_id', $setting->pillar_nature_title_id) }}" x-model="pillar_nature_title_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Ringkas (ID)</label>
                                            <textarea name="pillar_nature_desc_id" rows="2" x-model="pillar_nature_desc_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium">{{ old('pillar_nature_desc_id', $setting->pillar_nature_desc_id) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Title (EN)</label>
                                            <input type="text" name="pillar_nature_title_en" value="{{ old('pillar_nature_title_en', $setting->pillar_nature_title_en) }}" x-model="pillar_nature_title_en" class="block w-full px-4 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Short Description (EN)</label>
                                            <textarea name="pillar_nature_desc_en" rows="2" x-model="pillar_nature_desc_en" class="block w-full px-4 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium">{{ old('pillar_nature_desc_en', $setting->pillar_nature_desc_en) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Heritage -->
                            <div class="relative pl-6 border-l-4 border-amber-500 py-2">
                                <h4 class="text-sm font-black uppercase tracking-widest text-amber-600 mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-landmark"></i> Pillar 2: Heritage
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Judul (ID)</label>
                                            <input type="text" name="pillar_heritage_title_id" value="{{ old('pillar_heritage_title_id', $setting->pillar_heritage_title_id) }}" x-model="pillar_heritage_title_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Ringkas (ID)</label>
                                            <textarea name="pillar_heritage_desc_id" rows="2" x-model="pillar_heritage_desc_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all font-medium">{{ old('pillar_heritage_desc_id', $setting->pillar_heritage_desc_id) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Title (EN)</label>
                                            <input type="text" name="pillar_heritage_title_en" value="{{ old('pillar_heritage_title_en', $setting->pillar_heritage_title_en) }}" x-model="pillar_heritage_title_en" class="block w-full px-4 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Short Description (EN)</label>
                                            <textarea name="pillar_heritage_desc_en" rows="2" x-model="pillar_heritage_desc_en" class="block w-full px-4 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all font-medium">{{ old('pillar_heritage_desc_en', $setting->pillar_heritage_desc_en) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Arts -->
                            <div class="relative pl-6 border-l-4 border-purple-500 py-2">
                                <h4 class="text-sm font-black uppercase tracking-widest text-purple-600 mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-palette"></i> Pillar 3: Arts
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Judul (ID)</label>
                                            <input type="text" name="pillar_arts_title_id" value="{{ old('pillar_arts_title_id', $setting->pillar_arts_title_id) }}" x-model="pillar_arts_title_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Ringkas (ID)</label>
                                            <textarea name="pillar_arts_desc_id" rows="2" x-model="pillar_arts_desc_id" class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all font-medium">{{ old('pillar_arts_desc_id', $setting->pillar_arts_desc_id) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Title (EN)</label>
                                            <input type="text" name="pillar_arts_title_en" value="{{ old('pillar_arts_title_en', $setting->pillar_arts_title_en) }}" x-model="pillar_arts_title_en" class="block w-full px-4 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Short Description (EN)</label>
                                            <textarea name="pillar_arts_desc_en" rows="2" x-model="pillar_arts_desc_en" class="block w-full px-4 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 focus:bg-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all font-medium">{{ old('pillar_arts_desc_en', $setting->pillar_arts_desc_en) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="bg-gray-900 p-8 md:p-12 rounded-[3rem] shadow-2xl overflow-hidden relative">
                        <div class="flex items-center justify-between mb-8">
                             <h3 class="text-white font-bold flex items-center gap-2">
                                <i class="fa-solid fa-eye text-blue-400"></i>
                                Live Preview
                            </h3>
                            <div class="flex bg-gray-800 p-1 rounded-xl">
                                <button type="button" @click="previewLang = 'id'" :class="previewLang === 'id' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white'" class="px-3 py-1 text-xs font-bold rounded-lg transition-all">ID</button>
                                <button type="button" @click="previewLang = 'en'" :class="previewLang === 'en' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white'" class="px-3 py-1 text-xs font-bold rounded-lg transition-all">EN</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16 lg:gap-32 items-center opacity-90">
                            <div class="space-y-6 md:space-y-7">
                                <div class="space-y-1 md:space-y-2">
                                    <span class="block text-[10px] md:text-xs font-bold uppercase tracking-[0.25em] text-blue-400" x-text="currentData.label"></span>
                                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-poppins font-bold leading-[1.2] md:leading-[1.1] transition-all duration-300 max-w-xl">
                                        <template x-if="currentData.title">
                                            <div>
                                                <span class="text-white" x-text="currentData.title.split('\n')[0]"></span>
                                                <template x-if="currentData.title.split('\n').length > 1">
                                                    <div>
                                                        <span class="text-gray-500 text-3xl md:text-5xl lg:text-6xl" x-text="currentData.title.split('\n').slice(1).join('\n')"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </h2>
                                </div>
                                <p class="text-base md:text-lg text-gray-400 leading-relaxed font-light max-w-md whitespace-pre-line opacity-80" x-text="currentData.description"></p>
                                
                                <div class="pt-5 md:pt-6 mt-5 md:mt-6 border-t border-gray-800">
                                    <div class="grid grid-cols-3 gap-4 md:gap-6">
                                        <div class="group cursor-default">
                                            <span class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 md:mb-2" x-text="currentData.nature_title"></span>
                                            <p class="font-serif text-sm md:text-lg text-white leading-tight whitespace-pre-line" x-text="currentData.nature_desc"></p>
                                        </div>
                                        <div class="group cursor-default">
                                            <span class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 md:mb-2" x-text="currentData.heritage_title"></span>
                                            <p class="font-serif text-sm md:text-lg text-white leading-tight whitespace-pre-line" x-text="currentData.heritage_desc"></p>
                                        </div>
                                        <div class="group cursor-default">
                                            <span class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 md:mb-2" x-text="currentData.arts_title"></span>
                                            <p class="font-serif text-sm md:text-lg text-white leading-tight whitespace-pre-line" x-text="currentData.arts_desc"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="relative hidden lg:block">
                                <div class="aspect-[3/4] bg-gray-800 rounded-[2rem] overflow-hidden border border-gray-700 flex items-center justify-center">
                                    @if($setting->image_main)
                                        <img src="{{ Storage::url($setting->image_main) }}" class="w-full h-full object-cover opacity-50">
                                    @else
                                        <i class="fa-solid fa-image text-4xl text-gray-700"></i>
                                    @endif
                                </div>
                                <div class="absolute -top-4 -right-4 bg-gray-800 border border-gray-700 p-4 rounded-2xl shadow-xl text-center min-w-[100px]">
                                    <span class="block text-2xl font-serif text-white" x-text="stat_count + '+'"></span>
                                    <span class="text-[8px] font-black uppercase tracking-widest text-blue-400" x-text="currentData.stat_label"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Action -->
                    <div class="flex justify-end items-center mt-8 pt-8 border-t border-gray-200">
                        <button type="submit" class="inline-flex items-center px-8 py-4 bg-gray-900 rounded-2xl font-bold text-sm text-white hover:bg-gray-800 active:bg-black focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
