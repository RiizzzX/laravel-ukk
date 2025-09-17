@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Dashboard Admin</h1>

    <div class="grid grid-cols-5 gap-4">
        <div class="p-4 bg-blue-100 rounded shadow">Users: {{ $countUsers }}</div>
        <div class="p-4 bg-green-100 rounded shadow">Petugas: {{ $countPetugas }}</div>
        <div class="p-4 bg-yellow-100 rounded shadow">Items: {{ $countItems }}</div>
        <div class="p-4 bg-purple-100 rounded shadow">Lokasi: {{ $countLokasi }}</div>
        <div class="p-4 bg-red-100 rounded shadow">Pengaduan: {{ $countPengaduan }}</div>
    </div>
</div>
@endsection
