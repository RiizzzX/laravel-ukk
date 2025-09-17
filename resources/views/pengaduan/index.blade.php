<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    @include('layouts.navigation') {{-- kalau kamu punya navbar --}}

    <div class="min-h-screen">
        {{-- Tempat untuk header opsional --}}
        @yield('header')

        {{-- Tempat untuk konten --}}
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
