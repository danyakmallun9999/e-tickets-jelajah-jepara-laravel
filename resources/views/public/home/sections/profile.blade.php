    <!-- SECTION: Profile -->
    <div class="w-full bg-white dark:bg-gray-950 py-16 md:py-24 lg:py-32 relative overflow-hidden" id="profile">
        
        <!-- Background Elements for depth -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-blue-50/20 to-transparent dark:from-blue-900/5 pointer-events-none"></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16 lg:gap-32 items-start">
                
                <!-- Left Column: Pure Content -->
                <div class="profile-content order-2 lg:order-1 pt-0 md:pt-8 opacity-0 -translate-x-12">
                    
                    <div class="space-y-6 md:space-y-7">
                        
                        <div class="space-y-1 md:space-y-2">
                            <!-- Minimal Label -->
                            <span class="block text-[10px] md:text-xs font-bold uppercase tracking-[0.25em] text-blue-600 dark:text-blue-400">
                                {{ $profileSetting->getTranslatedAttribute('label') }}
                            </span>
                            
                            <!-- Typography -->
                            <h2 class="text-3xl md:text-5xl lg:text-6xl font-poppins font-bold leading-[1.2] md:leading-[1.1] text-gray-900 dark:text-white max-w-xl whitespace-pre-line">
                                {{ $profileSetting->getTranslatedAttribute('title') }}
                            </h2>
                        </div>

                        <p class="text-base md:text-lg text-gray-600 dark:text-gray-400 leading-relaxed font-light max-w-md whitespace-pre-line opacity-80">
                            {{ $profileSetting->getTranslatedAttribute('description') }}
                        </p>

                        <!-- Key Highlights (Pillars) -->
                        <div class="pt-5 md:pt-6 mt-5 md:mt-6 border-t border-gray-100 dark:border-gray-800">
                            <div class="grid grid-cols-3 gap-4 md:gap-6">
                                <!-- Pillar: Nature -->
                                <div class="group/pillar cursor-default">
                                    <span class="block text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 md:mb-2 group-hover/pillar:text-emerald-500 transition-colors">
                                        {{ $profileSetting->getTranslatedAttribute('pillar_nature_title') }}
                                    </span>
                                    <p class="font-serif text-sm md:text-lg text-gray-900 dark:text-white leading-tight group-hover/pillar:translate-x-1 transition-transform duration-300 max-w-[120px] whitespace-pre-line">
                                        {{ $profileSetting->getTranslatedAttribute('pillar_nature_desc') }}
                                    </p>
                                </div>
                                <!-- Pillar: Heritage -->
                                <div class="group/pillar cursor-default">
                                    <span class="block text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 md:mb-2 group-hover/pillar:text-amber-500 transition-colors">
                                        {{ $profileSetting->getTranslatedAttribute('pillar_heritage_title') }}
                                    </span>
                                    <p class="font-serif text-sm md:text-lg text-gray-900 dark:text-white leading-tight group-hover/pillar:translate-x-1 transition-transform duration-300 max-w-[120px] whitespace-pre-line">
                                        {{ $profileSetting->getTranslatedAttribute('pillar_heritage_desc') }}
                                    </p>
                                </div>
                                <!-- Pillar: Arts -->
                                <div class="group/pillar cursor-default">
                                    <span class="block text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 md:mb-2 group-hover/pillar:text-purple-500 transition-colors">
                                        {{ $profileSetting->getTranslatedAttribute('pillar_arts_title') }}
                                    </span>
                                    <p class="font-serif text-sm md:text-lg text-gray-900 dark:text-white leading-tight group-hover/pillar:translate-x-1 transition-transform duration-300 max-w-[120px] whitespace-pre-line">
                                        {{ $profileSetting->getTranslatedAttribute('pillar_arts_desc') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Clean Visuals -->
                <div class="relative order-1 lg:order-2 group/visuals" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    
                    <!-- Main Image (Clean Crop) -->
                    <div class="profile-image relative w-full aspect-[4/3] md:aspect-[3/4] overflow-hidden bg-gray-100 dark:bg-gray-900 rounded-[2.5rem] opacity-0 translate-x-12 shadow-2xl">
                        <img src="{{ $profileSetting->image_main ? Storage::url($profileSetting->image_main) : asset('images/profile/section-2.jpg') }}" 
                             alt="Landscape jepara" 
                             class="w-full h-full object-cover transition-all duration-1000 ease-out"
                             :class="hover ? 'scale-105 brightness-110' : ''">
                        
                        <!-- Subtle Inner Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-700"
                             :class="hover ? 'opacity-100' : ''"></div>
                    </div>

                    <!-- Secondary Image (Smaller, Clean Overlay) -->
                    <div class="absolute bottom-6 -left-6 md:bottom-8 md:-left-12 w-32 md:w-48 lg:w-60 aspect-square overflow-hidden rounded-[1.5rem] shadow-2xl border-4 md:border-8 border-white dark:border-gray-950 transition-all duration-700 ease-out hidden sm:block delay-75"
                         :class="hover ? 'translate-x-6 -translate-y-6 scale-105' : ''">
                        <img src="{{ $profileSetting->image_secondary ? Storage::url($profileSetting->image_secondary) : asset('images/profile/diving-karimunjawa.jpg') }}" 
                             alt="Detail" 
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Glassmorphism Stats -->
                    <div class="absolute top-8 -right-4 md:-right-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-gray-700/30 hidden lg:block transition-all duration-700 ease-out delay-150"
                         :class="hover ? '-translate-x-4 translate-y-4 scale-110' : ''">
                        <div class="flex flex-col items-center text-center">
                            <span class="block text-4xl md:text-5xl font-serif text-gray-900 dark:text-white mb-1">
                                {{ $profileSetting->stat_count ?? $countDestinasi }}+
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">
                                {{ $profileSetting->getTranslatedAttribute('stat_label') ?? __('Nav.Destinations') }}
                            </span>
                        </div>
                    </div>

                    <!-- Decorative Floating Shape -->
                    <div class="absolute -z-10 -top-12 -right-12 w-48 h-48 bg-blue-100/50 dark:bg-blue-900/10 rounded-full blur-3xl transition-opacity duration-1000"
                         :class="hover ? 'opacity-100' : 'opacity-0'"></div>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        (function() {
            const initProfile = () => {
                // Ensure GSAP and ScrollTrigger are available
                if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

                // Refresh ScrollTrigger to ensure correct positions
                ScrollTrigger.refresh();

                // Left Column (Content) Slide In
                gsap.to(".profile-content", {
                    scrollTrigger: {
                        trigger: ".profile-content",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    x: 0,
                    opacity: 1,
                    duration: 1.2,
                    ease: "power3.out"
                });

                // Right Column (Image) Slide In
                gsap.to(".profile-image", {
                    scrollTrigger: {
                        trigger: ".profile-image",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    x: 0,
                    opacity: 1,
                    duration: 1.2,
                    delay: 0.1,
                    ease: "power3.out"
                });
            };

            // Run immediately on load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initProfile);
            } else {
                initProfile();
            }

            // Run on Livewire navigation
            document.addEventListener('livewire:navigated', initProfile);
        })();
    </script>
    <!-- END SECTION: Profile -->
