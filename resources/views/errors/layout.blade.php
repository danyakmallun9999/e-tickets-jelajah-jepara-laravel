<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title') - Jelajah Jepara</title>
    <link rel="icon" href="{{ asset('images/logo-kura.png') }}" type="image/png">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        
        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Blob Animation */
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }
        .animate-blob { animation: blob 10s ease-in-out infinite; }
        .animate-blob-slow { animation: blob 15s ease-in-out infinite; }

        /* Error Code with Logo */
        .error-code-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.1em;
        }

        .error-code-digit {
            font-family: 'Press Start 2P', cursive;
            font-size: clamp(4rem, 20vw, 8rem);
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            text-shadow: 4px 4px 0px rgba(59, 130, 246, 0.1);
        }

        .error-code-logo {
            width: clamp(5rem, 22vw, 10rem);
            height: clamp(5rem, 22vw, 10rem);
            object-fit: contain;
            filter: drop-shadow(0 8px 20px rgba(59, 130, 246, 0.25));
            margin: 0 0.1em;
        }

        /* Button Hover Effect */
        .btn-primary {
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        /* Mobile Optimizations */
        @media (max-width: 640px) {
            .error-code-wrapper {
                gap: 0.05em;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 font-display antialiased min-h-screen flex flex-col" x-data="{ loaded: false }" x-init="loaded = true">

    <!-- Navbar -->
    <div class="flex-shrink-0 w-full bg-white/90 backdrop-blur-md border-b border-slate-200/50 z-50">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <header class="flex items-center justify-between gap-8 h-16 md:h-20">
                
                <!-- Logo Area -->
                <a class="flex items-center gap-2 md:gap-3 group" href="{{ url('/') }}">
                    <div class="relative w-12 h-12 md:w-20 md:h-20 transition-transform duration-300 group-hover:scale-110">
                        <img src="{{ asset('images/logo-kura.png') }}" alt="Logo Kabupaten Jepara" class="w-full h-full object-contain filter">
                    </div>
                    <div>
                        <h2 class="text-lg md:text-2xl font-bold leading-none tracking-tight text-slate-600 group-hover:text-primary transition-colors font-['Caveat']">
                            Jelajah Jepara
                        </h2>
                    </div>
                </a>

                <!-- Nav Button -->
                <a href="{{ url('/') }}" class="px-4 py-2 md:px-5 md:py-2.5 bg-primary text-white text-xs md:text-sm font-bold rounded-full hover:bg-primary/90 transition-colors shadow-lg shadow-primary/20">
                    <i class="fa-solid fa-house text-xs mr-1 md:mr-1.5"></i>
                    <span class="hidden sm:inline">Beranda</span>
                    <span class="sm:hidden">Home</span>
                </a>
            </header>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-8 relative overflow-hidden">
        
        <!-- Decorative Blobs -->
        <div class="absolute top-10 -left-20 w-40 md:w-72 h-40 md:h-72 bg-blue-400/15 rounded-full blur-3xl animate-blob" id="blob-1"></div>
        <div class="absolute bottom-10 -right-20 w-52 md:w-96 h-52 md:h-96 bg-cyan-400/15 rounded-full blur-3xl animate-blob-slow" id="blob-2"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[250px] md:w-[400px] h-[250px] md:h-[400px] bg-gradient-to-br from-blue-100/40 to-cyan-100/40 rounded-full blur-3xl" id="blob-3"></div>

        <!-- Error Content -->
        <div class="relative z-10 max-w-lg w-full text-center px-2">
            
            <!-- Error Code with Turtle Logo replacing 0 -->
            <div class="error-content mb-6 md:mb-10" id="error-code-section">
                <div class="error-code-wrapper">
                    @yield('code-display')
                </div>
            </div>
            
            <!-- Message -->
            <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-slate-800 mb-3 md:mb-4 px-2" id="error-message">
                @yield('message')
            </h1>
            
            <!-- Description -->
            <p class="text-sm md:text-base text-slate-500 max-w-sm mx-auto leading-relaxed mb-8 md:mb-10 px-2" id="error-description">
                @yield('description')
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center px-2" id="error-actions">
                <a href="{{ url('/') }}" class="btn-primary group inline-flex items-center justify-center gap-2 px-5 py-3 md:px-8 md:py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-bold rounded-xl md:rounded-2xl">
                    <i class="fa-solid fa-house text-sm"></i>
                    Beranda
                </a>
                
                <button onclick="history.back()" class="inline-flex items-center justify-center gap-2 px-5 py-3 md:px-8 md:py-3.5 bg-white text-slate-700 text-sm font-bold rounded-xl md:rounded-2xl border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all duration-300">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    Kembali
                </button>
            </div>

            @yield('actions')
        </div>
    </main>

    <!-- Minimal Footer -->
    <div class="flex-shrink-0 py-3 md:py-4 text-center border-t border-slate-100">
        <p class="text-xs text-slate-400 px-4">
            &copy; {{ date('Y') }} Dinas Pariwisata & Kebudayaan Kabupaten Jepara
        </p>
    </div>

    <!-- GSAP Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial states
            gsap.set('#error-code-section', { opacity: 0, y: -30 });
            gsap.set('#error-message', { opacity: 0, y: 20 });
            gsap.set('#error-description', { opacity: 0, y: 20 });
            gsap.set('#error-actions', { opacity: 0, y: 30 });
            gsap.set('#blob-1, #blob-2, #blob-3', { opacity: 0, scale: 0.5 });

            // Create timeline
            const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

            // Animate blobs first
            tl.to('#blob-1, #blob-2, #blob-3', {
                opacity: 1,
                scale: 1,
                duration: 1,
                stagger: 0.2
            })
            // Then error code with bounce
            .to('#error-code-section', {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'back.out(1.7)'
            }, '-=0.5')
            // Message
            .to('#error-message', {
                opacity: 1,
                y: 0,
                duration: 0.5
            }, '-=0.3')
            // Description
            .to('#error-description', {
                opacity: 1,
                y: 0,
                duration: 0.5
            }, '-=0.3')
            // Actions
            .to('#error-actions', {
                opacity: 1,
                y: 0,
                duration: 0.5
            }, '-=0.2');

            // Turtle logo subtle animation
            gsap.to('.error-code-logo', {
                rotation: 5,
                duration: 3,
                ease: 'sine.inOut',
                yoyo: true,
                repeat: -1
            });

            // Continuous blob movement
            gsap.to('#blob-1', {
                x: 20,
                y: -20,
                duration: 8,
                ease: 'sine.inOut',
                yoyo: true,
                repeat: -1
            });

            gsap.to('#blob-2', {
                x: -30,
                y: 20,
                duration: 10,
                ease: 'sine.inOut',
                yoyo: true,
                repeat: -1
            });
        });
    </script>

</body>
</html>
