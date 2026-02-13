<x-public-layout>
    @push('seo')
        <x-seo 
            :title="$post->title . ' - Jelajah Jepara'"
            :description="Str::limit(strip_tags($post->content), 150)"
            :image="$post->image_path ? asset($post->image_path) : asset('images/logo-kura.png')"
            type="article"
        />
    @endpush
    <div class="bg-white dark:bg-background-dark min-h-screen -mt-20 pt-32">
        <!-- Main Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            
            <!-- Breadcrumb -->
            <nav class="flex justify-center text-xs md:text-sm text-gray-500 mb-6 space-x-2">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">{{ __('Nav.Home') }}</a>
                <span>/</span>
                <a href="{{ route('posts.index') }}" class="text-gray-400 hover:text-primary transition-colors">{{ __('Nav.News') }}</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 truncate max-w-[200px]">{{ $post->translated_title }}</span>
            </nav>

            <!-- Header Section -->
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white leading-tight mb-6 tracking-tight font-serif">
                    {{ $post->translated_title }}
                </h1>
                
                <div class="flex flex-col md:flex-row items-center justify-center gap-6 border-b border-gray-100 dark:border-gray-800 pb-8 mb-8">
                    <!-- Author Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <img src="{{ asset('images/logo-kura.png') }}" class="w-6 h-6 object-contain" alt="Admin">
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $post->author ?? __('News.Header.Department') }}</p>
                            <p class="text-xs text-gray-500">
                                Published {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }} • {{ ceil(str_word_count(strip_tags($post->translated_content)) / 200) }} {{ __('News.ReadTime') }}
                            </p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden md:block w-px h-8 bg-gray-200 dark:bg-gray-700"></div>

                    <!-- Share Buttons -->
                    <!-- Share Buttons -->
                    <!-- Share Buttons -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500 mr-2 hidden md:inline">Share:</span>
                        <x-share-modal :url="route('posts.show', $post)" :title="$post->translated_title" :text="Str::limit(strip_tags($post->translated_content), 100)">
                            <button class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Bagikan artikel ini">
                                <i class="fa-solid fa-share-nodes"></i>
                            </button>
                        </x-share-modal>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="relative w-full aspect-video md:aspect-[21/9] rounded-2xl overflow-hidden mb-12 shadow-2xl">
                <img src="{{ asset($post->image_path) }}" alt="{{ $post->translated_title }}" class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
                <!-- Image Credit -->
                @if($post->image_credit)
                <div class="absolute bottom-4 right-4 px-3 py-1 bg-black/50 backdrop-blur-sm rounded-full text-[10px] text-white/80">
                    Photo: {{ $post->image_credit }}
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Main Content (Left) -->
                <div class="lg:col-span-8">
                    <!-- Intro Blockquote -->


                    <!-- Article Body -->
                    <article class="tinymce-content text-gray-700 dark:text-gray-300">
                        {!! $post->translated_content !!}
                    </article>

                    <!-- Tags -->
                    <div class="mt-12 flex flex-wrap gap-2">
                        <span class="text-sm font-bold text-gray-400 mr-2">Tags:</span>
                        <a href="#" class="px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-full hover:bg-primary hover:text-white transition-colors">#Jepara</a>
                        <a href="#" class="px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-full hover:bg-primary hover:text-white transition-colors">#Pariwisata</a>
                        <a href="#" class="px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-full hover:bg-primary hover:text-white transition-colors">#{{ $post->type }}</a>
                    </div>
                    <!-- Statistics Section -->
                    <div class="mt-16 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">analytics</span>
                                Statistik Pembaca
                            </h3>
                            <span class="text-xs text-gray-500 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-full">
                                Real-time Data
                            </span>
                        </div>
                        
                        <!-- Post Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <!-- Total Views -->
                            <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-900/30 transition-all hover:shadow-md">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </div>
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Views</p>
                                </div>
                                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($stats['total_views']) }}</p>
                            </div>
                            
                            <!-- Views Today -->
                            <div class="p-6 bg-purple-50 dark:bg-purple-900/20 rounded-2xl border border-purple-100 dark:border-purple-900/30 transition-all hover:shadow-md">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-800/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                        <i class="fa-solid fa-chart-line text-xs"></i>
                                    </div>
                                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Hari Ini</p>
                                </div>
                                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($stats['views_today']) }}</p>
                            </div>
                            
                            <!-- Unique Visitors -->
                            <div class="p-6 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 transition-all hover:shadow-md">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                        <i class="fa-solid fa-users text-xs"></i>
                                    </div>
                                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">Unique Visitors</p>
                                </div>
                                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($stats['unique_visitors']) }}</p>
                            </div>
                        </div>

                        <!-- Article Views Chart -->
                        <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm mb-16">
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-6">Grafik Kunjungan (30 Hari Terakhir)</h4>
                            <div class="relative h-64 w-full">
                                <canvas id="viewsChart"></canvas>
                            </div>
                        </div>

                        <!-- Tourism Stats Section -->
                        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 p-8 md:p-12 text-white shadow-xl">
                            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-primary/20 blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl"></div>
                            
                            <div class="relative z-10">
                                <h3 class="text-2xl md:text-3xl font-bold mb-2 flex items-center gap-3">
                                    <span class="material-symbols-outlined text-yellow-400">tour</span>
                                    Wisata Jepara {{ $tourismStats['year'] }}
                                </h3>
                                <p class="text-gray-400 mb-8 max-w-xl">Data statistik kunjungan wisatawan ke Kabupaten Jepara tahun ini. Mari dukung pariwisata lokal!</p>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                                    <!-- Total Visitors Card -->
                                    <div class="lg:col-span-1 bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center">
                                        <p class="text-sm text-gray-300 font-medium mb-2 uppercase tracking-widest">Total Wisatawan</p>
                                        <p class="text-5xl font-black text-yellow-400 tracking-tight my-4">{{ number_format($tourismStats['total_visitors']) }}</p>
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-xs font-bold">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                            <span>Year to Date</span>
                                        </div>
                                    </div>

                                    <!-- Monthly Chart -->
                                    <div class="lg:col-span-2 bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/5">
                                        <div class="relative h-64 w-full">
                                            <canvas id="tourismChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration for charts
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.scale.grid.color = 'rgba(148, 163, 184, 0.1)';

            // 1. Article Views Chart (Line)
            const viewsCtx = document.getElementById('viewsChart').getContext('2d');
            const viewsData = @json($viewsGraph);
            
            new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels: viewsData.map(d => {
                        const date = new Date(d.date);
                        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                    }),
                    datasets: [{
                        label: 'Pembaca',
                        data: viewsData.map(d => d.count),
                        borderColor: '#0ea5e9', // Blue-500
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.2)');
                            gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0ea5e9',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#f8fafc',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // 2. Tourism Stats Chart (Bar)
            const tourismCtx = document.getElementById('tourismChart').getContext('2d');
            const tourismData = @json($tourismStats['monthly_data']);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            // Fill missing months with 0
            const monthlyVisitors = new Array(12).fill(0);
            tourismData.forEach(d => {
                monthlyVisitors[d.month - 1] = d.visitors;
            });

            new Chart(tourismCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Wisatawan',
                        data: monthlyVisitors,
                        backgroundColor: '#facc15', // Yellow-400
                        borderRadius: 4,
                        hoverBackgroundColor: '#fbbf24',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000000',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('id-ID').format(context.raw) + ' Wisatawan';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            ticks: { color: '#9ca3af' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af' }
                        }
                    }
                }
            });
        });
    </script>
    @endpush

                <!-- Sidebar (Right) -->
                <div class="lg:col-span-4 space-y-12">
                    
                    <!-- Related News Widget -->
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">feed</span>
                            {{ __('News.RelatedTitle') }}
                        </h3>
                        <div class="space-y-6">
                            @foreach($relatedPosts as $related)
                            <a href="{{ route('posts.show', $related) }}" class="group flex gap-4 items-start">
                                <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                    <img src="{{ asset($related->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider block mb-1">
                                        {{ $related->published_at ? $related->published_at->format('M d, Y') : '' }}
                                    </span>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-primary transition-colors line-clamp-2">
                                        {{ $related->translated_title }}
                                    </h4>
                                </div>
                            </a>
                            @endforeach
                            @if($relatedPosts->isEmpty())
                                <p class="text-sm text-gray-500 italic">{{ __('News.NoRelated') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Must Visit Widget -->
                    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl p-6 border border-blue-100 dark:border-blue-900/20">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500">explore</span>
                            {{ __('News.MustVisitTitle') }}
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($recommendedPlaces as $place)
                            @if(!$place->slug) @continue @endif
                            <div class="relative group rounded-xl overflow-hidden aspect-[16/9] shadow-md">
                                <img src="{{ asset($place->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h4 class="text-white font-bold text-lg leading-tight mb-0.5">{{ $place->name }}</h4>
                                    <p class="text-white/80 text-xs">{{ $place->category?->name }}</p>
                                </div>
                                <a href="{{ route('places.show', $place) }}" class="absolute inset-0 z-10"></a>
                            </div>
                            @endforeach
                        </div>
                        
                        <a href="{{ route('explore.map') }}" class="mt-6 block w-full py-3 text-center text-sm font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-100/50 rounded-xl transition-colors">
                            {{ __('News.ViewFullMap') }}
                        </a>
                    </div>

                    <!-- CTA Section -->
                    <div class="rounded-2xl overflow-hidden relative aspect-square bg-gray-900 flex items-center justify-center text-center p-6 group">
                         <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=600&q=80" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700">
                         <div class="relative z-10">
                             <h4 class="text-white font-serif text-2xl font-bold mb-2">{{ __('News.CTA.Title') }}</h4>
                             <p class="text-white/80 text-sm mb-4">{{ __('News.CTA.Subtitle') }}</p>
                             <a href="{{ route('places.index') }}" class="inline-block px-6 py-2 bg-white text-gray-900 text-xs font-bold uppercase tracking-wider rounded-full hover:bg-primary hover:text-white transition-colors">
                                 {{ __('News.CTA.Button') }}
                             </a>
                         </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
