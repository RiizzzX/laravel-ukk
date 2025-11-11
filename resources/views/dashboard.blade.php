@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Pengguna</h1>
        <p class="text-gray-500">Selamat datang, {{ auth()->user()->username }} 👋</p>
      </div>
      <div class="flex items-center gap-4">
        <!-- Simple notification bell -->
        <div class="relative">
          <button id="notifBtn" class="p-2 rounded-lg bg-white border shadow-sm hover:bg-gray-50" title="Notifikasi">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
          </button>
          @if(isset($notifikasiCount) && $notifikasiCount > 0)
            <span class="notification-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $notifikasiCount }}</span>
          @endif
        </div>

        <a href="{{ route('pengaduan.create') }}"
           class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl shadow-md flex items-center gap-2 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Buat Pengaduan
        </a>
      </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      <div class="bg-gradient-to-r from-purple-600 to-purple-400 text-white p-6 rounded-2xl shadow flex items-center gap-4">
        <div class="bg-white/20 p-3 rounded-full">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm opacity-80">Selesai</h3>
          <p class="text-2xl font-bold">{{ $pengaduanSelesai ?? 0 }}</p>
        </div>
      </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    {{-- Notifikasi singkat (simple) --}}
    @if(isset($notifikasiBaru) && $notifikasiBaru->count() > 0)
      <div id="notificationCard" class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-xl">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-bold text-sm">🔔 Notifikasi Baru ({{ $notifikasiCount }})</h4>
          <div class="flex items-center gap-2">
            <button id="markReadBtn" class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
              Tandai Sudah Dibaca
            </button>
            <a href="{{ route('pengaduan.riwayat') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
          </div>
        </div>
        <ul class="space-y-2">
          @foreach($notifikasiBaru as $notif)
            <li class="p-2 bg-white rounded shadow-sm flex items-start justify-between">
              <div class="pr-3">
                <div class="text-sm font-medium text-gray-800">{{ $notif->item->nama_item ?? 'Item' }}</div>
                <div class="text-xs text-gray-500">Status: <span class="font-semibold">{{ ucfirst($notif->status) }}</span> · {{ $notif->updated_at->diffForHumans() }}</div>
              </div>
              <div class="text-xs text-gray-400">#{{ $notif->id_pengaduan }}</div>
            </li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Tabel Pengaduan Aktif --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8 border border-gray-100">
      <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Pengaduan Aktif</h3>
            <p class="text-xs text-gray-500">Pending, Diterima & Diproses ({{ count($pengaduan ?? []) }} pengaduan)</p>
          </div>
        </div>
        <a href="{{ route('pengaduan.index') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition">
          Lihat Semua
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-50 border-b border-purple-100">
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">No</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">ID</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">Item</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">Lokasi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">Deskripsi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">Status</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">Tanggal</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-900 uppercase">Bukti</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($pengaduan ?? [] as $index => $p)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                <td class="px-6 py-4 text-sm font-mono font-bold text-purple-700">#{{ $p->id_pengaduan }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->lokasiRelation->nama_lokasi ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $p->deskripsi }}">
                  {{ Str::limit($p->deskripsi, 40) }}
                </td>
                <td class="px-6 py-4">
                  @if($p->status == 'pending')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                      ⏳ Pending
                    </span>
                  @elseif($p->status == 'diterima')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                      ✅ Diterima
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
                         alt="Foto" 
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 transition shadow-md border-2 border-purple-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto) }}')"
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                  <p class="font-medium">Belum ada pengaduan aktif</p>
                  <p class="text-sm text-gray-400 mt-1">Klik tombol "Buat Pengaduan" untuk membuat pengaduan baru</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Tabel Riwayat Pengaduan (Ringkasan) --}}
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
            <p class="text-xs text-gray-500">Ringkasan pengaduan yang selesai atau ditolak</p>
          </div>
        </div>
        <a href="{{ route('pengaduan.riwayat') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
          Lihat Semua Riwayat
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-emerald-50 border-b border-emerald-100">
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase">No</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase">Item</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase">Status</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase">Tanggal</th>
              <th class="text-center px-6 py-4 text-xs font-semibold text-emerald-700 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($riwayat ?? [] as $index => $p)
              <tr class="hover:bg-emerald-50/30 transition">
                <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-800">{{ $p->item->nama_item ?? '-' }}</span>
                    <span class="text-xs text-gray-500">📍 {{ $p->lokasiRelation->nama_lokasi ?? '-' }}</span>
                  </div>
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
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ $p->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4 text-center">
                  <a href="{{ route('pengaduan.riwayat') }}" 
                     class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  <p class="font-medium">Belum ada riwayat</p>
                  <p class="text-sm text-gray-400 mt-1">Pengaduan yang sudah diselesaikan akan muncul di sini</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
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

  document.getElementById('closeModalBtn')?.addEventListener('click', closeImageModal);
  document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeImageModal();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImageModal();
  });
</script>
<script>
  // Manual mark notifications as read (via button)
  document.addEventListener('DOMContentLoaded', function() {
    const markReadBtn = document.getElementById('markReadBtn');
    
    if (markReadBtn) {
      markReadBtn.addEventListener('click', function() {
        // Show loading state
        markReadBtn.textContent = 'Memproses...';
        markReadBtn.disabled = true;
        
        fetch("{{ route('notifikasi.markRead') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Fade out and remove notification card and badge
            const card = document.getElementById('notificationCard');
            const badge = document.querySelector('.notification-badge');
            
            if (card) {
              card.style.transition = 'opacity 0.3s';
              card.style.opacity = '0';
              setTimeout(() => card.remove(), 300);
            }
            if (badge) {
              badge.style.transition = 'opacity 0.3s';
              badge.style.opacity = '0';
              setTimeout(() => badge.remove(), 300);
            }
          }
        })
        .catch(() => {
          markReadBtn.textContent = 'Gagal';
          markReadBtn.disabled = false;
          setTimeout(() => {
            markReadBtn.textContent = 'Tandai Sudah Dibaca';
          }, 2000);
        });
      });
    }
  });
</script>
@endsection
