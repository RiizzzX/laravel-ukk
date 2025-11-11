@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Petugas</h1>
        <p class="text-gray-500">Selamat datang, {{ auth()->user()->username }} 👋</p>
      </div>
      {{-- Badge Notifikasi Pengaduan Baru (Alert kelap-kelip - hilang setelah buka halaman pengaduan) --}}
      @if(isset($pengaduanBaruAlert) && $pengaduanBaruAlert > 0)
        <div class="relative">
          <div class="bg-red-500 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg flex items-center gap-2 animate-pulse">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span>{{ $pengaduanBaruAlert }}</span>
            <span class="text-sm font-normal">Pengaduan Baru!</span>
          </div>
        </div>
      @endif
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
      <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-10">
      <div class="bg-gradient-to-r from-purple-600 to-purple-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total Saya</h3>
          <p class="text-2xl font-bold">{{ $totalPengaduan ?? 0 }}</p>
        </div>
      </div>
      
      <div class="bg-gradient-to-r from-yellow-500 to-orange-400 text-white p-6 rounded-2xl shadow flex items-center gap-4 relative">
        @if($pengaduanBaru > 0)
          <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-bounce">{{ $pengaduanBaru }}</span>
        @endif
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Pengaduan Baru</h3>
          <p class="text-2xl font-bold">{{ $pengaduanBaru ?? 0 }}</p>
        </div>
      </div>

      <div class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Diproses</h3>
          <p class="text-2xl font-bold">{{ $pengaduanDiproses ?? 0 }}</p>
        </div>
      </div>
      
      <div class="bg-gradient-to-r from-green-500 to-emerald-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Selesai</h3>
          <p class="text-2xl font-bold">{{ $pengaduanSelesai ?? 0 }}</p>
        </div>
      </div>
    </div>

    {{-- Statistik Kinerja Diri Sendiri --}}
    <div class="mb-10">
      <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Kinerja Saya
      </h2>

      @php
        $mySelesai = $pengaduanSelesai ?? 0;
        $myDiproses = $pengaduanDiproses ?? 0;
        $myTotal = $mySelesai + $myDiproses;
        $myPercentage = $myTotal > 0 ? round(($mySelesai / $myTotal) * 100) : 0;
      @endphp

      <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
          
          {{-- Circular Progress --}}
          <div class="flex justify-center">
            <div class="relative">
              <div class="relative w-48 h-48">
                {{-- Background Circle --}}
                <svg class="w-48 h-48 transform -rotate-90">
                  <circle cx="96" cy="96" r="88" stroke="#E5E7EB" stroke-width="12" fill="none"/>
                  {{-- Progress Circle --}}
                  <circle cx="96" cy="96" r="88" 
                          stroke="url(#my-gradient)" 
                          stroke-width="12" 
                          fill="none"
                          stroke-linecap="round"
                          stroke-dasharray="{{ 2 * 3.14159 * 88 }}"
                          stroke-dashoffset="{{ 2 * 3.14159 * 88 * (1 - $myPercentage / 100) }}"
                          class="transition-all duration-1000 ease-out"
                          style="filter: drop-shadow(0 4px 6px rgba(168, 85, 247, 0.4))"/>
                  <defs>
                    <linearGradient id="my-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                      <stop offset="0%" style="stop-color:#A855F7;stop-opacity:1" />
                      <stop offset="50%" style="stop-color:#EC4899;stop-opacity:1" />
                      <stop offset="100%" style="stop-color:#F59E0B;stop-opacity:1" />
                    </linearGradient>
                  </defs>
                </svg>
                {{-- Center Content --}}
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                  <div class="text-center">
                    <div class="text-5xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 bg-clip-text text-transparent mb-1">
                      {{ $myPercentage }}%
                    </div>
                    <div class="text-sm text-gray-500 font-medium">Tingkat Selesai</div>
                  </div>
                </div>
              </div>
              {{-- Ring Decoration --}}
              <div class="absolute inset-0 w-48 h-48 rounded-full bg-gradient-to-br from-purple-100 via-pink-100 to-orange-100 opacity-20 blur-xl -z-10"></div>
            </div>
          </div>

          {{-- Stats Detail --}}
          <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-600 font-medium">Pengaduan Selesai</p>
                  <p class="text-xs text-gray-500">Total yang sudah dituntaskan</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-3xl font-bold text-green-600">{{ $mySelesai }}</p>
                <p class="text-xs text-green-600">Pengaduan</p>
              </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-600 font-medium">Sedang Diproses</p>
                  <p class="text-xs text-gray-500">Dalam penanganan saat ini</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-3xl font-bold text-blue-600">{{ $myDiproses }}</p>
                <p class="text-xs text-blue-600">Pengaduan</p>
              </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 via-pink-50 to-orange-50 rounded-xl border-2 border-purple-300 shadow-md">
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 flex items-center justify-center shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-700 font-bold">Total Ditangani</p>
                  <p class="text-xs text-gray-600">Selesai + Sedang Diproses</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 bg-clip-text text-transparent">{{ $myTotal }}</p>
                <p class="text-xs text-purple-600 font-semibold">Pengaduan</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- Quick Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      {{-- Card Pengaduan Aktif --}}
      <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition">
        <div class="px-6 py-8 bg-gradient-to-br from-purple-50 to-pink-50 border-b border-purple-100">
          <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <div class="text-right">
              <p class="text-4xl font-bold text-purple-600">{{ count($pengaduanAktif) }}</p>
              <p class="text-sm text-gray-500">Pengaduan</p>
            </div>
          </div>
          <h3 class="font-bold text-gray-800 text-xl mb-2">Pengaduan Aktif</h3>
          <p class="text-sm text-gray-600 mb-6">Pengaduan yang tersedia untuk diambil dan sedang Anda kerjakan</p>
          <a href="{{ route('petugas.pengaduan.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl text-center font-semibold transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Lihat Pengaduan Aktif →
          </a>
        </div>
      </div>

      {{-- Card Riwayat --}}
      <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition">
        <div class="px-6 py-8 bg-gradient-to-br from-emerald-50 to-green-50 border-b border-emerald-100">
          <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow-lg">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="text-right">
              <p class="text-4xl font-bold text-emerald-600">{{ count($pengaduanRiwayat) }}</p>
              <p class="text-sm text-gray-500">Terakhir</p>
            </div>
          </div>
          <h3 class="font-bold text-gray-800 text-xl mb-2">Riwayat Pengaduan</h3>
          <p class="text-sm text-gray-600 mb-6">Pengaduan yang sudah Anda selesaikan dan histori pekerjaan Anda</p>
          <a href="{{ route('petugas.pengaduan.riwayat') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl text-center font-semibold transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Lihat Riwayat Selesai →
          </a>
        </div>
      </div>
    </div>

  
<script>
  // Redirect ke halaman pengaduan atau riwayat jika ada error terkait action
  @if(session('error'))
    setTimeout(() => {
      const errorMsg = "{{ session('error') }}";
      if (errorMsg.includes('pengaduan') || errorMsg.includes('aksi')) {
        // Error dari action, tapi tetap di dashboard untuk info
      }
    }, 3000);
  @endif
</script>
@endsection
