<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'Sarpras') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 min-h-screen">

  <!-- Navbar -->
  <nav class="w-full fixed top-0 left-0 z-10 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <!-- Logo kiri -->
      <div class="flex items-center gap-3">
        <div class="w-12 h-10 flex items-center justify-center transform hover:scale-105 transition-transform relative flex-shrink-0">
          <svg viewBox="0 0 130 70" fill="none" class="w-full h-full">
            {{-- Cyan/Blue bubble (left, back) --}}
            <path d="M10 35 C10 22, 22 12, 38 12 L52 12 C68 12, 78 22, 78 35 C78 48, 68 58, 52 58 L42 58 L38 66 L36 58 L38 58 C22 58, 10 48, 10 35 Z" 
                  fill="url(#gradient-cyan-nav)" stroke="#0891b2" stroke-width="2.5"/>
            <circle cx="28" cy="35" r="3" fill="#ef4444"/>
            <circle cx="40" cy="35" r="3" fill="#f97316"/>
            <circle cx="52" cy="35" r="3" fill="#fbbf24"/>
            
            {{-- Purple bubble (right, front) --}}
            <path d="M52 25 C52 12, 64 4, 80 4 L98 4 C114 4, 124 14, 124 27 C124 40, 114 50, 98 50 L90 50 L92 58 L88 50 L82 50 C66 50, 52 38, 52 25 Z" 
                  fill="url(#gradient-purple-nav)" stroke="#7c3aed" stroke-width="2.5"/>
            <circle cx="72" cy="27" r="3" fill="#fbbf24"/>
            <circle cx="84" cy="27" r="3" fill="#f97316"/>
            <circle cx="96" cy="27" r="3" fill="#ef4444"/>
            
            <defs>
              <linearGradient id="gradient-cyan-nav" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#0891b2;stop-opacity:1" />
              </linearGradient>
              <linearGradient id="gradient-purple-nav" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#a855f7;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
              </linearGradient>
            </defs>
          </svg>
        </div>
        <div>
          <div class="font-black text-lg bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent leading-tight">NGASAR</div>
          <div class="text-[10px] text-gray-600 font-medium leading-tight">Ngadu Sarana Prasarana</div>
        </div>
      </div>

      <!-- Tombol kanan -->
      <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition">Masuk</a>
        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-purple-500 text-white text-sm font-bold shadow-lg hover:shadow-xl hover:from-purple-700 hover:to-purple-600 transition transform hover:scale-105">
          Daftar
        </a>
      </div>
    </div>
  </nav>

  <!-- Decorative shapes -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-20 right-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute top-40 left-10 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-20 right-1/3 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
  </div>

  <!-- Konten utama -->
  <div class="min-h-screen flex items-center justify-center px-4 pt-24 pb-12 relative">
    <div class="w-full max-w-md">
      <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/20">
        <div class="p-8 md:p-10">
          @yield('content')
        </div>
      </div>
      <p class="mt-8 text-center text-xs text-gray-500">
        © {{ date('Y') }} NGASAR - Sistem Pengaduan Sarpras. All rights reserved.
      </p>
    </div>
  </div>

  <style>
    @keyframes blob {
      0% { transform: translate(0px, 0px) scale(1); }
      33% { transform: translate(30px, -50px) scale(1.1); }
      66% { transform: translate(-20px, 20px) scale(0.9); }
      100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
      animation: blob 7s infinite;
    }
    .animation-delay-2000 {
      animation-delay: 2s;
    }
    .animation-delay-4000 {
      animation-delay: 4s;
    }
  </style>
</body>
</html>
