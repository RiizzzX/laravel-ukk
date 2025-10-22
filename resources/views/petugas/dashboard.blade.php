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
      <div class="bg-gradient-to-r from-purple-600 to-purple-400 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Total Pengaduan</h3>
        <p class="text-2xl font-bold">{{ $totalPengaduan ?? 0 }}</p>
      </div>
      <div class="bg-gradient-to-r from-yellow-500 to-orange-400 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Pending</h3>
        <p class="text-2xl font-bold">{{ $pengaduanPending ?? 0 }}</p>
      </div>
      <div class="bg-gradient-to-r from-green-500 to-emerald-400 text-white p-6 rounded-2xl shadow">
        <h3 class="text-sm opacity-80">Selesai</h3>
        <p class="text-2xl font-bold">{{ $pengaduanSelesai ?? 0 }}</p>
      </div>
    </div>

    {{-- Daftar Pengaduan --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <table class="w-full border-collapse">
        <thead class="bg-purple-100">
          <tr>
            <th class="p-4 text-left font-semibold text-gray-700">Pelapor</th>
            <th class="p-4 text-left font-semibold text-gray-700">Item</th>
            <th class="p-4 text-left font-semibold text-gray-700">Lokasi</th>
            <th class="p-4 text-left font-semibold text-gray-700">Deskripsi</th>
            <th class="p-4 text-left font-semibold text-gray-700">Status</th>
            <th class="p-4 text-left font-semibold text-gray-700">Bukti Foto</th>
            <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pengaduan as $p)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-4">{{ $p->user->username ?? '-' }}</td>
              <td class="p-4">{{ $p->item->nama_item ?? '-' }}</td>
              <td class="p-4">{{ $p->lokasi->nama_lokasi ?? '-' }}</td>
              <td class="p-4">{{ $p->deskripsi }}</td>
              <td class="p-4">
                <form action="{{ route('petugas.updateStatus', $p->id_pengaduan) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <select name="status" onchange="this.form.submit()"
                          class="px-2 py-1 border rounded text-sm">
                    <option value="pending" @selected($p->status=='pending')>Pending</option>
                    <option value="proses" @selected($p->status=='proses')>Proses</option>
                    <option value="selesai" @selected($p->status=='selesai')>Selesai</option>
                  </select>
                </form>
              </td>
              <td class="p-4">
                @if($p->foto)
                  <button onclick="openModal('{{ asset('storage/'.$p->foto) }}')"
                          class="text-purple-600 hover:underline">
                    Lihat Foto
                  </button>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
              <td class="p-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
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

{{-- Modal Foto --}}
<div id="fotoModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
  <div class="bg-white p-4 rounded-xl shadow max-w-2xl">
    <img id="fotoPreview" src="" class="max-h-[70vh] rounded" alt="Bukti Foto">
    <div class="text-right mt-3">
      <button onclick="closeModal()" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Tutup</button>
    </div>
  </div>
</div>

<script>
  function openModal(src) {
    document.getElementById('fotoPreview').src = src;
    document.getElementById('fotoModal').classList.remove('hidden');
    document.getElementById('fotoModal').classList.add('flex');
  }
  function closeModal() {
    document.getElementById('fotoModal').classList.add('hidden');
    document.getElementById('fotoModal').classList.remove('flex');
  }
</script>
@endsection
