@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Edit Lokasi</h1>

    <form action="{{ route('lokasi.update',$lokasi->id_lokasi) }}" method="POST">
        @csrf @method('PUT')
        <input type="text" name="nama_lokasi" value="{{ $lokasi->nama_lokasi }}" class="w-full border p-2 rounded mb-3" required>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
