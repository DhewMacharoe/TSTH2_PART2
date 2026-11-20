<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Tanaman TSTH2')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tsth2-logo.png') }}">

    {{-- Load CSS & JS dari Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font yang digunakan pada desain katalog & navbar seragam --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        body { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Navbar Seragam Port 8000 --}}
    @include('partials.navbar')

    {{-- Konten halaman --}}
    <main class="catalog-page min-h-screen py-8 sm:py-10">
        <div class="mx-auto max-w-[1180px] px-4 sm:px-7 lg:px-10">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

</body>
</html>
