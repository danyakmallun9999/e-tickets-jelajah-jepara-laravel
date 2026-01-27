<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Berita / Agenda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $post->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Jenis Posting')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="news" {{ old('type', $post->type) == 'news' ? 'selected' : '' }}>Berita</option>
                                    <option value="event" {{ old('type', $post->type) == 'event' ? 'selected' : '' }}>Agenda / Event</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Published At -->
                            <div>
                                <x-input-label for="published_at" :value="__('Tanggal Tayang')" />
                                <x-text-input id="published_at" class="block mt-1 w-full" type="date" name="published_at" :value="old('published_at', $post->published_at ? $post->published_at->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                            </div>

                             <!-- Is Published -->
                             <div class="flex items-center pt-6">
                                <label for="is_published" class="inline-flex items-center">
                                    <input id="is_published" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Langsung Terbitkan?') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <x-input-label for="image" :value="__('Ganti Gambar (Opsional)')" />
                            <div class="flex items-center gap-4">
                                @if($post->image_path)
                                    <div class="shrink-0">
                                        <img src="{{ $post->image_path }}" class="h-20 w-32 object-cover rounded-md" alt="Current Image" />
                                    </div>
                                @endif
                                <input type="file" id="image" name="image" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100" />
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 2MB.</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Isi Konten')" />
                            <textarea id="content" name="content" rows="10" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content', $post->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
