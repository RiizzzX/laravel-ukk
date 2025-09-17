@extends('layouts.app') {{-- gunakan layout utama dengan navbar/sidebar admin --}}

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500 text-sm">Ringkasan sistem pengaduan sarana & prasarana</p>
    </div>

    <!-- Grid Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Users -->
        <div class="p-5 bg-white rounded-2xl shadow hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Pengguna</div>
                    <div class="text-xl font-semibold">{{ $countUsers }}</div>
                </div>
            </div>
        </div>

        <!-- Petugas -->
        <div class="p-5 bg-white rounded-2xl shadow hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 
                              1.343-3 3-3 3 1.343 3 3zM19 11c0 1.657-1.343 
                              3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Petugas</div>
                    <div class="text-xl font-semibold">{{ $countPetugas }}</div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="p-5 bg-white rounded-2xl shadow hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 13V6a2 2 0 00-2-2h-5V2H7v2H5a2 2 0 00-2 2v13a2 2 0 
                              002 2h5v-2h8a2 2 0 002-2v-2h-2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Items</div>
                    <div class="text-xl font-semibold">{{ $countItems }}</div>
                </div>
            </div>
        </div>

        <!-- Lokasi -->
        <div class="p-5 bg-white rounded-2xl shadow hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 
                              1.343-3 3 1.343 3 3 3zm0 0c-2.21 0-4 1.79-4 
                              4v1h8v-1c0-2.21-1.79-4-4-4z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Lokasi</div>
                    <div class="text-xl font-semibold">{{ $countLokasi }}</div>
                </div>
            </div>
        </div>

        <!-- Pengaduan -->
        <div class="p-5 bg-white rounded-2xl shadow hover:shadow-lg transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 8h10M7 16h6M5 20h14a2 2 0 002-2V6a2 
                              2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 
                              002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Pengaduan</div>
                    <div class="text-xl font-semibold">{{ $countPengaduan }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahan: Tabel pengaduan terbaru -->
    <div class="mt-10 bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-700">Pengaduan Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Item</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestPengaduan as $p)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $p->id_pengaduan }}</td>
                            <td class="px-6 py-3">{{ $p->user->name }}</td>
                            <td class="px-6 py-3">{{ $p->item->nama_item }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded text-xs
                                    @if($p->status=='pending') bg-yellow-100 text-yellow-700
                                    @elseif($p->status=='proses') bg-blue-100 text-blue-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">{{ $p->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pengaduan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
