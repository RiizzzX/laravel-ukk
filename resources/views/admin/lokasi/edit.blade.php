@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Edit Lokasi</h1>
      <p class="text-gray-500">Perbarui informasi lokasi</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
      <form action="{{ route('admin.lokasi.update', $lokasi->id_lokasi) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lokasi</label>
          <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition" 
                 placeholder="Masukkan nama lokasi" required>
          @error('nama_lokasi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3 pt-4">
          <button type="submit" class="flex-1 px-6 py-3 rounded-lg bg-gradient-to-r from-yellow-600 to-orange-500 text-white font-semibold hover:from-yellow-700 hover:to-orange-600 transition shadow">
            Update Lokasi
          </button>
          <a href="{{ route('admin.lokasi.index') }}" class="flex-1 px-6 py-3 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition text-center">
            Batal
          </a>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
