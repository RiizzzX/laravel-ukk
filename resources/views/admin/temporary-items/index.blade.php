@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Temporary Items</h1>
        <p class="text-gray-500">Tinjau dan kelola item temporary yang diajukan user</p>
      </div>
    </div>

    <!-- Alert Messages -->
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
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    <!-- Stats Cards - Modern Style -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Pending Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-50 to-amber-100 shadow-lg border border-yellow-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-r from-yellow-400/10 to-transparent opacity-70"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-700 text-sm font-semibold tracking-wide uppercase">Pending</p>
                    <p class="text-3xl font-extrabold text-yellow-900 mt-1">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-500/20 rounded-full backdrop-blur-sm">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="h-2 w-full bg-yellow-200 rounded-full overflow-hidden">
                    @php
                        $totalTempItems = $stats['total'] ?? 0;
                        $pendingPercentage = $totalTempItems > 0 ? (($stats['pending'] ?? 0) / $totalTempItems) * 100 : 0;
                    @endphp
                    <div class="h-full bg-yellow-500 rounded-full" style="width: {{ $pendingPercentage }}%"></div>
                </div>
                <p class="text-xs text-yellow-700 mt-1">{{ number_format($pendingPercentage, 1) }}% dari total</p>
            </div>
        </div>
    </div>

    <!-- Approved Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-green-100 shadow-lg border border-emerald-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400/10 to-transparent opacity-70"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-700 text-sm font-semibold tracking-wide uppercase">Disetujui</p>
                    <p class="text-3xl font-extrabold text-emerald-900 mt-1">{{ ($stats['total'] ?? 0) - ($stats['pending'] ?? 0) }}</p>
                </div>
                <div class="p-3 bg-emerald-500/20 rounded-full backdrop-blur-sm">
                    <svg class="w-8 h-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="h-2 w-full bg-emerald-200 rounded-full overflow-hidden">
                    @php
                        $totalTempItems = $stats['total'] ?? 0;
                        $approvedCount = $totalTempItems - ($stats['pending'] ?? 0);
                        $approvedPercentage = $totalTempItems > 0 ? ($approvedCount / $totalTempItems) * 100 : 0;
                    @endphp
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $approvedPercentage }}%"></div>
                </div>
                <p class="text-xs text-emerald-700 mt-1">{{ number_format($approvedPercentage, 1) }}% dari total</p>
            </div>
        </div>
    </div>

    <!-- Rejected Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-50 to-red-100 shadow-lg border border-rose-200 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-r from-rose-400/10 to-transparent opacity-70"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-rose-700 text-sm font-semibold tracking-wide uppercase">Ditolak</p>
                    <p class="text-3xl font-extrabold text-rose-900 mt-1">0</p>
                </div>
                <div class="p-3 bg-rose-500/20 rounded-full backdrop-blur-sm">
                    <svg class="w-8 h-8 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="h-2 w-full bg-rose-200 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500 rounded-full" style="width: 0%"></div>
                </div>
                <p class="text-xs text-rose-700 mt-1">0% dari total</p>
            </div>
        </div>
    </div>
</div>

    <!-- Temporary Items Table -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="bg-purple-50/50 border-b border-purple-100">
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">ID</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Nama Item</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Lokasi</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Foto</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Diajukan Oleh</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Tanggal</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Status</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($temporaryItems as $item)
            <tr class="hover:bg-purple-50/30 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span class="text-sm font-bold text-purple-600">#{{ $item->id_temporary }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-semibold text-gray-900">{{ $item->nama_barang_baru ?? '-' }}</div>
                @if($item->deskripsi)
                  <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->deskripsi, 50) }}</div>
                @endif
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  <span class="text-sm text-gray-700">{{ $item->lokasi_barang_baru ?? '-' }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($item->foto)
                  <img src="{{ asset('storage/' . $item->foto) }}"
                       alt="Foto Bukti"
                       class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-purple-200"
                       onclick="showImageModal('{{ asset('storage/' . $item->foto) }}')"
                       onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                       title="Klik untuk memperbesar">
                @else
                  <span class="text-xs text-gray-400 italic">Tidak ada</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  <span class="text-sm text-gray-700">{{ $item->user->username ?? '-' }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($item->status === 'pending')
                  <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    ⏳ Pending
                  </span>
                @elseif($item->status === 'approved')
                  <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    ✓ Disetujui
                  </span>
                @else
                  <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    ✗ Ditolak
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                @if($item->status === 'pending')
                  <div class="flex items-center gap-2">
                    <button onclick="approveItem({{ $item->id_temporary }})" 
                      class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg font-semibold transition-colors flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                      Setujui
                    </button>
                    <button onclick="openRejectModal({{ $item->id_temporary }}, '{{ $item->nama_barang_baru ?? 'Item' }}')" 
                      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg font-semibold transition-colors flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                      Tolak
                    </button>
                  </div>
                @else
                  <div class="text-xs text-gray-500">
                    <div class="flex items-center gap-1 mb-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <span>{{ $item->approver->username ?? '-' }}</span>
                    </div>
                    @if($item->status === 'rejected' && $item->alasan_penolakan)
                      <button onclick="showReason('{{ addslashes($item->alasan_penolakan) }}')" 
                        class="text-blue-600 hover:text-blue-800 font-medium underline">
                        Lihat Alasan
                      </button>
                    @endif
                  </div>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-400">
                  <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                  </svg>
                  <p class="text-lg font-semibold">Tidak ada temporary item</p>
                  <p class="text-sm mt-1">Belum ada item temporary yang diajukan</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $temporaryItems->links() }}
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Item Temporary</h3>
            <p class="text-sm text-gray-600 mb-4">Item: <span id="rejectItemName" class="font-semibold"></span></p>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                    <textarea name="alasan_penolakan" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Tolak Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal untuk menampilkan foto besar --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
  <div class="relative max-w-7xl max-h-full">
    <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-3xl font-bold">&times;</button>
    <img id="modalImage" src="" alt="Foto Bukti" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl">
  </div>
</div>

<script>
function approveItem(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui item ini? Item akan ditambahkan ke daftar item permanent.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/temporary-items/${id}/approve`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function openRejectModal(id, name) {
    document.getElementById('rejectItemName').textContent = name;
    document.getElementById('rejectForm').action = `/admin/temporary-items/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

function showReason(reason) {
    alert('Alasan Penolakan:\n\n' + reason);
}

function showImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Close with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
        closeImageModal();
    }
});
</script>
@endsection
