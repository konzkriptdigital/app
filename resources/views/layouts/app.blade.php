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

        {{-- <link rel="stylesheet" href="{{ asset("resources/css/custom.css") }}">
        <script src="{{ asset("resources/js/custom.js") }}" defer></script> --}}
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/custom.css', 'resources/js/custom.js'])
    </head>
    <body class="font-sans antialiased">
        <canvas id="gradient-canvas" data-transition-in></canvas>
        <div class="relative z-10 flex flex-row min-h-screen">
            <livewire:layout.sidebar />

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
        {{-- <livewire:chatbox/> --}}
    </body>
</html>
