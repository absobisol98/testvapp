<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Global font: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>*, body { font-family: 'Inter', system-ui, sans-serif; }</style>
    {{-- Tailwind --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    {{-- Swiper JS --}}
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <title>@yield('title') - Ayala Foundation</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo-vapp.svg') }}">

</head>

<body>
@include('custom.includes.navbar')

@yield('content')

@yield('javascript')

@stack('scripts')

@include('custom.includes.footer')
</body>

</html>
