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
    // Dashboard Admin: tampil ringkasan
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

    // List petugas
    public function listPetugas()
{
    $petugas = Petugas::all();
    return view('admin.petugas.index', compact('petugas'));
}


    // Simpan petugas baru (otomatis buat user dengan role petugas)
    public function storePetugas(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string',
            'jabatan' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // simpan ke tabel petugas
        $petugas = Petugas::create([
            'nama_petugas' => $request->nama_petugas,
            'jabatan' => $request->jabatan,
        ]);

        // buat akun user role=petugas
        User::create([
            'name' => $request->nama_petugas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return back()->with('success','Petugas baru berhasil ditambahkan');
    }
}
