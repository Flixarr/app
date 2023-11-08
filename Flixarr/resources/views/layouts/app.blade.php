<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeMode">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title . ' - ' . config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <script>
        if (
            localStorage.theme === "dark" ||
            (!("theme" in localStorage) &&
                window.matchMedia("(prefers-color-scheme: dark)").matches)
        ) {
            console.log('Setting theme mode to "dark"');
            localStorage.theme = "dark"
            document.documentElement.classList.add("dark");
        } else {
            console.log('Setting theme mode to "light"');
            localStorage.theme = "light"
            document.documentElement.classList.remove("dark");
        }
    </script>
</head>

<body class="@env('local') pb-6 @endenv">
    <!-- Toasts -->
    <livewire:toasts />

    <!-- Main Content -->
    <div class="h-full">
        {{ $slot }}
    </div>

    <x-util.dev-bar />

    {{-- <div id="nprogress">
        <div class="bar" role="bar" style="transform: translate3d(-60.5008%, 0px, 0px); transition: all 200ms ease 0s;">
            <div class="peg"></div>
        </div>
        <div class="spinner" role="spinner">
            <div class="spinner-icon"></div>
        </div>
    </div> --}}

    <!-- Livewire Script Config -->
    @livewireScriptConfig

    <!-- Custom Scripts -->
    @stack('scripts')
</body>

</html>
