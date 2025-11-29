<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ sidebarMinimized: localStorage.getItem('sidebarMinimized') === 'true' }" x-init="$watch('sidebarMinimized', value => localStorage.setItem('sidebarMinimized', value))">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside class="flex-shrink-0 z-30 transition-all duration-300 ease-in-out" :class="sidebarMinimized ? 'w-20' : 'md:w-64'">
                @include('layouts.sidebar')
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden relative">
                <!-- Top Header (Optional, mostly for mobile toggle placeholder if needed, or breadcrumbs) -->
                <!-- For now, we keep it clean as sidebar handles nav -->

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    @isset($header)
                        <header class="bg-white sticky top-0 z-20 h-20 flex items-center border-b border-gray-200">
                            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    @if(isset($attributes) && $attributes->get('full-width'))
                        {{ $slot }}
                    @else
                        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                            {{ $slot }}
                        </div>
                    @endif
                </main>
            </div>
        </div>
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Global Delete Confirmation
                document.body.addEventListener('submit', function(e) {
                    if (e.target.classList.contains('delete-form')) {
                        e.preventDefault();
                        const form = e.target;
                        
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                });

                // Success Flash Message
                @if(session('status') || session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('status') ?? session('success') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
            });
        </script>
    </body>
</html>
