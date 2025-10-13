@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Edit Pengaduan</h1>
      <p class="text-gray-500">Perbarui informasi pengaduan Anda, hanya bisa diubah jika status masih <b>Pending</b>.</p>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-2xl shadow p-6">
      @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('pengaduan.update', $pengaduan->id_pengaduan) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Pilih Item --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Pilih Item</label>
          <select name="id_item" required class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500">
            <option value="">-- Pilih Item --</option>
            @foreach($items as $item)
              <option value="{{ $item->id_item }}" {{ $pengaduan->id_item == $item->id_item ? 'selected' : '' }}>
                {{ $item->nama_item }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Pilih Lokasi --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Pilih Lokasi</label>
          <select name="id_lokasi" required class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500">
            <option value="">-- Pilih Lokasi --</option>
            @foreach($lokasi as $l)
              <option value="{{ $l->id_lokasi }}" {{ $pengaduan->lokasi == $l->id_lokasi ? 'selected' : '' }}>
                {{ $l->nama_lokasi }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Deskripsi --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Deskripsi Pengaduan</label>
          <textarea name="deskripsi" rows="4" required
            class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500"
            placeholder="Tuliskan keluhan Anda...">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
        </div>

        {{-- Upload Foto Bukti --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Foto Bukti (opsional)</label>
          @if($pengaduan->foto)
            <div class="mb-2">
              <img src="{{ asset('storage/'.$pengaduan->foto) }}" alt="Foto lama"
                   class="w-24 h-24 object-cover rounded border">
              <p class="text-xs text-gray-500">Foto lama. Anda bisa menggantinya di bawah.</p>
            </div>
          @endif
          <input type="file" name="foto" accept="image/jpeg,image/png"
            class="mt-1 w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-purple-500">
          <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maksimal 5MB</p>
        </div>

        {{-- Tombol Submit --}}
        <div class="flex justify-center gap-3">
          <a href="{{ route('pengaduan.index') }}" class="px-6 py-3 rounded-xl border bg-gray-100 hover:bg-gray-200">
            Batal
          </a>
          <button type="submit"
            class="px-6 py-3 rounded-xl text-white font-semibold bg-purple-600 hover:bg-purple-700 shadow">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
