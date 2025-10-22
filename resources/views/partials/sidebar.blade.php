@php
    use Illuminate\Support\Facades\Route;
    $role = auth()->check() ? auth()->user()->role : null;
@endphp

<aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-gray-200 flex flex-col shadow-lg z-40">
  <div class="flex-1 overflow-y-auto">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
      <div class="w-12 h-10 flex items-center justify-center transform hover:scale-105 transition-transform relative flex-shrink-0">
        {{-- Two interlocking chat bubbles --}}
        <svg viewBox="0 0 130 70" fill="none" class="w-full h-full">
          {{-- Cyan/Blue bubble (left, back) --}}
          <path d="M10 35 C10 22, 22 12, 38 12 L52 12 C68 12, 78 22, 78 35 C78 48, 68 58, 52 58 L42 58 L38 66 L36 58 L38 58 C22 58, 10 48, 10 35 Z" 
                fill="url(#gradient-cyan)" stroke="#0891b2" stroke-width="2.5"/>
          {{-- Dots in cyan bubble --}}
          <circle cx="28" cy="35" r="3" fill="#ef4444"/>
          <circle cx="40" cy="35" r="3" fill="#f97316"/>
          <circle cx="52" cy="35" r="3" fill="#fbbf24"/>
          
          {{-- Purple bubble (right, front) --}}
          <path d="M52 25 C52 12, 64 4, 80 4 L98 4 C114 4, 124 14, 124 27 C124 40, 114 50, 98 50 L90 50 L92 58 L88 50 L82 50 C66 50, 52 38, 52 25 Z" 
                fill="url(#gradient-purple)" stroke="#7c3aed" stroke-width="2.5"/>
          {{-- Dots in purple bubble --}}
          <circle cx="72" cy="27" r="3" fill="#fbbf24"/>
          <circle cx="84" cy="27" r="3" fill="#f97316"/>
          <circle cx="96" cy="27" r="3" fill="#ef4444"/>
          
          {{-- Gradients --}}
          <defs>
            <linearGradient id="gradient-cyan" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#0891b2;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="gradient-purple" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#a855f7;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
            </linearGradient>
          </defs>
        </svg>
      </div>
      <div>
        <div class="font-display font-black text-lg bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent leading-tight">NGASAR</div>
        <div class="text-[10px] text-gray-600 font-medium leading-tight">Ngadu Sarana Prasarana</div>
      </div>
    </div>

    {{-- Menu Navigasi --}}
    <nav class="mt-3 flex flex-col gap-0.5 px-3">
      @if($role === 'pengguna')
        {{-- Menu untuk User --}}
        <a href="{{ route('pengaduan.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('pengaduan.index') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          Dashboard
        </a>
        
        <a href="{{ route('pengaduan.riwayat') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('pengaduan.riwayat') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          Riwayat Pengaduan
        </a>
        
        <a href="{{ route('pengaduan.saran') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('pengaduan.saran') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
          Saran Item Sarpras
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Akun</p>
        </div>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          Profil Saya
        </a>
      @elseif($role === 'petugas')
        {{-- Menu untuk Petugas --}}
        <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('petugas.dashboard') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          Dashboard
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pengaduan</p>
        </div>
        
        <a href="{{ route('petugas.pengaduan.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('petugas.pengaduan.index') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
          </svg>
          Pengaduan
        </a>
        
        <a href="{{ route('petugas.pengaduan.riwayat') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('petugas.pengaduan.riwayat') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          Riwayat Pengaduan
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Akun</p>
        </div>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          Profil Saya
        </a>
      @else
        {{-- Menu untuk Admin --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          Dashboard
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Master</p>
        </div>
        
        <a href="{{ route('admin.lokasi.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.lokasi.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          Lokasi
        </a>
        
        <a href="{{ route('admin.items.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.items.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          Item
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pengaduan</p>
        </div>
        
        <a href="{{ route('admin.pengaduan.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.pengaduan.*') && !request()->routeIs('admin.pengaduan.riwayat') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
          </svg>
          Pengaduan
        </a>
        
        <a href="{{ route('admin.pengaduan.riwayat') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.pengaduan.riwayat') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          Riwayat Pengaduan
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Manajemen</p>
        </div>
        
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          Manajemen User
        </a>
        
        <a href="{{ route('admin.petugas.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.petugas.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          Manajemen Petugas
        </a>
        
        <div class="px-3 py-1.5 mt-2">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Akun</p>
        </div>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-purple-600 to-purple-500 text-white shadow-md' : 'text-gray-600 hover:bg-purple-50' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          Profil Saya
        </a>
      @endif
    </nav>
  </div>

  {{-- Profile & Logout --}}
  <div class="px-4 py-4 border-t border-gray-100 bg-gray-50">
    <div class="flex items-center gap-3 mb-3 px-2">
      <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-sm">
        <span class="font-bold text-white text-sm">{{ substr(auth()->user()->username, 0, 1) }}</span>
      </div>
      <div class="flex-1 min-w-0">
        <div class="font-semibold text-sm text-gray-800 truncate">{{ auth()->user()->username }}</div>
        <div class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-white border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50 hover:border-red-300 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Logout
      </button>
    </form>
  </div>
</aside>
