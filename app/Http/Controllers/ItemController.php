<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255'
        ]);

        Item::create([
            'nama_item' => $request->nama_item
        ]);

        return redirect()->route('items.index')->with('success','Item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255'
        ]);

        $item = Item::findOrFail($id);
        $item->update([
            'nama_item' => $request->nama_item
        ]);

        return redirect()->route('items.index')->with('success','Item berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')->with('success','Item berhasil dihapus');
    }
}
