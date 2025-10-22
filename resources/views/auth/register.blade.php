@extends('layouts.guest')

@section('content')
  {{-- Header dengan gradient --}}
  <div class="text-center mb-8">
    <div class="mx-auto w-32 h-16 flex items-center justify-center transform hover:scale-105 transition-transform">
      <svg viewBox="0 0 130 70" fill="none" class="w-full h-full">
        {{-- Cyan/Blue bubble (left, back) --}}
        <path d="M10 35 C10 22, 22 12, 38 12 L52 12 C68 12, 78 22, 78 35 C78 48, 68 58, 52 58 L42 58 L38 66 L36 58 L38 58 C22 58, 10 48, 10 35 Z" 
              fill="url(#gradient-cyan-reg)" stroke="#0891b2" stroke-width="2.5"/>
        {{-- Dots in cyan bubble --}}
        <circle cx="28" cy="35" r="3" fill="#ef4444"/>
        <circle cx="40" cy="35" r="3" fill="#f97316"/>
        <circle cx="52" cy="35" r="3" fill="#fbbf24"/>
        
        {{-- Purple bubble (right, front) --}}
        <path d="M52 25 C52 12, 64 4, 80 4 L98 4 C114 4, 124 14, 124 27 C124 40, 114 50, 98 50 L90 50 L92 58 L88 50 L82 50 C66 50, 52 38, 52 25 Z" 
              fill="url(#gradient-purple-reg)" stroke="#7c3aed" stroke-width="2.5"/>
        {{-- Dots in purple bubble --}}
        <circle cx="72" cy="27" r="3" fill="#fbbf24"/>
        <circle cx="84" cy="27" r="3" fill="#f97316"/>
        <circle cx="96" cy="27" r="3" fill="#ef4444"/>
        
        {{-- Gradients --}}
        <defs>
          <linearGradient id="gradient-cyan-reg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0891b2;stop-opacity:1" />
          </linearGradient>
          <linearGradient id="gradient-purple-reg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#a855f7;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
          </linearGradient>
        </defs>
      </svg>
    </div>
    <div class="mt-4">
      <div class="font-black text-3xl bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent leading-tight">NGASAR</div>
      <div class="text-sm text-gray-600 font-medium mt-1">Ngadu Sarana Prasarana</div>
    </div>
    <p class="text-gray-500 text-sm mt-4">Lengkapi form di bawah untuk membuat akun</p>
  </div>

  {{-- Notifikasi error --}}
  @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="text-sm space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    {{-- Username --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <input type="text" name="username" value="{{ old('username') }}" required autofocus
          class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
          placeholder="Masukkan username unik">
      </div>
      @error('username')<p class="text-xs text-red-600 mt-2 flex items-center gap-1"><span>⚠️</span>{{ $message }}</p>@enderror
    </div>

    {{-- Nama Lengkap --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <input type="text" name="name" value="{{ old('name') }}" required
          class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
          placeholder="Masukkan nama lengkap">
      </div>
      @error('name')<p class="text-xs text-red-600 mt-2 flex items-center gap-1"><span>⚠️</span>{{ $message }}</p>@enderror
    </div>

    {{-- Password --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <input type="password" name="password" required
          class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
          placeholder="Minimal 8 karakter">
      </div>
      @error('password')<p class="text-xs text-red-600 mt-2 flex items-center gap-1"><span>⚠️</span>{{ $message }}</p>@enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <input type="password" name="password_confirmation" required
          class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
          placeholder="Ketik ulang password">
      </div>
      @error('password_confirmation')<p class="text-xs text-red-600 mt-2 flex items-center gap-1"><span>⚠️</span>{{ $message }}</p>@enderror
    </div>

    {{-- Submit --}}
    <button type="submit"
      class="w-full py-4 rounded-xl font-bold text-white bg-gradient-to-r from-purple-600 to-purple-500 shadow-lg hover:shadow-xl hover:from-purple-700 hover:to-purple-600 transform hover:scale-[1.02] transition-all flex items-center justify-center gap-2 group mt-6">
      <span>Daftar Sekarang</span>
      <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
      </svg>
    </button>
  </form>

  {{-- Divider --}}
  <div class="relative my-8">
    <div class="absolute inset-0 flex items-center">
      <div class="w-full border-t border-gray-200"></div>
    </div>
    <div class="relative flex justify-center text-sm">
      <span class="px-4 bg-white text-gray-500">atau</span>
    </div>
  </div>

  {{-- Link login --}}
  <div class="text-center">
    <p class="text-sm text-gray-600">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-purple-600 font-bold hover:text-purple-700 hover:underline transition">
        Masuk Sekarang →
      </a>
    </p>
  </div>

  {{-- Info --}}
  <div class="mt-8 p-4 bg-purple-50 rounded-xl border border-purple-100">
    <div class="flex items-start gap-3">
      <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
      </svg>
      <div>
        <p class="text-sm font-semibold text-purple-900">Keamanan Data Terjamin</p>
        <p class="text-xs text-purple-700 mt-1">Data Anda akan kami jaga dengan aman dan tidak akan dibagikan kepada pihak lain.</p>
      </div>
    </div>
  </div>
@endsection
