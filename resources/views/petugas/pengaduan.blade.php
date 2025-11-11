@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Pengaduan Aktif</h1>
        <p class="text-gray-500">Pengaduan tersedia & sedang diproses</p>
      </div>
      <a href="{{ route('petugas.pengaduan.riwayat') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Lihat Riwayat
      </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    {{-- Tabel Pengaduan Aktif --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      <div class="px-6 py-4 bg-purple-100 border-b border-purple-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Pengaduan Aktif</h3>
            <p class="text-xs text-gray-500">Tersedia & Diproses ({{ count($pengaduan) }} pengaduan)</p>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-100">
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">No</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">User</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Item</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Lokasi</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Deskripsi</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Status</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Tanggal</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Bukti</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($pengaduan as $index => $p)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->user->username ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->lokasiRelation->nama_lokasi ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $p->deskripsi }}">
                  {{ Str::limit($p->deskripsi, 50) }}
                </td>
                <td class="px-6 py-4">
                  @if($p->status == 'diterima' && $p->id_petugas === null)
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">
                      🆕 Tersedia
                    </span>
                  @elseif($p->status == 'diproses')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                      🔄 Diproses
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4">
                  @if($p->foto)
                    <img src="{{ asset('storage/'.$p->foto) }}" 
                         alt="Foto Bukti" 
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-purple-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto) }}')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                         title="Klik untuk memperbesar">
                  @else
                    <span class="text-xs text-gray-400 italic">Tidak ada</span>
                  @endif
                </td>
                <td class="px-6 py-4">
                  @if($p->status == 'diterima' && $p->id_petugas === null)
                    <button onclick="openClaimModal({{ $p->id_pengaduan }})" 
                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold transition shadow">
                      ✅ Ambil & Proses
                    </button>
                  @elseif($p->status == 'diproses' && $p->id_petugas == $petugas->id_petugas)
                    <button onclick="openCompleteModal({{ $p->id_pengaduan }})" 
                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shadow">
                      ✅ Selesaikan
                    </button>
                  @else
                    <span class="text-xs text-gray-400 italic">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                  <p class="font-medium">Tidak ada pengaduan aktif</p>
                  <p class="text-sm text-gray-400 mt-1">Pengaduan yang tersedia atau sedang diproses akan muncul di sini</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      
      {{-- Pagination --}}
      @if($pengaduan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
          {{ $pengaduan->links() }}
        </div>
      @endif
    </div>

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

{{-- Modal Update Status --}}
<div id="claimModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
    <div class="p-6 border-b border-gray-200">
      <h3 class="text-xl font-bold text-gray-800">Ambil Pengaduan</h3>
      <p class="text-sm text-gray-500 mt-1">Anda akan mengambil dan memproses pengaduan ini</p>
    </div>
    <form id="claimForm" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="status" value="diproses">
      <div class="p-6">
        <p class="text-gray-600">Apakah Anda yakin ingin mengambil pengaduan ini? Pengaduan akan berpindah ke status "Diproses".</p>
      </div>
      <div class="p-6 border-t border-gray-200 flex gap-3">
        <button type="button" onclick="closeClaimModal()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
          Batal
        </button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
          ✅ Ya, Ambil
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Selesaikan --}}
<div id="completeModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
    <div class="p-6 border-b border-gray-200">
      <h3 class="text-xl font-bold text-gray-800">Selesaikan Pengaduan</h3>
      <p class="text-sm text-gray-500 mt-1">Tandai pengaduan sebagai selesai</p>
    </div>
    <form id="completeForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <input type="hidden" name="status" value="selesai">
      <div class="p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Upload Foto Bukti Penyelesaian <span class="text-red-500">*</span>
          </label>
          <input type="file" name="foto_penyelesaian" required accept="image/jpeg,image/png"
                 class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maksimal 5MB (Wajib)</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Penyelesaian (Opsional)</label>
          <textarea name="catatan_petugas" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tambahkan catatan tentang penyelesaian..."></textarea>
        </div>
      </div>
      <div class="p-6 border-t border-gray-200 flex gap-3">
        <button type="button" onclick="closeCompleteModal()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
          Batal
        </button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
          ✅ Selesaikan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  let currentPengaduanId = null;

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

  // Claim modal
  function openClaimModal(idPengaduan) {
    currentPengaduanId = idPengaduan;
    const modal = document.getElementById('claimModal');
    const form = document.getElementById('claimForm');
    form.action = `/petugas/pengaduan/${idPengaduan}/status`;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeClaimModal() {
    const modal = document.getElementById('claimModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPengaduanId = null;
  }

  // Complete modal
  function openCompleteModal(idPengaduan) {
    currentPengaduanId = idPengaduan;
    const modal = document.getElementById('completeModal');
    const form = document.getElementById('completeForm');
    form.action = `/petugas/pengaduan/${idPengaduan}/status`;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeCompleteModal() {
    const modal = document.getElementById('completeModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPengaduanId = null;
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
