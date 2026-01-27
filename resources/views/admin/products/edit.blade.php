<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Produk')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Price -->
                            <div class="mb-4">
                                <x-input-label for="price" :value="__('Harga (Rp)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price)" required min="0" step="100" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Image -->
                            <div class="mb-4">
                                <x-input-label for="image" :value="__('Ganti Foto (Opsional)')" />
                                <div class="flex items-center gap-4">
                                    @if($product->image_path)
                                        <div class="shrink-0">
                                            <img src="{{ $product->image_path }}" class="h-16 w-16 object-cover rounded-md" alt="Current Image" />
                                        </div>
                                    @endif
                                    <input type="file" id="image" name="image" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100" />
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Maksimal 2MB (JPEG, PNG).</p>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi Produk')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Seller Name -->
                            <div class="mb-4">
                                <x-input-label for="seller_name" :value="__('Nama Penjual / Toko')" />
                                <x-text-input id="seller_name" class="block mt-1 w-full" type="text" name="seller_name" :value="old('seller_name', $product->seller_name)" placeholder="Contoh: Kerajinan Ukir Pak Joyo" />
                                <x-input-error :messages="$errors->get('seller_name')" class="mt-2" />
                            </div>

                            <!-- Seller Contact -->
                            <div class="mb-4">
                                <x-input-label for="seller_contact" :value="__('Nomor WhatsApp')" />
                                <x-text-input id="seller_contact" class="block mt-1 w-full" type="text" name="seller_contact" :value="old('seller_contact', $product->seller_contact)" placeholder="Contoh: 081234567890" />
                                <x-input-error :messages="$errors->get('seller_contact')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
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
