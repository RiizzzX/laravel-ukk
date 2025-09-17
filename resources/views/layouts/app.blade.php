<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sarpras') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Konten utama --}}
        <main class="flex-1 ml-16 md:ml-64 transition-all duration-300 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
