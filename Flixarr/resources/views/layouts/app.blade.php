<!DOCTYPE html>
<html class="h-full dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if (isset($title))
        <title>{{ $title . ' - ' . config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/themeMode.js']) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    @livewireStyles

</head>

{{-- Add "pb-6" to body when in development for the dev bar at bottom of page --}}

<body class="@env('local') pb-6 @endenv">

    <!-- Toasts -->
    <livewire:toasts />

    <!-- Main Content -->
    <div class="main-content">
        {{ $slot }}
    </div>

    <!-- Development Bar -->
    <x-util.dev-bar />

    <!-- Livewire Script Config -->
    @livewireScriptConfig

    <!-- Custom Scripts -->
    @stack('scripts')

</body>

</html>
