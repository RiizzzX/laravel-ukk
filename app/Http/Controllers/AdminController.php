<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Petugas;
use App\Models\Item;
use App\Models\Lokasi;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Dashboard Statistik
    public function dashboard()
    {
        return view('admin.dashboard', [
            'countUsers'     => User::count(),
            'countPetugas'   => Petugas::count(),
            'countItems'     => Item::count(),
            'countLokasi'    => Lokasi::count(),
            'countPengaduan' => Pengaduan::count(),
        ]);
    }

    // ================== PETUGAS ==================
    public function listPetugas()
    {
        $petugas = Petugas::all();
        return view('admin.petugas.index', compact('petugas'));
    }

    public function storePetugas(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string',
            'jabatan'      => 'nullable|string',
            'username'     => 'required|string|unique:users,username',
            'password'     => 'required|min:6',
        ]);

        $petugas = Petugas::create([
            'nama_petugas' => $request->nama_petugas,
            'jabatan'      => $request->jabatan,
        ]);

        User::create([
            'username' => $request->username,
            'name'     => $request->nama_petugas,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
        ]);

        return back()->with('success', 'Petugas baru berhasil ditambahkan');
    }

    // ================== ITEM ==================
    public function listItems()
    {
        $items = Item::with('lokasi')->get();
        return view('admin.items.index', compact('items'));
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
        ]);

        Item::create($request->only('nama_item', 'id_lokasi'));

        return back()->with('success', 'Item berhasil ditambahkan');
    }

    // ================== LOKASI ==================
    public function listLokasi()
    {
        $lokasi = Lokasi::all();
        return view('admin.lokasi.index', compact('lokasi'));
    }

    public function storeLokasi(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|unique:lokasi,nama_lokasi',
        ]);

        Lokasi::create($request->only('nama_lokasi'));

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    // ================== PENGADUAN ==================
    public function listPengaduan()
    {
        $pengaduan = Pengaduan::with(['user','item','petugas'])->latest()->get();
        return view('admin.pengaduan.index', compact('pengaduan'));
    }
}
