<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('lokasi')->get();
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $lokasi = Lokasi::all();
        return view('admin.items.create', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'id_lokasi' => 'nullable|exists:lokasi,id_lokasi'
        ]);

        Item::create([
            'nama_item' => $request->nama_item,
            'id_lokasi' => $request->id_lokasi
        ]);

        return redirect()->route('admin.items.index')->with('success','Item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = Item::where('id_item', $id)->firstOrFail();
        $lokasi = Lokasi::all();
        return view('admin.items.edit', compact('item', 'lokasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'id_lokasi' => 'nullable|exists:lokasi,id_lokasi'
        ]);

        $item = Item::where('id_item', $id)->firstOrFail();
        $item->update([
            'nama_item' => $request->nama_item,
            'id_lokasi' => $request->id_lokasi
        ]);

        return redirect()->route('admin.items.index')->with('success','Item berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Item::where('id_item', $id)->firstOrFail();
        $item->delete();

        return redirect()->route('admin.items.index')->with('success','Item berhasil dihapus');
    }
}
