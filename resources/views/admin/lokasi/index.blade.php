@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Lokasi</h1>
        <p class="text-gray-500">Manajemen data lokasi</p>
      </div>
      <a href="{{ route('admin.lokasi.create') }}" 
              class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white px-6 py-3 rounded-lg shadow-sm flex items-center gap-2 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Lokasi
      </a>
    </div>

    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

   {{-- Statistik Lokasi --}}
<div class="mb-8">
  <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    Statistik Lokasi
  </h2>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Card Total Lokasi --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-indigo-100 shadow-lg border border-purple-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
      <div class="absolute inset-0 bg-gradient-to-r from-purple-400/10 to-transparent opacity-70"></div>
      <div class="relative p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-purple-500/20 rounded-full backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-3xl font-extrabold text-purple-900">{{ $totalLokasi }}</p>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-lg font-semibold text-purple-800">Total Lokasi</h3>
          <p class="text-sm text-purple-600">Lokasi terdaftar</p>
        </div>
        <div class="mt-4">
          <div class="h-2 w-full bg-purple-200 rounded-full overflow-hidden">
            <div class="h-full bg-purple-500 rounded-full" style="width: 100%"></div>
          </div>
          <p class="text-xs text-purple-600 mt-1">{{ $totalLokasi }} lokasi terdaftar</p>
        </div>
      </div>
    </div>

    {{-- Card Total Pengaduan Lokasi --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-50 to-rose-100 shadow-lg border border-pink-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
      <div class="absolute inset-0 bg-gradient-to-r from-pink-400/10 to-transparent opacity-70"></div>
      <div class="relative p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-pink-500/20 rounded-full backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-3xl font-extrabold text-pink-900">{{ $totalPengaduanLokasi }}</p>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-lg font-semibold text-pink-800">Total Pengaduan</h3>
          <p class="text-sm text-pink-600">Dari semua lokasi</p>
        </div>
        <div class="mt-4">
          <div class="h-2 w-full bg-pink-200 rounded-full overflow-hidden">
            @php
              $maxTargetLokasi = 100; // Target maksimal pengaduan
              $percentagePengaduanLokasi = min(100, ($totalPengaduanLokasi / $maxTargetLokasi) * 100);
            @endphp
            <div class="h-full bg-pink-500 rounded-full" style="width: {{ $percentagePengaduanLokasi }}%"></div>
          </div>
          <p class="text-xs text-pink-600 mt-1">{{ number_format($percentagePengaduanLokasi, 1) }}% dari target {{ $maxTargetLokasi }}</p>
        </div>
      </div>
    </div>

    {{-- Card Rata-rata Pengaduan --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-cyan-100 shadow-lg border border-blue-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-400/10 to-transparent opacity-70"></div>
      <div class="relative p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-blue-500/20 rounded-full backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-3xl font-extrabold text-blue-900">
              {{ $totalLokasi > 0 ? number_format($totalPengaduanLokasi / $totalLokasi, 1) : 0 }}
            </p>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-lg font-semibold text-blue-800">Rata-rata Pengaduan</h3>
          <p class="text-sm text-blue-600">Per lokasi</p>
        </div>
        <div class="mt-4">
          <div class="h-2 w-full bg-blue-200 rounded-full overflow-hidden">
            @php
              $rataRataLokasi = $totalLokasi > 0 ? $totalPengaduanLokasi / $totalLokasi : 0;
              $maxScaleLokasi = 10; // Skala maksimal untuk rata-rata
              $percentageRataRataLokasi = min(100, ($rataRataLokasi / $maxScaleLokasi) * 100);
            @endphp
            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $percentageRataRataLokasi }}%"></div>
          </div>
          <p class="text-xs text-blue-600 mt-1">
            {{ number_format($rataRataLokasi, 1) }} pengaduan/lokasi (skala 0-{{ $maxScaleLokasi }})
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-100">
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">ID</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Nama Lokasi</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Items</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($lokasi as $l)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $l->id_lokasi }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 font-semibold">{{ $l->nama_lokasi }}</td>
                <td class="px-6 py-4">
                  @php
                    $items = $l->items ?? [];
                    if (is_string($items)) {
                      $items = json_decode($items, true) ?? [];
                    }
                  @endphp
                  
                  @if(is_array($items) && count($items) > 0)
                    <button onclick="toggleItems({{ $l->id_lokasi }})" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 transition">
                      Lihat Items
                    </button>
                    <div id="items-{{ $l->id_lokasi }}" class="hidden mt-2 p-3 bg-gray-50 rounded-lg text-xs">
                      <div class="font-semibold text-gray-700 mb-2">Daftar Barang:</div>
                      <div class="grid grid-cols-2 gap-2">
                        @foreach($items as $item)
                          <div class="flex items-center gap-2 text-gray-600">
                            <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                            <span>{{ $item['nama_item'] ?? 'N/A' }}</span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @else
                    <span class="text-gray-400 text-xs">Tidak ada item</span>
                  @endif
                </td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <a href="{{ route('admin.lokasi.edit', $l->id_lokasi) }}" class="px-4 py-1.5 rounded-lg bg-orange-100 text-orange-700 text-xs font-bold hover:bg-orange-200 transition">Edit</a>
                    <form action="{{ route('admin.lokasi.destroy', $l->id_lokasi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus lokasi ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="px-4 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-bold hover:bg-red-200 transition">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                  <p class="font-medium">Belum ada lokasi</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Pagination --}}
    @if($lokasi->hasPages())
      <div class="mt-6">
        {{ $lokasi->links() }}
      </div>
    @endif

  </div>
</div>

<script>
function toggleItems(id) {
  const element = document.getElementById('items-' + id);
  if (element.classList.contains('hidden')) {
    element.classList.remove('hidden');
  } else {
    element.classList.add('hidden');
  }
}
</script>
@endsection
