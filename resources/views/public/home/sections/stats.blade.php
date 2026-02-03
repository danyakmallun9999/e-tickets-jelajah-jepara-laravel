    <!-- SECTION: Stats -->
    <div class="w-full bg-background-light dark:bg-background-dark py-6 border-b border-surface-light dark:border-surface-dark transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Responsive Grid: 2 cols on mobile/tablet, 4 cols on desktop -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                <!-- Destinasi Wisata -->
                <div class="flex items-center gap-3 md:gap-4 rounded-xl p-4 bg-surface-light/50 dark:bg-white/5 hover:bg-surface-light dark:hover:bg-white/10 transition-colors duration-200 border border-transparent hover:border-primary/20 group">
                    <div class="size-10 md:size-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <span class="material-symbols-outlined text-xl md:text-2xl">photo_camera</span>
                    </div>
                    <div>
                        <!-- Using $countDestinasi passed from controller -->
                        <p class="text-text-light dark:text-text-dark text-xl md:text-2xl font-bold tracking-tight leading-none mb-1">
                            {{ $countDestinasi }}+</p>
                        <p class="text-[10px] md:text-xs text-text-light/60 dark:text-text-dark/60 font-medium uppercase tracking-wider">
                            {{ __('Stats.Destinations') }}</p>

                    </div>
                </div>

                <!-- Kuliner Khas -->
                <div class="flex items-center gap-3 md:gap-4 rounded-xl p-4 bg-surface-light/50 dark:bg-white/5 hover:bg-surface-light dark:hover:bg-white/10 transition-colors duration-200 border border-transparent hover:border-primary/20 group">
                    <div class="size-10 md:size-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <span class="material-symbols-outlined text-xl md:text-2xl">restaurant_menu</span>
                    </div>
                    <div>
                        <p class="text-text-light dark:text-text-dark text-xl md:text-2xl font-bold tracking-tight leading-none mb-1">
                            {{ $countKuliner }}+</p>
                        <p class="text-[10px] md:text-xs text-text-light/60 dark:text-text-dark/60 font-medium uppercase tracking-wider">
                             {{ __('Stats.Culinary') }}</p>
                    </div>
                </div>

                <!-- Agenda Event -->
                <div class="flex items-center gap-3 md:gap-4 rounded-xl p-4 bg-surface-light/50 dark:bg-white/5 hover:bg-surface-light dark:hover:bg-white/10 transition-colors duration-200 border border-transparent hover:border-primary/20 group">
                    <div class="size-10 md:size-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <span class="material-symbols-outlined text-xl md:text-2xl">event_available</span>
                    </div>
                    <div>
                        <p class="text-text-light dark:text-text-dark text-xl md:text-2xl font-bold tracking-tight leading-none mb-1">
                            {{ $countEvent }}</p>
                        <p class="text-[10px] md:text-xs text-text-light/60 dark:text-text-dark/60 font-medium uppercase tracking-wider">
                            {{ __('Nav.Events') }}</p>
                    </div>
                </div>

                <!-- Desa Wisata -->
                <div class="flex items-center gap-3 md:gap-4 rounded-xl p-4 bg-surface-light/50 dark:bg-white/5 hover:bg-surface-light dark:hover:bg-white/10 transition-colors duration-200 border border-transparent hover:border-primary/20 group">
                    <div class="size-10 md:size-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <span class="material-symbols-outlined text-xl md:text-2xl">holiday_village</span>
                    </div>
                    <div>
                        <p class="text-text-light dark:text-text-dark text-xl md:text-2xl font-bold tracking-tight leading-none mb-1">
                            {{ $countDesa }}</p>
                        <p class="text-[10px] md:text-xs text-text-light/60 dark:text-text-dark/60 font-medium uppercase tracking-wider">
                            {{ __('Stats.Villages') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION: Stats -->
