<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    // Daftar pengaduan milik user
    public function index()
    {
        $pengaduan = Pengaduan::with('item')
                        ->where('id_user', Auth::id())
                        ->latest()
                        ->get();

        return view('pengaduan.index', compact('pengaduan'));
    }

    // Form buat pengaduan
    public function create()
    {
        $items = Item::all();
        return view('pengaduan.create', compact('items'));
    }

    // Simpan pengaduan baru
    public function store(Request $request)
    {
        $request->validate([
            'id_item' => 'required|exists:items,id_item',
            'deskripsi' => 'required|min:5',
        ]);

        Pengaduan::create([
            'id_user' => Auth::id(),
            'id_item' => $request->id_item,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        return redirect()->route('pengaduan.index')->with('success','Pengaduan berhasil dikirim!');
    }
}
