<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Admin Panel</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Data Kependudukan
                </h2>
            </div>
            <a href="{{ route('admin.population.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fa-solid fa-edit mr-2"></i> Edit Data
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-check-circle"></i>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 rounded-lg text-green-600">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Penduduk</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($population->total_population ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                            <i class="fa-solid fa-house-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kepala Keluarga</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($population->total_families ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
                            <i class="fa-solid fa-male text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Laki-laki</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($population->total_male ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-pink-100 rounded-lg text-pink-600">
                            <i class="fa-solid fa-female text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Perempuan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($population->total_female ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Age Groups -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">Kelompok Usia</h3>
                    <div class="relative h-64">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>

                <!-- Education -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">Tingkat Pendidikan</h3>
                    <div class="relative h-64">
                        <canvas id="educationChart"></canvas>
                    </div>
                </div>

                <!-- Jobs -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">Pekerjaan</h3>
                    <div class="relative h-64">
                        <canvas id="jobChart"></canvas>
                    </div>
                </div>

                <!-- Religions -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">Agama</h3>
                    <div class="relative h-64">
                        <canvas id="religionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Helper to parse data
            const parseData = (data) => {
                if (!data) return { labels: [], values: [] };
                return {
                    labels: data.map(item => item.label),
                    values: data.map(item => item.count)
                };
            };

            const ageData = parseData(@json($population->age_groups ?? []));
            const eduData = parseData(@json($population->education_levels ?? []));
            const jobData = parseData(@json($population->jobs ?? []));
            const religionData = parseData(@json($population->religions ?? []));

            // Chart Colors
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6', '#f97316'];

            // Age Chart (Bar)
            if (ageData.labels.length > 0) {
                new Chart(document.getElementById('ageChart'), {
                    type: 'bar',
                    data: {
                        labels: ageData.labels,
                        datasets: [{
                            label: 'Jumlah',
                            data: ageData.values,
                            backgroundColor: '#10b981',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // Education Chart (Pie)
            if (eduData.labels.length > 0) {
                new Chart(document.getElementById('educationChart'), {
                    type: 'doughnut',
                    data: {
                        labels: eduData.labels,
                        datasets: [{
                            data: eduData.values,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            // Job Chart (Bar Horizontal)
            if (jobData.labels.length > 0) {
                new Chart(document.getElementById('jobChart'), {
                    type: 'bar',
                    data: {
                        labels: jobData.labels,
                        datasets: [{
                            label: 'Jumlah',
                            data: jobData.values,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true } }
                    }
                });
            }

            // Religion Chart (Pie)
            if (religionData.labels.length > 0) {
                new Chart(document.getElementById('religionChart'), {
                    type: 'pie',
                    data: {
                        labels: religionData.labels,
                        datasets: [{
                            data: religionData.values,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        });
    </script>
</x-app-layout>
