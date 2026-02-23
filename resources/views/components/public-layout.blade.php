@props(['hideFooter' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dinas Pariwisata dan Kebudayaan Jepara') }}</title>

    @stack('seo')
    <link rel="icon" href="{{ asset('images/logo-kura.png') }}" type="image/png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 flex flex-col min-h-screen selection:bg-primary/30 selection:text-primary">

    <!-- Top Navigation -->
    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @unless($hideFooter)
        @include('layouts.partials.footer')
    @endunless

    @stack('scripts')
</body>
</html>
