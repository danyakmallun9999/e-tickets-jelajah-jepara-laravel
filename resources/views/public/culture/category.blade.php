<x-public-layout>
    @push('styles')
    <style>
        .culture-card:hover .card-description {
            opacity: 1;
            transform: translateY(0);
        }
        .culture-card:hover .card-bg {
            transform: scale(1.05);
        }
        .culture-card .card-description {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .culture-card .card-bg {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    @endpush

    <div class="bg-background-light dark:bg-background-dark min-h-screen -mt-20 pt-32 pb-24 font-display">
        <div class="max-w-7xl mx-auto px-6 lg:px-20">
            
            <!-- Breadcrumb -->
            <nav class="flex text-xs md:text-sm text-gray-400 mb-6 space-x-2 font-sans">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors" wire:navigate>{{ __('Nav.Home') }}</a>
                <span>/</span>
                <a href="{{ route('culture.index') }}" class="hover:text-primary transition-colors" wire:navigate>{{ __('Nav.Culture') }}</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $matchedCategory['title'] }}</span>
            </nav>

            <button onclick="history.back()" class="mb-8 flex items-center gap-2 text-slate-500 hover:text-primary transition-colors font-sans font-bold uppercase tracking-wider text-sm">
                <span class="material-symbols-outlined">arrow_back</span> Back to Collections
            </button>

            <!-- Hero Title Section -->
            <section class="mb-12">
                <div class="max-w-3xl">
                    <h1 class="text-2xl lg:text-4xl font-black mb-6 leading-[1.1] tracking-tight text-slate-900 dark:text-slate-100 font-display">
                        {{ $matchedCategory['title'] }}
                    </h1>
                    <p class="text-sm lg:text-lg text-slate-600 dark:text-slate-400 leading-relaxed font-sans">
                        {{ $matchedCategory['description'] }}
                    </p>
                </div>
            </section>

            <!-- Detail Grid -->
            <section class="min-h-[600px] mb-16">
                <div class="space-y-16">
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 lg:gap-6">
                        @forelse($cultures as $culture)
                            <a href="{{ route(isset($culture->price) || $culture->category === 'Kuliner Khas' ? 'culinary.show' : 'culture.show', $culture->slug) }}" 
                                 wire:navigate
                                 class="culture-card w-full block relative group overflow-hidden rounded-xl bg-slate-200 dark:bg-slate-800 cursor-pointer aspect-[3/4] md:aspect-[4/5]">
                                
                                <!-- Background Image -->
                                @if($culture->image)
                                    <div class="card-bg absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $culture->image_url }}');"></div>
                                @else
                                    <div class="card-bg absolute inset-0 bg-slate-300 dark:bg-slate-700 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-6xl text-slate-400">theater_comedy</span>
                                    </div>
                                @endif

                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                                <!-- Content -->
                                <div class="absolute inset-0 p-8 flex flex-col justify-end">
                                    <h3 class="text-white text-3xl font-bold mb-3 leading-tight font-display">{{ $culture->name }}</h3>
                                    
                                    <p class="card-description text-slate-200 text-base leading-relaxed font-sans mb-6 line-clamp-3">
                                        {{Str::limit($culture->description, 100)}}
                                    </p>
                                    
                                    <div class="flex items-center text-primary font-bold gap-2 text-sm uppercase tracking-wider group/link font-sans mt-auto">
                                        <span>{{ __('Culture.Button.More') }}</span>
                                        <span class="material-symbols-outlined transition-transform group-hover/link:translate-x-2 text-lg">arrow_forward</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-3 block">inventory_2</span>
                                <p>Belum ada data budaya untuk kategori ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination Controls -->
                    @if($cultures->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $cultures->links() }}
                    </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-public-layout>
