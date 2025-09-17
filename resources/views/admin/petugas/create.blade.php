@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Dashboard Petugas</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-yellow-100 rounded shadow">Pending: {{ $countPending }}</div>
        <div class="p-4 bg-blue-100 rounded shadow">Proses: {{ $countProses }}</div>
        <div class="p-4 bg-green-100 rounded shadow">Selesai: {{ $countSelesai }}</div>
    </div>

    <div class="bg-white p-4 shadow rounded">
        <h2 class="text-xl mb-3">Daftar Pengaduan</h2>
        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">User</th>
                    <th class="p-2">Item</th>
                    <th class="p-2">Deskripsi</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengaduan as $p)
                <tr class="border-t">
                    <td class="p-2">{{ $p->user->name }}</td>
                    <td class="p-2">{{ $p->item->nama_item }}</td>
                    <td class="p-2">{{ $p->deskripsi }}</td>
                    <td class="p-2">{{ ucfirst($p->status) }}</td>
                    <td class="p-2">
                        <form action="{{ route('petugas.updateStatus',$p->id_pengaduan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="border p-1">
                                <option value="pending" {{ $p->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="proses" {{ $p->status=='proses'?'selected':'' }}>Proses</option>
                                <option value="selesai" {{ $p->status=='selesai'?'selected':'' }}>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
