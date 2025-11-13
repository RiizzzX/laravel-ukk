@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Pengajuan Item/Lokasi Baru</h1>
      <p class="text-gray-500">Daftar pengajuan item atau lokasi baru yang Anda ajukan</p>
    </div>
    <a href="{{ route('temporary-items.create') }}" 
       class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl shadow-md flex items-center gap-2 transition">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      Ajukan Baru
    </a>
  </div>

  @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
      <p class="text-green-700">{{ session('success') }}</p>
    </div>
  @endif

  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Item Baru</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Lokasi Baru</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status Pengaduan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($temporaryItems as $index => $temp)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">{{ $index + 1 }}</td>
              <td class="px-6 py-4">
                @if($temp->nama_barang_baru)
                  <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                      </svg>
                      Item Baru
                    </span>
                    <span class="font-medium">{{ $temp->nama_barang_baru }}</span>
                  </div>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
              <td class="px-6 py-4">
                @if($temp->lokasi_barang_baru)
                  <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                      </svg>
                      Lokasi Baru
                    </span>
                    <span class="font-medium">{{ $temp->lokasi_barang_baru }}</span>
                  </div>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-gray-600">{{ $temp->created_at->format('d/m/Y H:i') }}</span>
              </td>
              <td class="px-6 py-4">
                @if($temp->pengaduan && $temp->pengaduan->count() > 0)
                  <div class="flex flex-col gap-1">
                    @foreach($temp->pengaduan as $p)
                      <a href="{{ route('pengaduan.show', $p->id_pengaduan) }}" 
                         class="text-sm text-purple-600 hover:text-purple-800 hover:underline">
                        {{ $p->nama_pengaduan }} 
                        <span class="text-xs px-2 py-0.5 rounded-full 
                          @if($p->status == 'pending') bg-yellow-100 text-yellow-800
                          @elseif($p->status == 'diterima') bg-blue-100 text-blue-800
                          @elseif($p->status == 'diproses') bg-orange-100 text-orange-800
                          @elseif($p->status == 'selesai') bg-green-100 text-green-800
                          @else bg-red-100 text-red-800
                          @endif">
                          {{ ucfirst($p->status) }}
                        </span>
                      </a>
                    @endforeach
                  </div>
                @else
                  <span class="text-gray-400 text-sm">Belum ada pengaduan</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center gap-3">
                  <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                  </svg>
                  <p class="text-gray-500">Belum ada pengajuan item/lokasi baru</p>
                  <a href="{{ route('temporary-items.create') }}" 
                     class="mt-2 text-purple-600 hover:text-purple-800 font-medium">
                    Ajukan Sekarang →
                  </a>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    {{-- Pagination --}}
    @if($temporaryItems->hasPages())
      <div class="px-6 py-4 border-t border-gray-200">
        {{ $temporaryItems->links() }}
      </div>
    @endif
  </div>

  <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <p class="text-sm text-blue-700">
      <strong>ℹ️ Info:</strong> Pengajuan item/lokasi baru akan diproses oleh admin. 
      Setelah disetujui, Anda dapat menggunakannya saat membuat pengaduan.
    </p>
  </div>
</div>
@endsection
