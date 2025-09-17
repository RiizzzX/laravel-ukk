@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Kelola Items</h1>

    @if(session('success'))
        <div class="p-3 bg-green-200 rounded mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Item</a>

    <table class="w-full mt-4 border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Nama Item</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr class="border-t">
                <td class="p-2">{{ $item->nama_item }}</td>
                <td class="p-2">
                    <a href="{{ route('items.edit',$item->id_item) }}" class="bg-yellow-400 px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('items.destroy',$item->id_item) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Yakin hapus?')" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
