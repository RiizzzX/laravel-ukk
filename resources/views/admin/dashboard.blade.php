@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-10">
      <div class="bg-purple-600 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Total User</h3>
        <p class="text-2xl font-bold">{{ $countUsers }}</p>
      </div>
      <div class="bg-pink-500 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Total Petugas</h3>
        <p class="text-2xl font-bold">{{ $countPetugas }}</p>
      </div>
      <div class="bg-blue-500 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Total Item</h3>
        <p class="text-2xl font-bold">{{ $countItems }}</p>
      </div>
      <div class="bg-yellow-500 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Total Lokasi</h3>
        <p class="text-2xl font-bold">{{ $countLokasi }}</p>
      </div>
      <div class="bg-green-500 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Total Pengaduan</h3>
        <p class="text-2xl font-bold">{{ $countPengaduan }}</p>
      </div>
    </div>

    {{-- Shortcut Menu --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
      <a href="{{ route('admin.petugas.index') }}" class="bg-white p-6 rounded-2xl shadow hover:shadow-lg">
        <h4 class="font-semibold text-purple-700">Kelola Petugas</h4>
      </a>
      <a href="{{ route('admin.items.index') }}" class="bg-white p-6 rounded-2xl shadow hover:shadow-lg">
        <h4 class="font-semibold text-blue-700">Kelola Item</h4>
      </a>
      <a href="{{ route('admin.lokasi.index') }}" class="bg-white p-6 rounded-2xl shadow hover:shadow-lg">
        <h4 class="font-semibold text-yellow-700">Kelola Lokasi</h4>
      </a>
      <a href="{{ route('admin.pengaduan.index') }}" class="bg-white p-6 rounded-2xl shadow hover:shadow-lg">
        <h4 class="font-semibold text-green-700">Kelola Pengaduan</h4>
      </a>
    </div>

  </div>
</div>
@endsection
