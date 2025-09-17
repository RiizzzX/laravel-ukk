@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Buat Pengaduan Baru</h1>

    <form action="{{ route('pengaduan.store') }}" method="POST">
        @csrf

        <label class="block mb-2">Pilih Item</label>
        <select name="id_item" class="w-full p-2 border rounded">
            @foreach($items as $item)
                <option value="{{ $item->id_item }}">{{ $item->nama_item }}</option>
            @endforeach
        </select>
        @error('id_item') <p class="text-red-500">{{ $message }}</p> @enderror

        <label class="block mt-4 mb-2">Deskripsi</label>
        <textarea name="deskripsi" rows="5" class="w-full p-2 border rounded" placeholder="Jelaskan masalah dengan jelas...">{{ old('deskripsi') }}</textarea>
        @error('deskripsi') <p class="text-red-500">{{ $message }}</p> @enderror

        <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim</button>
    </form>
</div>
@endsection
