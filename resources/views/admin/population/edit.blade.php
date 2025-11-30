<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Admin Panel</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Edit Data Kependudukan
                </h2>
            </div>
            <a href="{{ route('admin.population.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.population.update') }}">
                @csrf
                @method('PUT')

                <!-- Main Stats Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-blue-600"></i> Statistik Utama
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Total Population -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-users text-xl"></i>
                                </div>
                                <label for="total_population" class="font-semibold text-gray-700">Total Penduduk</label>
                            </div>
                            <input type="number" id="total_population" name="total_population" 
                                   value="{{ old('total_population', $population->total_population ?? 0) }}"
                                   class="w-full text-3xl font-bold text-gray-900 border-0 border-b-2 border-gray-200 focus:border-green-500 focus:ring-0 px-0 py-2 transition"
                                   placeholder="0">
                            <x-input-error class="mt-2" :messages="$errors->get('total_population')" />
                        </div>

                        <!-- Total Families -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-house-user text-xl"></i>
                                </div>
                                <label for="total_families" class="font-semibold text-gray-700">Kepala Keluarga</label>
                            </div>
                            <input type="number" id="total_families" name="total_families" 
                                   value="{{ old('total_families', $population->total_families ?? 0) }}"
                                   class="w-full text-3xl font-bold text-gray-900 border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 transition"
                                   placeholder="0">
                            <x-input-error class="mt-2" :messages="$errors->get('total_families')" />
                        </div>

                        <!-- Total Male -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                    <i class="fa-solid fa-male text-xl"></i>
                                </div>
                                <label for="total_male" class="font-semibold text-gray-700">Laki-laki</label>
                            </div>
                            <input type="number" id="total_male" name="total_male" 
                                   value="{{ old('total_male', $population->total_male ?? 0) }}"
                                   class="w-full text-3xl font-bold text-gray-900 border-0 border-b-2 border-gray-200 focus:border-indigo-500 focus:ring-0 px-0 py-2 transition"
                                   placeholder="0">
                            <x-input-error class="mt-2" :messages="$errors->get('total_male')" />
                        </div>

                        <!-- Total Female -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600">
                                    <i class="fa-solid fa-female text-xl"></i>
                                </div>
                                <label for="total_female" class="font-semibold text-gray-700">Perempuan</label>
                            </div>
                            <input type="number" id="total_female" name="total_female" 
                                   value="{{ old('total_female', $population->total_female ?? 0) }}"
                                   class="w-full text-3xl font-bold text-gray-900 border-0 border-b-2 border-gray-200 focus:border-pink-500 focus:ring-0 px-0 py-2 transition"
                                   placeholder="0">
                            <x-input-error class="mt-2" :messages="$errors->get('total_female')" />
                        </div>
                    </div>
                </div>

                <!-- Demographics Section -->
                <div class="mb-20">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-blue-600"></i> Detail Demografi
                    </h3>
                    
                    @php
                        $demographics = [
                            'age_groups' => ['label' => 'Kelompok Usia', 'icon' => 'fa-child', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-100', 'placeholder' => 'Contoh: 0-5 Tahun'],
                            'education_levels' => ['label' => 'Tingkat Pendidikan', 'icon' => 'fa-graduation-cap', 'color' => 'text-violet-600', 'bg' => 'bg-violet-100', 'placeholder' => 'Contoh: SD/Sederajat'],
                            'jobs' => ['label' => 'Pekerjaan', 'icon' => 'fa-briefcase', 'color' => 'text-amber-600', 'bg' => 'bg-amber-100', 'placeholder' => 'Contoh: Petani'],
                            'religions' => ['label' => 'Agama', 'icon' => 'fa-pray', 'color' => 'text-cyan-600', 'bg' => 'bg-cyan-100', 'placeholder' => 'Contoh: Islam'],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($demographics as $field => $config)
                            <div x-data="{
                                items: {{ json_encode($population->$field ?? [['label' => '', 'count' => 0]]) }},
                                addItem() { this.items.push({label: '', count: 0}) },
                                removeItem(index) { this.items.splice(index, 1) }
                            }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                                
                                <!-- Card Header -->
                                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg {{ $config['bg'] }} flex items-center justify-center {{ $config['color'] }}">
                                            <i class="fa-solid {{ $config['icon'] }}"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-800">{{ $config['label'] }}</h4>
                                    </div>
                                    <button type="button" @click="addItem()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                                        <i class="fa-solid fa-plus text-xs"></i> Tambah
                                    </button>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6 flex-1">
                                    <div class="space-y-3">
                                        <template x-for="(item, index) in items" :key="index">
                                            <div class="group flex items-center gap-3 p-2 rounded-xl border border-transparent hover:border-gray-200 hover:bg-gray-50 transition">
                                                <div class="flex-1">
                                                    <input type="text" 
                                                           :name="'{{ $field }}[' + index + '][label]'" 
                                                           x-model="item.label"
                                                           class="block w-full border-0 bg-transparent p-0 text-gray-900 placeholder-gray-400 focus:ring-0 sm:text-sm font-medium" 
                                                           placeholder="{{ $config['placeholder'] }}"
                                                           required>
                                                </div>
                                                <div class="w-px h-6 bg-gray-200"></div>
                                                <div class="w-24">
                                                    <input type="number" 
                                                           :name="'{{ $field }}[' + index + '][count]'" 
                                                           x-model="item.count"
                                                           class="block w-full border-0 bg-transparent p-0 text-right text-gray-900 placeholder-gray-400 focus:ring-0 sm:text-sm font-bold" 
                                                           placeholder="0"
                                                           min="0"
                                                           required>
                                                </div>
                                                <button type="button" @click="removeItem(index)" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition opacity-0 group-hover:opacity-100">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <p x-show="items.length === 0" class="text-sm text-gray-400 text-center py-8 italic">
                                        Belum ada data {{ strtolower($config['label']) }}.
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Floating Action Bar -->
                <div class="fixed bottom-6 right-6 z-50">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 hover:shadow-blue-600/30 hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
