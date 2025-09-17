<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Item;
use App\Models\Lokasi;  
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pengaduan.index', [
            'pengaduan'        => Pengaduan::where('id_user', $user->id_user)->latest()->get(),
            'totalPengaduan'   => Pengaduan::where('id_user', $user->id_user)->count(),
            'pengaduanProses'  => Pengaduan::where('id_user', $user->id_user)->where('status', 'proses')->count(),
            'pengaduanSelesai' => Pengaduan::where('id_user', $user->id_user)->where('status', 'selesai')->count(),
        ]);
    }

    public function create()
    {
        $items = Item::all();
        $lokasi = Lokasi::all(); // <-- tambahkan ini
        return view('pengaduan.create', compact('items', 'lokasi')); // <-- kirim $lokasi ke view
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi'  => 'required|string',
            'id_item'    => 'required|exists:items,id_item',
            'id_lokasi'  => 'required|exists:lokasi,id_lokasi', // <-- validasi lokasi
            'foto'       => 'nullable|image|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create([
            'deskripsi'     => $request->deskripsi,
            'id_item'       => $request->id_item,
            'lokasi'        => $request->id_lokasi, // <-- simpan pilihan lokasi ke kolom 'lokasi'
            'id_user'       => Auth::id(),
            'status'        => 'pending',
            'foto'          => $path,
            'tgl_pengajuan' => now(),
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim');
    }
}
