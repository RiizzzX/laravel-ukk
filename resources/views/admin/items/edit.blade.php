@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Edit Item</h1>
      <p class="text-gray-500">Perbarui informasi item</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
      <form action="{{ route('admin.items.update', $item->id_item) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Item</label>
          <input type="text" name="nama_item" value="{{ old('nama_item', $item->nama_item) }}"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                 placeholder="Masukkan nama item" required>
          @error('nama_item')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
          <select name="id_lokasi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <option value="">Pilih Lokasi (Opsional)</option>
            @foreach($lokasi as $lok)
              <option value="{{ $lok->id_lokasi }}" {{ old('id_lokasi', $item->id_lokasi) == $lok->id_lokasi ? 'selected' : '' }}>
                {{ $lok->nama_lokasi }}
              </option>
            @endforeach
          </select>
          @error('id_lokasi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3 pt-4">
          <button type="submit" class="flex-1 px-6 py-3 rounded-lg bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold hover:from-blue-700 hover:to-cyan-600 transition shadow">
            Update Item
          </button>
          <a href="{{ route('admin.items.index') }}" class="flex-1 px-6 py-3 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition text-center">
            Batal
          </a>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
