@extends('layouts.app')

@section('title', 'Riwayat Pengaduan')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Pengaduan</h1>
        <p class="text-gray-600">Daftar pengaduan yang telah selesai atau ditolak</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Selesai Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Pengaduan Selesai</p>
                    <h3 class="text-4xl font-bold">{{ $totalSelesai }}</h3>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ditolak Card -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Pengaduan Ditolak</p>
                    <h3 class="text-4xl font-bold">{{ $totalDitolak }}</h3>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Riwayat Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-purple-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bukti</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($riwayat as $index => $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $p->item ? $p->item->nama_item : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $p->lokasiRelation ? $p->lokasiRelation->nama_lokasi : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="max-w-xs truncate" title="{{ $p->deskripsi }}">
                                    {{ Str::limit($p->deskripsi, 50) }}
                                </div>
                            </td>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->status == 'selesai')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @elseif($p->status == 'ditolak')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium">Belum ada riwayat pengaduan</p>
                                    <p class="text-sm text-gray-400 mt-1">Pengaduan yang selesai atau ditolak akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
