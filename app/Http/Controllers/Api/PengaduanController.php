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
        $items  = Item::all();
        $lokasi = Lokasi::all(); // <-- wajib biar $lokasi ada di blade

        return view('pengaduan.create', compact('items', 'lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_item'   => 'required|exists:items,id_item',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'deskripsi' => 'required|string',
            'foto'      => 'nullable|image|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create([
            'id_user'       => Auth::id(),
            'id_item'       => $request->id_item,
            'id_lokasi'     => $request->id_lokasi,
            'deskripsi'     => $request->deskripsi,
            'foto'          => $path,
            'status'        => 'pending',
            'tgl_pengajuan' => now(),
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim!');
    }
}
