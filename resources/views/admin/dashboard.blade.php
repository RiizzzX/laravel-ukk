@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500">Halo, {{ auth()->user()->username }}</p>
      </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-10">
      {{-- card total user --}}
      <div class="bg-gradient-to-r from-purple-600 to-purple-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6h10z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total User</h3>
          <p class="text-2xl font-bold">{{ $countUsers ?? 0 }}</p>
        </div>
      </div>

      {{-- card total petugas --}}
      <div class="bg-gradient-to-r from-pink-500 to-rose-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3 1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657 5 8 5 5 6.343 5 8s1.343 3 3 3z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total Petugas</h3>
          <p class="text-2xl font-bold">{{ $countPetugas ?? 0 }}</p>
        </div>
      </div>

      {{-- card total item --}}
      <div class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total Item</h3>
          <p class="text-2xl font-bold">{{ $countItems ?? 0 }}</p>
        </div>
      </div>

      {{-- card total lokasi --}}
      <div class="bg-gradient-to-r from-yellow-500 to-orange-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l6 6v14H6V8l6-6z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total Lokasi</h3>
          <p class="text-2xl font-bold">{{ $countLokasi ?? 0 }}</p>
        </div>
      </div>

      {{-- card total pengaduan --}}
      <div class="bg-gradient-to-r from-green-500 to-emerald-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Total Pengaduan</h3>
          <p class="text-2xl font-bold">{{ $countPengaduan ?? 0 }}</p>
        </div>
      </div>
    </div>

    {{-- Tabel Pengaduan Terbaru --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="p-4 font-semibold text-gray-700 border-b">Pengaduan Terbaru</div>
      <table class="w-full border-collapse">
        <thead class="bg-purple-100">
          <tr>
            <th class="text-left p-3">User</th>
            <th class="text-left p-3">Item</th>
            <th class="text-left p-3">Deskripsi</th>
            <th class="text-left p-3">Status</th>
            <th class="text-left p-3">Tanggal</th>
            <th class="text-left p-3">Bukti</th>
          </tr>
        </thead>
        <tbody>
          
          @forelse($pengaduanTerbaru as $p)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-3">{{ $p->user->username ?? '-' }}</td>
              <td class="p-3">{{ $p->item->nama_item ?? '-' }}</td>
              <td class="p-3">{{ $p->deskripsi }}</td>
              <td class="p-3">
                <span class="px-3 py-1 rounded-full text-sm
                  @if($p->status=='pending') bg-yellow-100 text-yellow-700
                  @elseif($p->status=='proses') bg-blue-100 text-blue-700
                  @else bg-green-100 text-green-700 @endif">
                  {{ ucfirst($p->status) }}
                </span>
              </td>
              <td class="p-3 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
              <td class="p-3">
                @if($p->foto)
                  <a href="{{ asset('storage/'.$p->foto) }}" target="_blank" class="text-purple-600 hover:underline">Lihat</a>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
            </tr>
            @if($p->status === 'selesai')
  <form action="{{ route('admin.pengaduan.destroy',$p->id_pengaduan) }}" 
        method="POST" 
        onsubmit="return confirm('Yakin hapus pengaduan ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" 
            class="px-3 py-1 rounded bg-red-500 text-white text-sm hover:bg-red-600">
      Hapus
    </button>
  </form>
@endif

          @empty
            <tr>
              <td colspan="6" class="text-center p-6 text-gray-500">Belum ada pengaduan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
