<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- NB International Pro font --}}
    <link href="https://fonts.cdnfonts.com/css/nb" rel="stylesheet">
    {{-- Design system fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

</head>

<body>
@include('custom.includes.navbar')

@yield('content')

@yield('javascript')

@stack('scripts')

@include('custom.includes.footer')
</body>

</html>
