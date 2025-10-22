<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'Sarpras') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

  <!-- Navbar -->
  <nav class="w-full fixed top-0 left-0 z-10 bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
      <!-- Logo kiri -->
      <div class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-full bg-purple-600 flex items-center justify-center">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 11c0 1.66-1.34 3-3 3s-3-1.34-3-3 
              1.34-3 3-3 3 1.34 3 3zM19 11c0 1.66-1.34 
              3-3 3s-3-1.34-3-3 1.34-3 3-3 3 1.34 3 3z"/>
          </svg>
        </div>
        <span class="font-semibold text-gray-800">Sarpras</span>
      </div>

      <!-- Tombol kanan -->
      <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Masuk</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-purple-600 text-white text-sm font-medium shadow hover:bg-purple-700 transition">
          Daftar
        </a>
      </div>
    </div>
  </nav>

  <!-- Konten utama -->
  <div class="min-h-screen flex items-center justify-center px-4 pt-24">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8">
          @yield('content')
        </div>
      </div>
      <p class="mt-6 text-center text-xs text-gray-400">© {{ date('Y') }} Sarpras</p>
    </div>
  </div>
</body>
</html>
