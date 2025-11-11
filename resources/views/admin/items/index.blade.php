@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Item</h1>
        <p class="text-gray-500">Manajemen data item inventaris</p>
      </div>
      <a href="{{ route('admin.items.create') }}"   class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white px-6 py-3 rounded-lg shadow-sm flex items-center gap-2 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah item
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

   {{-- Statistik Item --}}
<div class="mb-8">
  <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    Statistik Item
  </h2>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Card Total Item --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-indigo-100 shadow-lg border border-purple-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
      <div class="absolute inset-0 bg-gradient-to-r from-purple-400/10 to-transparent opacity-70"></div>
      <div class="relative p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-purple-500/20 rounded-full backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-3xl font-extrabold text-purple-900">{{ $totalItems }}</p>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-lg font-semibold text-purple-800">Total Item Terdaftar</h3>
          <p class="text-sm text-purple-600">Semua item dalam sistem</p>
        </div>
        <div class="mt-4">
          <div class="h-2 w-full bg-purple-200 rounded-full overflow-hidden">
            <div class="h-full bg-purple-500 rounded-full" style="width: 100%"></div>
          </div>
          <p class="text-xs text-purple-600 mt-1">{{ $totalItems }} item terdaftar</p>
        </div>
      </div>
    </div>

    {{-- Card Total Pengaduan Item --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-50 to-rose-100 shadow-lg border border-pink-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
      <div class="absolute inset-0 bg-gradient-to-r from-pink-400/10 to-transparent opacity-70"></div>
      <div class="relative p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-pink-500/20 rounded-full backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-3xl font-extrabold text-pink-900">{{ $totalPengaduanItem }}</p>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-lg font-semibold text-pink-800">Total Pengaduan</h3>
          <p class="text-sm text-pink-600">Laporan terkait item</p>
        </div>
        <div class="mt-4">
          <div class="h-2 w-full bg-pink-200 rounded-full overflow-hidden">
            @php
              $maxTarget = 100; // Target maksimal pengaduan
              $percentagePengaduan = min(100, ($totalPengaduanItem / $maxTarget) * 100);
            @endphp
            <div class="h-full bg-pink-500 rounded-full" style="width: {{ $percentagePengaduan }}%"></div>
          </div>
          <p class="text-xs text-pink-600 mt-1">{{ number_format($percentagePengaduan, 1) }}% dari target {{ $maxTarget }}</p>
        </div>
      </div>
    </div>

    {{-- Card Rata-rata --}}
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
              {{ $totalItems > 0 ? number_format($totalPengaduanItem / $totalItems, 1) : 0 }}
            </p>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-lg font-semibold text-blue-800">Rata-rata Laporan</h3>
          <p class="text-sm text-blue-600">Per item</p>
        </div>
        <div class="mt-4">
          <div class="h-2 w-full bg-blue-200 rounded-full overflow-hidden">
            @php
              $rataRata = $totalItems > 0 ? $totalPengaduanItem / $totalItems : 0;
              $maxScale = 10; // Skala maksimal untuk rata-rata
              $percentageRataRata = min(100, ($rataRata / $maxScale) * 100);
            @endphp
            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $percentageRataRata }}%"></div>
          </div>
          <p class="text-xs text-blue-600 mt-1">
            {{ number_format($rataRata, 1) }} pengaduan/item (skala 0-{{ $maxScale }})
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-50/50 border-b border-purple-100">
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">ID</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Nama Item</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Deskripsi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Lokasi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->id_item }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">
                  <div class="flex items-center gap-2">
                    {{ $item->nama_item }}
                    @if($item->is_temporary)
                      <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">
                        Temporary
                      </span>
                    @endif
                    @if($item->is_temporary && !$item->is_approved)
                      <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                        Pending
                      </span>
                    @endif
                    @if($item->is_approved)
                      <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                        Approved
                      </span>
                    @endif
                  </div>
                  @if($item->is_temporary && $item->creator)
                    <p class="text-xs text-gray-500 mt-1">Dibuat oleh: {{ $item->creator->username }}</p>
                  @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  <div class="max-w-xs">
                    {{ $item->deskripsi ? Str::limit($item->deskripsi, 80) : '-' }}
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  @if($item->listLokasi->isNotEmpty())
                    {{ $item->listLokasi->first()->lokasi->nama_lokasi ?? '-' }}
                  @else
                    -
                  @endif
                </td>
                <td class="px-6 py-4">
                  <div class="flex gap-2 flex-wrap">
                    @if($item->is_temporary && !$item->is_approved)
                      <form action="{{ route('admin.items.approve', $item->id_item) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-100 text-green-700 text-xs font-bold hover:bg-green-200 transition">
                          ✓ Approve
                        </button>
                      </form>
                      <form action="{{ route('admin.items.destroy', $item->id_item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin reject item temporary ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-bold hover:bg-red-200 transition">
                          ✗ Reject
                        </button>
                      </form>
                    @else
                      <a href="{{ route('admin.items.edit', $item->id_item) }}" class="px-4 py-1.5 rounded-lg bg-orange-100 text-orange-700 text-xs font-bold hover:bg-orange-200 transition">Edit</a>
                      <form action="{{ route('admin.items.destroy', $item->id_item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus item ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-bold hover:bg-red-200 transition">Hapus</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                  <p class="font-medium">Belum ada item</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($items->hasPages())
      <div class="mt-6">
        {{ $items->links() }}
      </div>
    @endif

  </div>
</div>
@endsection
