@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard Petugas</h1>
      <p class="text-gray-500">Selamat datang, {{ auth()->user()->username }} 👋</p>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      <div class="bg-gradient-to-r from-purple-600 to-purple-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total Pengaduan</h3>
          <p class="text-2xl font-bold">{{ $totalPengaduan ?? 0 }}</p>
        </div>
      </div>
      <div class="bg-gradient-to-r from-yellow-500 to-orange-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Pending</h3>
          <p class="text-2xl font-bold">{{ $pengaduanPending ?? 0 }}</p>
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

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <a href="{{ route('petugas.pengaduan.index') }}" class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all border-2 border-purple-100 hover:border-purple-300 group">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-800">Kelola Pengaduan</h3>
            <p class="text-sm text-gray-500">Lihat dan proses pengaduan aktif</p>
          </div>
        </div>
      </a>

      <a href="{{ route('petugas.pengaduan.riwayat') }}" class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all border-2 border-emerald-100 hover:border-emerald-300 group">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-800">Riwayat Pengaduan</h3>
            <p class="text-sm text-gray-500">Lihat pengaduan yang sudah selesai</p>
          </div>
        </div>
      </a>
    </div>

  </div>
</div>
@endsection
