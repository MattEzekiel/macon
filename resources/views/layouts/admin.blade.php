<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ strtoupper(config('app.name'))  }} | @yield('title')</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow">
            <x-admin.sidebar>
                @yield('admin')
            </x-admin.sidebar>
        </div>
        <x-admin.footer></x-admin.footer>
    </div>
    @stack('scripts')
</body>
</html>
