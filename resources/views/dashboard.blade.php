@extends('layouts.app')

@section('content')
<div class="grid grid-cols-3 gap-6">
  <div class="bg-white rounded-2xl p-6 shadow">
    <h2 class="text-lg font-semibold text-gray-700">Total Petugas</h2>
    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalPetugas ?? 0 }}</p>
  </div>
  <div class="bg-white rounded-2xl p-6 shadow">
    <h2 class="text-lg font-semibold text-gray-700">Total Item</h2>
    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalItem ?? 0 }}</p>
  </div>
  <div class="bg-white rounded-2xl p-6 shadow">
    <h2 class="text-lg font-semibold text-gray-700">Total Pengaduan</h2>
    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalPengaduan ?? 0 }}</p>
  </div>
</div>
@endsection
