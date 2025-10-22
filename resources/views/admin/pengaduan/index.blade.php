@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Kelola Pengaduan</h1>
      <p class="text-gray-500">Daftar semua pengaduan dari pengguna</p>
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

    {{-- Tabel Pengaduan --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      <div class="px-6 py-4 bg-purple-100 border-b border-purple-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Semua Pengaduan</h3>
            <p class="text-xs text-gray-500">Total {{ count($pengaduan) }} pengaduan</p>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-100">
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">ID</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">User</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Item</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Deskripsi</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Status (Klik untuk ubah)</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Tanggal</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Bukti</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($pengaduan as $p)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $p->id_pengaduan }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->user->username ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $p->deskripsi }}</td>
                <td class="px-6 py-4">
                  @if($p->status=='pending')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 cursor-pointer hover:bg-yellow-200 transition-all"
                          onclick="openStatusModal({{ $p->id_pengaduan }}, 'pending')"
                          title="Klik untuk ubah status">
                      ⏳ Pending
                    </span>
                  @elseif($p->status=='diproses')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 cursor-pointer hover:bg-blue-200 transition-all"
                          onclick="openStatusModal({{ $p->id_pengaduan }}, 'diproses')"
                          title="Klik untuk ubah status">
                      🔄 Diproses
                    </span>
                  @elseif($p->status=='selesai')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                      ✅ Selesai
                    </span>
                  @elseif($p->status=='ditolak')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                      ❌ Ditolak
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="px-6 py-4">
                  @if($p->foto)
                    <img src="{{ asset('storage/'.$p->foto) }}" 
                         alt="Foto Bukti" 
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-purple-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto) }}')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                         title="Klik untuk memperbesar">
                  @else
                    <span class="text-xs text-gray-400 italic">Tidak ada bukti</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                  <p class="font-medium">Belum ada pengaduan</p>
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

{{-- Modal Update Status --}}
<div id="statusModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
    <div class="p-6 border-b border-gray-200">
      <h3 class="text-xl font-bold text-gray-800">Ubah Status Pengaduan</h3>
      <p class="text-sm text-gray-500 mt-1">Pilih status baru untuk pengaduan ini</p>
    </div>
    <div class="p-6">
      <div class="space-y-3" id="statusOptions">
        <!-- Status options will be inserted here -->
      </div>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
      <button onclick="closeStatusModal()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
        Batal
      </button>
    </div>
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

  // Show status menu
  let currentPengaduanId = null;
  
  function openStatusModal(idPengaduan, currentStatus) {
    currentPengaduanId = idPengaduan;
    const modal = document.getElementById('statusModal');
    const optionsContainer = document.getElementById('statusOptions');
    
    // Clear previous options
    optionsContainer.innerHTML = '';
    
    // Define options based on current status
    const options = [];
    
    if (currentStatus === 'pending') {
      options.push(
        { label: '🔄 Diproses', value: 'diproses', bgColor: 'bg-blue-500 hover:bg-blue-600', desc: 'Pengaduan sedang ditangani' },
        { label: '✅ Selesai', value: 'selesai', bgColor: 'bg-green-500 hover:bg-green-600', desc: 'Pengaduan telah diselesaikan' },
        { label: '❌ Ditolak', value: 'ditolak', bgColor: 'bg-red-500 hover:bg-red-600', desc: 'Pengaduan ditolak' }
      );
    } else if (currentStatus === 'diproses') {
      options.push(
        { label: '✅ Selesai', value: 'selesai', bgColor: 'bg-green-500 hover:bg-green-600', desc: 'Pengaduan telah diselesaikan' },
        { label: '❌ Ditolak', value: 'ditolak', bgColor: 'bg-red-500 hover:bg-red-600', desc: 'Pengaduan ditolak' },
        { label: '⏳ Pending', value: 'pending', bgColor: 'bg-yellow-500 hover:bg-yellow-600', desc: 'Kembalikan ke pending' }
      );
    }
    
    // Create option buttons
    options.forEach(option => {
      const btn = document.createElement('button');
      btn.className = `w-full text-left p-4 ${option.bgColor} text-white rounded-xl transition-all transform hover:scale-105 shadow-md`;
      btn.innerHTML = `
        <div class="font-bold text-lg">${option.label}</div>
        <div class="text-sm opacity-90 mt-1">${option.desc}</div>
      `;
      btn.onclick = function() {
        updateStatus(option.value);
      };
      optionsContainer.appendChild(btn);
    });
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
  
  function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPengaduanId = null;
  }
  
  function updateStatus(newStatus) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/pengaduan/${currentPengaduanId}/status`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = newStatus;
    
    form.appendChild(csrfToken);
    form.appendChild(statusInput);
    document.body.appendChild(form);
    form.submit();
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
