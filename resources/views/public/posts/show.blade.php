<x-public-layout>
    <!-- Hero Section -->
    <div class="relative h-[50vh] min-h-[400px] w-full overflow-hidden -mt-20">
        <img src="{{ $post->image_path }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16">
            <div class="max-w-4xl mx-auto">
                <span class="inline-block px-3 py-1 mb-4 text-xs font-bold text-white uppercase tracking-wider rounded-full {{ $post->type == 'event' ? 'bg-purple-600' : 'bg-blue-600' }}">
                    {{ $post->type == 'event' ? 'Agenda & Event' : 'Berita Terkini' }}
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight shadow-sm">{{ $post->title }}</h1>
                <div class="flex items-center gap-4 text-white/80 text-sm">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">calendar_today</span>
                        {{ $post->published_at ? $post->published_at->format('d F Y') : '-' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">person</span>
                        Admin Wisata
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="bg-white rounded-2xl p-8 md:p-12 shadow-xl border border-gray-100 -mt-20 relative z-10">
            <article class="prose prose-lg prose-slate max-w-none">
                {!! nl2br(e($post->content)) !!}
            </article>

            <div class="mt-12 pt-8 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ route('welcome') }}#news" class="inline-flex items-center gap-2 text-slate-600 hover:text-blue-600 font-bold transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali ke Beranda
                </a>
                
                <!-- Share Buttons (Mockup) -->
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors">
                        <i class="fa-brands fa-facebook-f"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition-colors">
                        <i class="fa-brands fa-whatsapp"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center hover:bg-sky-100 transition-colors">
                        <i class="fa-brands fa-twitter"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
