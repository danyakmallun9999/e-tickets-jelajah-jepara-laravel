# Backup Section Kebudayaan (Culture)

Ini adalah backup dari section "Kebudayaan" di halaman `welcome.blade.php`.
Backup ini mencakup dua bagian utama yang berkaitan dengan kebudayaan:

1. **Sejarah & Legenda (History & Legend)** - Menampilkan tokoh R.A. Kartini dan Ratu Kalinyamat.
2. **Destinasi Kebudayaan** - Menampilkan daftar kebudayaan dalam bentuk kartu akordeon.

## 1. Section Sejarah & Legenda (History & Legend)

Bagian ini menggunakan font `Pinyon Script` dan `Playfair Display`.

```html
<!-- History & Legend Section (Light & Clean Version) -->
<div
    class="relative w-full bg-white dark:bg-gray-900 overflow-hidden py-24"
    id="history"
>
    <!-- Background Decor -->
    <div
        class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent"
    ></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div
            class="text-center mb-20"
            x-data="{ shown: false }"
            x-intersect.threshold.0.5="shown = true"
        >
            <span
                class="block text-primary font-bold tracking-[0.3em] uppercase text-xs mb-4 opacity-0 translate-y-4 transition-all duration-700"
                :class="shown ? 'opacity-100 translate-y-0' : ''"
            >
                Warisan Leluhur
            </span>
            <h2
                class="text-5xl md:text-6xl lg:text-7xl font-['Pinyon_Script'] text-gray-900 dark:text-gray-100 mb-6 opacity-0 translate-y-4 transition-all duration-700 delay-100 drop-shadow-sm"
                :class="shown ? 'opacity-100 translate-y-0' : ''"
            >
                Sejarah & Legenda
            </h2>
            <div
                class="w-16 h-1 bg-gray-200 dark:bg-gray-700 mx-auto rounded-full overflow-hidden opacity-0 scale-x-0 transition-all duration-700 delay-200"
                :class="shown ? 'opacity-100 scale-x-100' : ''"
            >
                <div class="w-1/2 h-full bg-primary animate-slide-x"></div>
            </div>
        </div>

        <!-- Full Image Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16">
            <!-- Kartini Card -->
            <div
                class="group relative h-[600px] w-full rounded-[2rem] overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none"
                x-data="{ shown: false }"
                x-intersect.threshold.0.2="shown = true"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12 transition-all duration-1000'"
            >
                <!-- Full Background Image -->
                <img
                    src="{{ asset('images/kartini.jpg') }}"
                    alt="R.A. Kartini"
                    class="absolute inset-0 w-full h-full object-cover object-top filter grayscale-[0.2] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[1500ms] ease-out"
                />

                <!-- Gradient Overlay (Subtle) -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-700"
                ></div>

                <!-- Content Overlay -->
                <div
                    class="absolute bottom-0 left-0 right-0 p-8 md:p-12 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-700"
                >
                    <div
                        class="w-12 h-1 bg-white/30 backdrop-blur-sm mb-6 rounded-full group-hover:w-20 transition-all duration-500"
                    ></div>
                    <h3
                        class="text-3xl md:text-5xl font-['Playfair_Display'] font-black mb-3 leading-tight tracking-tight"
                    >
                        R.A. Kartini
                    </h3>
                    <p
                        class="text-xl md:text-2xl font-['Pinyon_Script'] text-white/90 mb-6"
                    >
                        "Habis Gelap Terbitlah Terang"
                    </p>
                    <div
                        class="h-0 group-hover:h-auto overflow-hidden transition-all duration-500 opacity-0 group-hover:opacity-100"
                    >
                        <p
                            class="text-white/80 text-sm md:text-base leading-relaxed max-w-md"
                        >
                            Pahlawan emansipasi yang memperjuangkan hak
                            pendidikan wanita. Sosoknya menginspirasi perubahan
                            besar dari Jepara untuk Indonesia.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kalinyamat Card -->
            <div
                class="group relative h-[600px] w-full rounded-[2rem] overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none transition-all duration-1000 delay-200"
                x-data="{ shown: false }"
                x-intersect.threshold.0.2="shown = true"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
            >
                <!-- Full Background Image -->
                <img
                    src="{{ asset('images/kalinyamat.jpg') }}"
                    alt="Ratu Kalinyamat"
                    class="absolute inset-0 w-full h-full object-cover object-center filter grayscale-[0.2] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[1500ms] ease-out"
                />

                <!-- Gradient Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-700"
                ></div>

                <!-- Content Overlay -->
                <div
                    class="absolute bottom-0 left-0 right-0 p-8 md:p-12 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-700"
                >
                    <div
                        class="w-12 h-1 bg-white/30 backdrop-blur-sm mb-6 rounded-full group-hover:w-20 transition-all duration-500"
                    ></div>
                    <h3
                        class="text-3xl md:text-5xl font-['Playfair_Display'] font-black mb-3 leading-tight tracking-tight"
                    >
                        Ratu Kalinyamat
                    </h3>
                    <p
                        class="text-xl md:text-2xl font-['Pinyon_Script'] text-white/90 mb-6"
                    >
                        "Sang Ratu Laut yang Gagah Berani"
                    </p>
                    <div
                        class="h-0 group-hover:h-auto overflow-hidden transition-all duration-500 opacity-0 group-hover:opacity-100"
                    >
                        <p
                            class="text-white/80 text-sm md:text-base leading-relaxed max-w-md"
                        >
                            Penguasa maritim Nusantara yang disegani. Membangun
                            Jepara menjadi pusat niaga dan kekuatan laut yang
                            tak tertandingi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

## 2. Section Destinasi Kebudayaan (Accordion Style)

Bagian ini menampilkan daftar kebudayaan secara dinamis menggunakan `@foreach`.

```html
<!-- Destinasi Kebudayaan (Modern Accordion Style) -->
<div class="w-full bg-[#1a1c23] py-16 lg:py-24 overflow-hidden relative">
    <div
        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"
    ></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header -->
        <div
            class="text-center mb-12 lg:mb-16"
            x-data="{ show: false }"
            x-intersect="show = true"
        >
            <h2
                class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight opacity-0 translate-y-10 transition-all duration-1000 ease-out"
                :class="show ? 'opacity-100 translate-y-0' : ''"
            >
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-600"
                    >Jelajahi</span
                >
                Kebudayaan
            </h2>

            <p
                class="mt-6 text-gray-400 max-w-2xl mx-auto text-lg opacity-0 translate-y-5 transition-all duration-1000 delay-500"
                :class="show ? 'opacity-100 translate-y-0' : ''"
            >
                Warisan sejarah dan tradisi yang tak lekang oleh waktu, menjadi
                identitas kebanggaan Jepara.
            </p>
        </div>

        <!-- Modern Accordion Cards -->
        <!-- Slim Vertical Stack (Wide Banners, Reduced Height) -->
        <div class="flex flex-col gap-3 px-4 md:px-0" x-data>
            @foreach($cultures as $index => $culture)
            <div
                class="group relative w-full h-[140px] hover:h-[280px] transition-all duration-500 ease-[cubic-bezier(0.25,0.1,0.25,1)] rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-2xl border border-white/5 hover:border-orange-500/30"
                style="background-image: url('{{ asset($culture->image_path) }}'); background-size: cover; background-position: center 50%;"
            >
                <!-- Dark Overlay (Removed default darkening, only on hover) -->
                <div
                    class="absolute inset-0 bg-transparent group-hover:bg-black/60 transition-all duration-500"
                ></div>

                <!-- Content Wrapper -->
                <div
                    class="absolute inset-0 flex flex-col justify-center items-center p-6 md:px-8 text-center"
                >
                    <!-- Header Row (Centered) -->
                    <div class="flex flex-col items-center w-full mb-2">
                        <span
                            class="inline-block px-3 py-1 mb-2 rounded-full bg-orange-600/90 backdrop-blur text-white text-[10px] md:text-xs font-bold shadow-lg whitespace-nowrap"
                        >
                            {{ $culture->category->name }}
                        </span>
                        <h3
                            class="text-xl md:text-3xl font-bold text-white leading-tight drop-shadow-md group-hover:text-orange-100 transition-colors"
                        >
                            {{ $culture->name }}
                        </h3>
                    </div>

                    <!-- Expanded Content (Hidden by default, reveals on hover) -->
                    <div
                        class="grid grid-rows-[0fr] group-hover:grid-rows-[1fr] transition-all duration-500 opacity-0 group-hover:opacity-100 w-full"
                    >
                        <div class="overflow-hidden">
                            <p
                                class="text-gray-300 text-sm md:text-base line-clamp-3 mb-4 max-w-3xl mx-auto"
                            >
                                {{ $culture->description }}
                            </p>
                            <a
                                href="{{ route('culture.show', $culture->slug) }}"
                                class="inline-flex items-center gap-2 text-orange-400 font-bold hover:text-orange-300 transition-colors"
                            >
                                <span>Jelajahi Detail</span>
                                <span class="material-symbols-outlined text-sm"
                                    >arrow_forward</span
                                >
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
```

## 3. Konfigurasi CSS & Tailwind (Penting!)

Agar tampilan sama persis, pastikan konfigurasi berikut ada di project baru Anda.

### `tailwind.config.js`

Tambahkan colors dan font family ini:

```javascript
theme: {
    extend: {
        colors: {
            primary: "#0ea5e9", // Ocean Blue
            "primary-dark": "#0284c7",
            "accent": "#8b5a2b", // Wood Brown
            "background-light": "#f8fafc", // Slate 50
            "background-dark": "#0f172a", // Slate 900
            "text-light": "#334155", // Slate 700
            "text-dark": "#f1f5f9", // Slate 100
            "surface-light": "#e2e8f0", // Slate 200
            "surface-dark": "#1e293b" // Slate 800
        },
        fontFamily: {
            // Pastikan font ini terinstall atau diload via Google Fonts
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            display: ["Plus Jakarta Sans"],
        },
    },
},
```

### `resources/css/app.css`

Tambahkan animasi kustom dan import font yang dibutuhkan:

```css
/* Imports untuk Font dan Icon */
@import "https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap";
@import "material-symbols";
@import "@fortawesome/fontawesome-free/css/all.css";

@layer components {
    /* Custom Animations untuk loading bar */
    @keyframes slide-x {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-slide-x {
        animation: slide-x 2s infinite linear;
    }
}
```

## 4. Dependencies Tambahan

Pastikan library berikut diload di `<head>` layout utama Anda (seperti di welcome.blade.php):

1.  **Alpine.js**: (Biasanya via CDN atau npm) untuk interaksi scroll dan hover.
2.  **Google Fonts**:
    - `Pinyon Script` (untuk tulisan estetik seperti "Sejarah & Legenda")
    - `Playfair Display` (untuk judul besar)
    - `Plus Jakarta Sans` (font body utama)
3.  **FontAwesome**: Untuk icon social media atau UI lainnya.
4.  **Material Symbols Outlined**: Untuk icon panah dan UI navigasi.
