@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Lokasi</h1>
        <p class="text-gray-500">Manajemen data lokasi</p>
      </div>
      <a href="{{ route('admin.lokasi.create') }}" 
              class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white px-6 py-3 rounded-lg shadow-sm flex items-center gap-2 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Lokasi
      </a>
    </div>

    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-100">
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">ID</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Nama Lokasi</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($lokasi as $l)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $l->id_lokasi }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $l->nama_lokasi }}</td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <a href="{{ route('admin.lokasi.edit', $l->id_lokasi) }}" class="px-4 py-1.5 rounded-lg bg-orange-100 text-orange-700 text-xs font-bold hover:bg-orange-200 transition">Edit</a>
                    <form action="{{ route('admin.lokasi.destroy', $l->id_lokasi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus lokasi ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="px-4 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-bold hover:bg-red-200 transition">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                  <p class="font-medium">Belum ada lokasi</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
