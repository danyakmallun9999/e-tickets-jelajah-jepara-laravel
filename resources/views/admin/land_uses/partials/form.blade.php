<form x-data="{ area: '' }" @area-calculated.window="area = $event.detail; $refs.areaInput.value = area"
    action="{{ $action }}" method="POST" class="flex h-[calc(100vh-80px)]">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <!-- Left Sidebar: Form Inputs -->
    <div class="w-96 min-w-[380px] bg-white border-r border-gray-200 flex flex-col z-10">
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Detail Penggunaan Lahan</h3>
                <p class="text-xs text-gray-500">Isi informasi detail mengenai penggunaan lahan ini.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lahan</label>
                <input type="text" name="name" value="{{ old('name', $landUse->name) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required placeholder="Contoh: Sawah Pak Budi">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                <select name="type"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>
                    <option value="settlement" @selected(old('type', $landUse->type) === 'settlement')>Permukiman</option>
                    <option value="rice_field" @selected(old('type', $landUse->type) === 'rice_field')>Persawahan</option>
                    <option value="plantation" @selected(old('type', $landUse->type) === 'plantation')>Perkebunan</option>
                    <option value="forest" @selected(old('type', $landUse->type) === 'forest')>Hutan</option>
                    <option value="other" @selected(old('type', $landUse->type) === 'other')>Lainnya</option>
                </select>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Luas (Hektar)</label>
                <input x-ref="areaInput" type="number" name="area_hectares"
                    value="{{ old('area_hectares', $landUse->area_hectares) }}" step="0.0001"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Otomatis dihitung dari gambar polygon.</p>
                <x-input-error :messages="$errors->get('area_hectares')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pemilik</label>
                <input type="text" name="owner" value="{{ old('owner', $landUse->owner) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Nama pemilik lahan">
                <x-input-error :messages="$errors->get('owner')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Deskripsi singkat...">{{ old('description', $landUse->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('admin.land-uses.index') }}"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-white transition text-sm font-medium">
                Batal
            </a>
            <button type="submit"
                class="px-5 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition text-sm shadow-md hover:shadow-lg">
                {{ $submitLabel }}
            </button>
        </div>
    </div>

    <!-- Right Area: Map -->
    <div class="flex-1 bg-gray-100 relative">
        <div class="absolute inset-0 p-4">
            <!-- Map Drawing Component for Polygon -->
            @include('admin.components.map-drawer', [
                'drawType' => 'polygon',
                'initialGeometry' => old('geometry', $landUse->geometry),
                'height' => 'h-full',
            ])
            @include('admin.components.load-geometry')
            <x-input-error :messages="$errors->get('geometry')" class="mt-2" />
        </div>
    </div>
</form>
