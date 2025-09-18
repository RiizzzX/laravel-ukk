@php
    use Illuminate\Support\Facades\Route;
    $role = auth()->check() ? auth()->user()->role : null;
@endphp

<aside class="fixed left-0 top-0 h-full bg-purple-700 text-white w-64 transition-all duration-300">
  {{-- Logo --}}
  <div class="p-4 flex items-center gap-3">
    <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center shadow">
      <svg class="w-7 h-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 
             1.343-3 3-3 3 1.343 3 3zM19 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 
             1.343-3 3-3 3 1.343 3 3z"/>
      </svg>
    </div>
    <div class="font-semibold text-lg">Sarpras</div>
  </div>

  {{-- Menu Navigasi --}}
  <nav class="mt-6 flex flex-col gap-1 px-2">
    {{-- ADMIN --}}
    @if($role === 'admin')
      <a href="{{ route('admin.dashboard') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="{{ route('admin.petugas.index') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3 1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657 5 8 5 5 6.343 5 8s1.343 3 3 3z"/></svg>
        <span>Kelola Petugas</span>
      </a>
      <a href="{{ route('admin.items.index') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v10H3z"/></svg>
        <span>Items</span>
      </a>
      <a href="{{ route('admin.lokasi.index') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l6 6v7a6 6 0 11-12 0V8l6-6z"/></svg>
        <span>Lokasi</span>
      </a>
      <a href="{{ route('admin.pengaduan.index') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/></svg>
        <span>Pengaduan</span>
      </a>
    @endif

    {{-- PETUGAS --}}
    @if($role === 'petugas')
      <a href="{{ route('petugas.dashboard') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v3h6v-3c0-1.657-1.343-3-3-3zM12 2a4 4 0 100 8 4 4 0 000-8z"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="{{ route('petugas.dashboard') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>Kelola Pengaduan</span>
      </a>
    @endif

    {{-- PENGGUNA --}}
    @if($role === 'pengguna')
      <a href="{{ route('pengaduan.index') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
        <span>Pengaduan Saya</span>
      </a>
      <a href="{{ route('pengaduan.create') }}"
         class="flex items-center gap-3 p-3 rounded hover:bg-purple-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Buat Pengaduan</span>
      </a>
    @endif
  </nav>

  {{-- Logout --}}
  <div class="absolute bottom-4 left-0 right-0 px-2">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="w-full flex items-center gap-3 p-3 rounded hover:bg-red-600">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
        <span>Logout</span>
      </button>
    </form>
  </div>
</aside>
