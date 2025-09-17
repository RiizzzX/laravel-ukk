@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Buat Pengaduan Baru</h1>
    <p class="text-gray-500 mb-6">Lengkapi form berikut untuk melaporkan kerusakan / kebutuhan sarana prasarana.</p>

    @if($errors->any())
      <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf

      {{-- Pilih Item --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Pilih Item</label>
        <select name="id_item" required
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500">
          <option value="">-- Pilih Item --</option>
          @foreach($items as $item)
            <option value="{{ $item->id_item }}">{{ $item->nama_item }}</option>
          @endforeach
        </select>
      </div>

      {{-- Pilih Lokasi --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Pilih Lokasi</label>
        <select name="id_lokasi" required
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500">
          <option value="">-- Pilih Lokasi --</option>
          @foreach($lokasi as $l)
            <option value="{{ $l->id_lokasi }}">{{ $l->nama_lokasi }}</option>
          @endforeach
        </select>
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi Pengaduan</label>
        <textarea name="deskripsi" rows="4" required
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500"
          placeholder="Tuliskan keluhan atau kebutuhan sarpras dengan jelas..."></textarea>
      </div>

      {{-- Upload Foto Bukti --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Upload Foto Bukti (opsional)</label>
        <input type="file" name="foto"
          accept="image/jpeg,image/png"
          class="mt-1 w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-purple-500">
        <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maksimal 5MB</p>
      </div>

      {{-- Submit --}}
      <div class="flex justify-end">
        <a href="{{ route('pengaduan.index') }}" class="px-4 py-2 rounded-xl border mr-3">Batal</a>
        <button type="submit"
          class="px-6 py-3 rounded-xl text-white font-semibold bg-purple-600 hover:bg-purple-700 shadow">
          Kirim Pengaduan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
