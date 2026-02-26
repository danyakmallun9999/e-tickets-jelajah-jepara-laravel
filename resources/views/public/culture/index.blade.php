<x-public-layout>
    @push('styles')
    {{-- Fonts: Noto Serif & Noto Sans now bundled via @fontsource in app.css --}}
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
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ __('Nav.Culture') }}</span>
            </nav>

            <!-- Hero Title Section -->
            <section class="mb-12">
                <div class="max-w-3xl">
                    <h1 class="text-2xl lg:text-4xl font-black mb-6 leading-[1.1] tracking-tight text-slate-900 dark:text-slate-100 font-display">
                        {{ __('Culture.Title') }} <span class="text-primary">{{ __('Nav.Culture') }}</span>
                    </h1>
                    <p class="text-sm lg:text-lg text-slate-600 dark:text-slate-400 leading-relaxed font-sans">
                        {{ __('Culture.Subtitle') }}
                    </p>
                </div>
            </section>

            <div class="category-grid">
                
                <!-- Interactive Category Grid -->
                <section class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-8 mb-16">
                    @foreach($categoriesForAlpine as $category)
                        <a href="{{ route('culture.category', Str::slug($category['title'])) }}" 
                           class="culture-card w-full block relative group overflow-hidden rounded-xl bg-slate-200 cursor-pointer aspect-video md:aspect-[4/3]"
                           wire:navigate>
                            
                            <div class="card-bg absolute inset-0 bg-cover bg-center" style="{{ $category['image'] ? "background-image: url('{$category['image']}');" : 'background-color: #cbd5e1;' }}">
                                @if(!$category['image'])
                                <div class="w-full h-full flex items-center justify-center opacity-30">
                                    <span class="material-symbols-outlined text-6xl">collections</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                            <div class="absolute inset-0 p-4 md:p-6 lg:p-8 flex flex-col justify-end">
                                <h3 class="text-white text-lg md:text-2xl lg:text-3xl font-bold mb-1 md:mb-2 lg:mb-3 leading-tight">{{ $category['title'] }}</h3>
                                <div class="flex items-center text-primary font-bold gap-2 text-[10px] md:text-xs lg:text-sm uppercase tracking-wider group/link">
                                    <span>Explore Gallery</span>
                                    <span class="material-symbols-outlined transition-transform group-hover/link:translate-x-2 text-base md:text-xl lg:text-2xl">arrow_forward</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </section>

            </div>


        </div>
    </div>
</x-public-layout>
