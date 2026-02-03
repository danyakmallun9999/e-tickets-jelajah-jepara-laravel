<footer class="relative bg-slate-950 text-white pt-16 md:pt-32 pb-12 md:pb-16 overflow-hidden">
    
    <!-- Sophisticated Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <!-- Single Clean Gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900"></div>
        
        <!-- Subtle Ambient Light (Single Orb) -->
        <div class="absolute bottom-0 right-0 w-[60%] h-[60%] bg-blue-500/5 rounded-full blur-3xl pointer-events-none transform translate-x-1/3 translate-y-1/3"></div>
        
        <!-- Top Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-700/50 to-transparent"></div>
    </div>

    <!-- Content Container -->
    <div class="relative w-full mx-auto max-w-7xl px-6 md:px-8 z-20">
        
        <!-- Header Section -->
        <div class="mb-16 md:mb-20 pb-12 md:pb-16 border-b border-slate-800/50">
            <!-- Branding -->
            <div class="mb-8 md:mb-12">
                <h2 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight leading-tight text-white mb-3">
                    Pemerintah<br>Kabupaten <span class="text-blue-400">Jepara</span>
                </h2>
                <p class="text-slate-400 text-sm md:text-base font-medium">
                    {{ __('Footer.Department') }}
                </p>
            </div>

            <!-- Description -->
            <p class="text-slate-400 text-sm md:text-base leading-relaxed max-w-2xl font-normal">
                {{ __('Footer.About') }}
            </p>
        </div>

        <!-- Main Grid (3 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-16 mb-16 md:mb-24">
            
            <!-- Column 1: Navigation (Explore) -->
            <div>
                <h3 class="text-white text-sm font-bold tracking-wider uppercase mb-6 md:mb-8">
                    {{ __('Footer.Section.Explore') }}
                </h3>
                <ul class="space-y-3.5 md:space-y-4">
                    <li>
                        <a href="{{ route('welcome') }}" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            {{ __('Nav.Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('places.index') }}" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            {{ __('Nav.Destinations') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('explore.map') }}" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            {{ __('Nav.Map') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.public.index') }}" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            {{ __('Nav.Events') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('posts.index') }}" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            {{ __('Nav.News') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 2: Categories -->
            <div>
                <h3 class="text-white text-sm font-bold tracking-wider uppercase mb-6 md:mb-8">
                    {{ __('Footer.Section.Categories') }}
                </h3>
                <ul class="space-y-3.5 md:space-y-4">
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            Wisata Alam
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            Wisata Budaya
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            Wisata Kuliner
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white text-sm md:text-base font-medium transition-colors duration-300 inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-blue-400/50 group-hover:bg-blue-400 transition-colors"></span>
                            Wisata Religi
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Contact -->
            <div>
                <h3 class="text-white text-sm font-bold tracking-wider uppercase mb-6 md:mb-8">
                    {{ __('Footer.Section.Contact') }}
                </h3>
                <ul class="space-y-4 md:space-y-5">
                    <!-- Address -->
                    <li class="flex gap-3 md:gap-4 group">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-lg bg-blue-400/10 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-400/20 transition-colors">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>
                        <p class="text-slate-400 group-hover:text-slate-300 text-xs md:text-sm leading-relaxed transition-colors">
                            Jl. Abdul Rahman Hakim. No. 51, Kauman, Kec. Jepara, Kabupaten Jepara, Jawa Tengah 59417
                        </p>
                    </li>

                    <!-- Phone -->
                    <li class="flex gap-3 md:gap-4 group">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-lg bg-blue-400/10 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-400/20 transition-colors">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773c.345.611.895 1.165 1.599 1.659l1.325-1.326a1 1 0 011.414 0l3.536 3.536a1 1 0 010 1.414l-1.326 1.325c.494.704 1.048 1.254 1.659 1.599l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2.57C6.75 18 3 14.25 3 9.43V5a1 1 0 011-1z"/>
                            </svg>
                        </div>
                        <a href="tel:(0291) 591219" class="text-slate-400 hover:text-slate-300 text-xs md:text-sm font-medium transition-colors">
                            (0291) 591219
                        </a>
                    </li>

                    <!-- Email -->
                    <li class="flex gap-3 md:gap-4 group">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-lg bg-blue-400/10 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-400/20 transition-colors">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <a href="mailto:disparbud@jepara.go.id" class="text-slate-400 hover:text-slate-300 text-xs md:text-sm font-medium transition-colors break-all">
                            disparbud@jepara.go.id
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Social Links -->
        <div class="mb-16 md:mb-20 pb-12 md:pb-16 border-b border-slate-800/50">
            <h3 class="text-white text-sm font-bold tracking-wider uppercase mb-6">
                {{ __('Footer.Social') ?? 'Ikuti Kami' }}
            </h3>
            <div class="flex items-center gap-3 md:gap-4">
                <a href="#" aria-label="Facebook" class="w-10 h-10 md:w-11 md:h-11 rounded-lg bg-slate-800/50 hover:bg-blue-500/20 flex items-center justify-center text-slate-400 hover:text-blue-400 transition-all duration-300 group border border-slate-700/50 hover:border-blue-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Instagram" class="w-10 h-10 md:w-11 md:h-11 rounded-lg bg-slate-800/50 hover:bg-blue-500/20 flex items-center justify-center text-slate-400 hover:text-blue-400 transition-all duration-300 group border border-slate-700/50 hover:border-blue-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.467.398.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/>
                    </svg>
                </a>
                <a href="#" aria-label="YouTube" class="w-10 h-10 md:w-11 md:h-11 rounded-lg bg-slate-800/50 hover:bg-blue-500/20 flex items-center justify-center text-slate-400 hover:text-blue-400 transition-all duration-300 group border border-slate-700/50 hover:border-blue-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Twitter" class="w-10 h-10 md:w-11 md:h-11 rounded-lg bg-slate-800/50 hover:bg-blue-500/20 flex items-center justify-center text-slate-400 hover:text-blue-400 transition-all duration-300 group border border-slate-700/50 hover:border-blue-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7s2.44 2.78 5-3.72a4.5 4.5 0 01-6-4z"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Bottom Section: Stamps & Copyright -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 md:gap-12">
            <!-- Stamps & Partners -->
            <div class="flex items-center gap-8 md:gap-12">
                <img src="{{ asset('images/footer/wndrfl-indonesia2.png') }}" alt="Wonderful Indonesia" class="h-8 md:h-10 w-auto object-contain opacity-50 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('images/footer/logo-jpr-psn.png') }}" alt="Jepara Mempesona" class="h-10 md:h-12 w-auto object-contain opacity-50 hover:opacity-100 transition-opacity duration-300">
            </div>

            <!-- Copyright -->
            <div>
                <p class="text-xs md:text-sm text-slate-500 font-medium">
                    &copy; 2024 <span class="text-slate-300">{{ __('Footer.Department') }}</span>.<br class="md:hidden"> 
                    <span class="text-slate-600">{{ __('Footer.Rights') }}</span>
                </p>
            </div>
        </div>

    </div>

</footer>