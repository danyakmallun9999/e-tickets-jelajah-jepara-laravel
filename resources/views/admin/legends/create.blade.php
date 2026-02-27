<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.legends.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-blue-600 transition-colors" wire:navigate>
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="hidden md:block text-sm text-gray-500 mb-0.5">Admin Panel</p>
                <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">
                    Tambah Tokoh Sejarah
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.legends.store') }}" method="POST" enctype="multipart/form-data" 
                  x-data="{
                      isTranslatingQuote: false,
                      isTranslatingDesc: false,

                      async autoTranslate(type) {
                          const translateUrl = '{{ route('admin.posts.translate') }}';
                          
                          if (type === 'quote') {
                              this.isTranslatingQuote = true;
                              const sourceText = this.$refs.quote_id.value;
                              if (!sourceText) {
                                  this.isTranslatingQuote = false;
                                  return;
                              }
                              await this.performTranslation(sourceText, 'quote_en', translateUrl);
                              this.isTranslatingQuote = false;
                          } else if (type === 'desc') {
                              this.isTranslatingDesc = true;
                              const sourceText = this.$refs.description_id.value;
                              if (!sourceText) {
                                  this.isTranslatingDesc = false;
                                  return;
                              }
                              await this.performTranslation(sourceText, 'description_en', translateUrl);
                              this.isTranslatingDesc = false;
                          }
                      },

                      async performTranslation(text, targetRef, url) {
                          try {
                              const response = await fetch(url, {
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
                                  if (this.$refs[targetRef]) {
                                      this.$refs[targetRef].value = data.translation;
                                      window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjemahan berhasil!', type: 'success' } }));
                                  }
                              } else {
                                  window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal menerjemahkan.', type: 'error' } }));
                              }
                          } catch (e) {
                               console.error(e);
                               window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjadi kesalahan sistem.', type: 'error' } }));
                          }
                      }
                  }">
                @csrf
                <div class="space-y-6">
                    {{-- Basic Info --}}
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Tokoh</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                       class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-0 focus:border-blue-500 transition-all" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-admin.gallery-picker name="image" label="Foto Tokoh" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampil</label>
                                <input id="order" name="order" type="number" value="{{ old('order', 0) }}"
                                       class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-0 focus:border-blue-500 transition-all" />
                                <x-input-error :messages="$errors->get('order')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Translations - Quotes --}}
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm relative">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-quote-left text-blue-500"></i>
                                Kutipan / Slogan
                            </h3>
                            <button type="button" 
                                    @click="autoTranslate('quote')"
                                    :disabled="isTranslatingQuote"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-all shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5 active:translate-y-0">
                                <template x-if="!isTranslatingQuote">
                                    <div class="flex items-center gap-2"><i class="fa-solid fa-wand-magic-sparkles"></i> Terjemahkan</div>
                                </template>
                                 <template x-if="isTranslatingQuote">
                                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-notch fa-spin"></i> Translating...</div>
                                </template>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="quote_id" class="block text-sm font-semibold text-gray-700 mb-2">Kutipan (Indonesia)</label>
                                <input id="quote_id" name="quote_id" type="text" value="{{ old('quote_id') }}"
                                       x-ref="quote_id"
                                       class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-0 focus:border-blue-500 transition-all" />
                                <x-input-error :messages="$errors->get('quote_id')" class="mt-2" />
                            </div>
                            <div>
                                <label for="quote_en" class="block text-sm font-semibold text-gray-700 mb-2">Kutipan (English)</label>
                                <input id="quote_en" name="quote_en" type="text" value="{{ old('quote_en') }}"
                                       x-ref="quote_en"
                                       class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-0 focus:border-blue-500 transition-all" />
                                <x-input-error :messages="$errors->get('quote_en')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Translations - Descriptions --}}
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-sm relative">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-align-left text-blue-500"></i>
                                Deskripsi Singkat
                            </h3>
                            <button type="button" 
                                    @click="autoTranslate('desc')"
                                    :disabled="isTranslatingDesc"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-all shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5 active:translate-y-0">
                                <template x-if="!isTranslatingDesc">
                                    <div class="flex items-center gap-2"><i class="fa-solid fa-wand-magic-sparkles"></i> Terjemahkan</div>
                                </template>
                                 <template x-if="isTranslatingDesc">
                                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-notch fa-spin"></i> Translating...</div>
                                </template>
                            </button>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label for="description_id" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Indonesia)</label>
                                <textarea name="description_id" id="description_id" rows="3" 
                                          x-ref="description_id"
                                          class="mt-1 block w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-0 focus:border-blue-500 transition-all shadow-sm">{{ old('description_id') }}</textarea>
                                <x-input-error :messages="$errors->get('description_id')" class="mt-2" />
                            </div>
                            <div>
                                <label for="description_en" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (English)</label>
                                <textarea name="description_en" id="description_en" rows="3" 
                                          x-ref="description_en"
                                          class="mt-1 block w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:ring-0 focus:border-blue-500 transition-all shadow-sm">{{ old('description_en') }}</textarea>
                                <x-input-error :messages="$errors->get('description_en')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <label class="inline-flex items-center mr-4 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600 font-medium">Aktifkan langsung</span>
                        </label>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Simpan Tokoh
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
