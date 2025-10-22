@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Laporan Pengaduan</h1>
        <p class="text-gray-500">Laporan lengkap semua pengaduan sarana prasarana</p>
      </div>
      <button onclick="window.print()" class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-3 rounded-lg shadow flex items-center gap-2 font-semibold hover:from-purple-700 hover:to-pink-600 transition print:hidden">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak Laporan
      </button>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8 print:grid-cols-4">
      <div class="bg-gradient-to-br from-purple-500 to-purple-400 text-white p-6 rounded-xl shadow-lg">
        <div class="text-center">
          <p class="text-sm opacity-90 mb-2">Total Pengaduan</p>
          <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
        </div>
      </div>
      <div class="bg-gradient-to-br from-yellow-500 to-yellow-400 text-white p-6 rounded-xl shadow-lg">
        <div class="text-center">
          <p class="text-sm opacity-90 mb-2">Pending</p>
          <p class="text-4xl font-bold">{{ $stats['pending'] }}</p>
        </div>
      </div>
      <div class="bg-gradient-to-br from-blue-500 to-blue-400 text-white p-6 rounded-xl shadow-lg">
        <div class="text-center">
          <p class="text-sm opacity-90 mb-2">Diproses</p>
          <p class="text-4xl font-bold">{{ $stats['diproses'] }}</p>
        </div>
      </div>
      <div class="bg-gradient-to-br from-green-500 to-green-400 text-white p-6 rounded-xl shadow-lg">
        <div class="text-center">
          <p class="text-sm opacity-90 mb-2">Selesai</p>
          <p class="text-4xl font-bold">{{ $stats['selesai'] }}</p>
        </div>
      </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Daftar Pengaduan</h3>
            <p class="text-xs text-gray-500">Tanggal cetak: {{ now()->format('d F Y H:i') }}</p>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-50">
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">No</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">ID</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">Tanggal</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">User</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">Item</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">Lokasi</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">Deskripsi</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">Status</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-purple-900">Petugas</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($pengaduan as $index => $p)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $p->id_pengaduan }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $p->user->username ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $p->lokasiRelation->nama_lokasi ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">{{ Str::limit($p->deskripsi, 50) }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold
                    @if($p->status=='pending') bg-yellow-100 text-yellow-700
                    @elseif($p->status=='diproses') bg-blue-100 text-blue-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst($p->status) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $p->petugas->username ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center px-6 py-12 text-gray-400">
                  <p class="font-medium">Belum ada data pengaduan</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Footer untuk print --}}
    <div class="hidden print:block mt-8 pt-6 border-t border-gray-300">
      <div class="flex justify-between items-end">
        <div>
          <p class="text-sm text-gray-600">Dicetak oleh: {{ auth()->user()->username }}</p>
          <p class="text-sm text-gray-600">Tanggal: {{ now()->format('d F Y H:i:s') }}</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-600 mb-16">Mengetahui,</p>
          <p class="text-sm text-gray-800 font-bold border-t border-gray-800 pt-1">Admin NGASAR</p>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  @media print {
    body {
      print-color-adjust: exact;
      -webkit-print-color-adjust: exact;
    }
    .print\:hidden {
      display: none !important;
    }
    .print\:block {
      display: block !important;
    }
    @page {
      size: landscape;
      margin: 1cm;
    }
  }
</style>
@endsection
