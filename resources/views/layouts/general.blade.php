<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen flex flex-col">
@yield('general')
<footer class="footer sm:footer-horizontal footer-center bg-base-300 text-base-content p-4">
    <p class="py-5">Macon &copy; - {{ date('Y') }}</p>
</footer>
</body>
</html>
