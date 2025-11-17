@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Riwayat Pengaduan</h1>
      <p class="text-gray-500">Pengaduan yang telah selesai atau ditolak</p>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
      <div class="bg-gradient-to-r from-green-500 to-emerald-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Pengaduan Selesai</h3>
          <p class="text-2xl font-bold">{{ $totalSelesai ?? 0 }}</p>
        </div>
      </div>
      <div class="bg-gradient-to-r from-red-500 to-pink-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Pengaduan Ditolak</h3>
          <p class="text-2xl font-bold">{{ $totalDitolak ?? 0 }}</p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
      </div>
    @endif

    {{-- Tabel Riwayat Pengaduan --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-emerald-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pengaduan</h3>
            <p class="text-xs text-gray-500">Ditolak & Selesai ({{ $riwayat->total() }} pengaduan)</p>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-emerald-50 border-b border-emerald-100">
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">No</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Item</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Lokasi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Deskripsi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Status</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Petugas</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Tanggal</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Bukti</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($riwayat ?? [] as $index => $p)
              <tr class="hover:bg-emerald-50/30 transition">
                <td class="px-6 py-4 text-sm text-gray-700">{{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1 }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->lokasiRelation->nama_lokasi ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $p->deskripsi }}">
                  {{ Str::limit($p->deskripsi, 50) }}
                </td>
                <td class="px-6 py-4">
                  @if($p->status == 'selesai')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                      ✅ Selesai
                    </span>
                  @elseif($p->status == 'ditolak')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                      ❌ Ditolak
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  {{ $p->petugas->nama_petugas ?? '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ \Carbon\Carbon::parse($p->tgl_pengajuan ?? $p->created_at)->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                  @if($p->status == 'selesai' && $p->foto_penyelesaian)
                    <img src="{{ asset('storage/'.$p->foto_penyelesaian) }}"
                         alt="Foto Bukti Penyelesaian"
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-emerald-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto_penyelesaian) }}')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                         title="Klik untuk memperbesar">
                  @elseif($p->foto)
                    <img src="{{ asset('storage/'.$p->foto) }}"
                         alt="Foto Bukti Pengaduan"
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-blue-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto) }}')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                         title="Klik untuk memperbesar">
                  @else
                    <span class="text-xs text-gray-400 italic">Tidak ada</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  <p class="font-medium">Belum ada riwayat pengaduan</p>
                  <p class="text-sm text-gray-400 mt-1">Pengaduan yang selesai atau ditolak akan muncul di sini</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Pagination --}}
    @if($riwayat->hasPages())
      <div class="mt-6">
        {{ $riwayat->links() }}
      </div>
    @endif

  </div>
</div>

{{-- Modal untuk menampilkan foto besar --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="relative max-w-5xl max-h-full">
    <button type="button" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-all" id="closeModalBtn">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
    <img id="modalImage" src="" alt="Foto Bukti" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl">
  </div>
</div>

<script>
function showImageModal(imageSrc) {
  const modal = document.getElementById('imageModal');
  const img = document.getElementById('modalImage');
  img.src = imageSrc;
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeImageModal() {
  const modal = document.getElementById('imageModal');
  modal.classList.add('hidden');
  document.body.style.overflow = 'auto';
}

// Close button click
document.getElementById('closeModalBtn')?.addEventListener('click', function(e) {
  e.stopPropagation();
  closeImageModal();
});

// Click background to close
document.getElementById('imageModal')?.addEventListener('click', function(e) {
  if (e.target === this) {
    closeImageModal();
  }
});

// Close modal dengan tombol Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeImageModal();
  }
});
</script>
@endsection
