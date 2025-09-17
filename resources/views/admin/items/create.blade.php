@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Tambah Item</h1>

    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <input type="text" name="nama_item" class="w-full border p-2 rounded mb-3" placeholder="Nama Item" required>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
