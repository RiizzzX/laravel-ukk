@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Pengguna</h1>
        <p class="text-gray-500">Selamat datang, {{ auth()->user()->username }}</p>
      </div>
      <a href="{{ route('pengaduan.create') }}"
         class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl shadow-md flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Pengaduan
      </a>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      <div class="bg-gradient-to-r from-purple-600 to-purple-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Sedang Diproses</h3>
          <p class="text-2xl font-bold">{{ $pengaduanProses ?? 0 }}</p>
        </div>
      </div>
      <div class="bg-gradient-to-r from-green-500 to-emerald-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Selesai</h3>
          <p class="text-2xl font-bold">{{ $pengaduanSelesai ?? 0 }}</p>
        </div>
      </div>
    </div>

    {{-- Daftar Pengaduan --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <table class="w-full border-collapse">
        <thead class="bg-purple-100">
          <tr>
            <th class="text-left p-4 font-semibold text-gray-700">Item</th>
            <th class="text-left p-4 font-semibold text-gray-700">Lokasi</th>
            <th class="text-left p-4 font-semibold text-gray-700">Deskripsi</th>
            <th class="text-left p-4 font-semibold text-gray-700">Status</th>
            <th class="text-left p-4 font-semibold text-gray-700">Tanggal</th>
            <th class="text-left p-4 font-semibold text-gray-700">Bukti</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pengaduan as $p)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-4">{{ $p->item->nama_item ?? '-' }}</td>
              <td class="p-4">{{ $p->lokasi->nama_lokasi ?? '-' }}</td>
              <td class="p-4">{{ $p->deskripsi }}</td>
              <td class="p-4">
                @if($p->status == 'pending')
                  <span class="flex items-center gap-1 px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700">
                    ⏳ Pending
                  </span>
                @elseif($p->status == 'proses')
                  <span class="flex items-center gap-1 px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-700">
                    🔄 Proses
                  </span>
                @else
                  <span class="flex items-center gap-1 px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">
                    ✅ Selesai
                  </span>
                @endif
              </td>
              <td class="p-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
              <td class="p-4">
                @if($p->foto)
                  <div x-data="{ open: false }" class="relative">
                    <!-- Thumbnail -->
                    <img src="{{ asset('storage/'.$p->foto) }}"
                         alt="Bukti"
                         class="w-16 h-16 object-cover rounded border cursor-pointer hover:scale-105 transition"
                         @click="open = true">

                    <!-- Modal -->
                    <div x-show="open" x-cloak
                         class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
                         @click.self="open = false">
                      <div class="bg-white p-3 rounded-xl shadow-lg max-w-2xl">
                        <img src="{{ asset('storage/'.$p->foto) }}"
                             alt="Bukti Full"
                             class="max-h-[80vh] rounded-lg object-contain">
                        <button @click="open = false"
                                class="mt-3 w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                          Tutup
                        </button>
                      </div>
                    </div>
                  </div>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center p-6 text-gray-500">Belum ada pengaduan</td>
            </tr>
          @endforelse
          <script src="//unpkg.com/alpinejs" defer></script>

        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
