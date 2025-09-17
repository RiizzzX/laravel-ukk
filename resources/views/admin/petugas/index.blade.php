@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Kelola Petugas</h1>

    @if(session('success'))
        <div class="p-3 bg-green-200 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.petugas.store') }}" method="POST" class="mb-6 bg-white p-4 rounded shadow">
        @csrf
        <h2 class="text-lg mb-3">Tambah Petugas Baru</h2>
        <div class="grid grid-cols-2 gap-4">
            <input type="text" name="nama_petugas" placeholder="Nama Petugas" class="border p-2 rounded" required>
            <input type="text" name="jabatan" placeholder="Jabatan" class="border p-2 rounded">
            <input type="email" name="email" placeholder="Email" class="border p-2 rounded" required>
            <input type="password" name="password" placeholder="Password" class="border p-2 rounded" required>
        </div>
        <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Tambah</button>
    </form>

    <h2 class="text-lg mb-3">Daftar Petugas</h2>
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Nama</th>
                <th class="p-2">Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($petugas as $p)
            <tr class="border-t">
                <td class="p-2">{{ $p->nama_petugas }}</td>
                <td class="p-2">{{ $p->jabatan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
