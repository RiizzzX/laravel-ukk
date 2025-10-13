@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Petugas</h1>
        <p class="text-gray-500">Halo, {{ auth()->user()->username }}</p>
      </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      {{-- Total Pengaduan --}}
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

      {{-- Diproses --}}
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

      {{-- Selesai --}}
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

    {{-- Tabel Pengaduan --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="p-4 font-semibold text-gray-700 border-b">Daftar Pengaduan</div>
      <table class="w-full border-collapse">
        <thead class="bg-purple-100">
          <tr>
            <th class="text-left p-3">User</th>
            <th class="text-left p-3">Item</th>
            <th class="text-left p-3">Deskripsi</th>
            <th class="text-left p-3">Status</th>
            <th class="text-left p-3">Tanggal</th>
            <th class="text-left p-3">Bukti</th>
            <th class="text-left p-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pengaduan as $p)
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
              <td class="p-3">
                <form action="{{ route('petugas.updateStatus',$p->id_pengaduan) }}" method="POST" class="flex gap-2">
                  @csrf
                  @method('PUT')
                  @if($p->status == 'pending')
                    <button type="submit" name="status" value="proses"
                      class="px-3 py-1 rounded bg-blue-500 text-white text-sm">Proses</button>
                  @elseif($p->status == 'proses')
                    <button type="submit" name="status" value="selesai"
                      class="px-3 py-1 rounded bg-green-500 text-white text-sm">Selesaikan</button>
                  @else
                    <span class="text-gray-400 text-sm">✅ Selesai</span>
                  @endif
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center p-6 text-gray-500">Belum ada pengaduan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
