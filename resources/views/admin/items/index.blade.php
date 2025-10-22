@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Item</h1>
        <p class="text-gray-500">Manajemen data item inventaris</p>
      </div>
      <a href="{{ route('admin.items.create') }}"   class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white px-6 py-3 rounded-lg shadow-sm flex items-center gap-2 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah item
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
        <table class="w-full">
          <thead>
            <tr class="bg-purple-50/50 border-b border-purple-100">
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">ID</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Nama Item</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->id_item }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $item->nama_item }}</td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <a href="{{ route('admin.items.edit', $item->id_item) }}" class="px-4 py-1.5 rounded-lg bg-orange-100 text-orange-700 text-xs font-bold hover:bg-orange-200 transition">Edit</a>
                    <form action="{{ route('admin.items.destroy', $item->id_item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus item ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="px-4 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-bold hover:bg-red-200 transition">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                  <p class="font-medium">Belum ada item</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
    </div>

  </div>
</div>
@endsection
