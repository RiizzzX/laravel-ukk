<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Item;
use App\Models\Lokasi;  
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    // ================== INDEX ==================
    public function index()
    {
        $user = Auth::user();

        return view('pengaduan.index', [
            'pengaduan'        => Pengaduan::where('id_user', $user->id_user)
                                    ->whereIn('status', ['pending', 'diproses'])
                                    ->latest()
                                    ->get(),
            'totalPengaduan'   => Pengaduan::where('id_user', $user->id_user)->count(),
            'pengaduanProses'  => Pengaduan::where('id_user', $user->id_user)->where('status', 'diproses')->count(),
            'pengaduanSelesai' => Pengaduan::where('id_user', $user->id_user)->where('status', 'selesai')->count(),
        ]);
    }

    // ================== CREATE ==================
    public function create()
    {
        $items  = Item::all();
        $lokasi = Lokasi::all(); 
        return view('pengaduan.create', compact('items', 'lokasi'));
    }

    // ================== STORE ==================
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi'  => 'required|string',
            'id_item'    => 'required|exists:items,id_item',
            'id_lokasi'  => 'required|exists:lokasi,id_lokasi',
            'foto'       => 'nullable|image|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create([
            'deskripsi'     => $request->deskripsi,
            'id_item'       => $request->id_item,
            'lokasi'        => $request->id_lokasi,
            'id_user'       => Auth::id(),
            'status'        => 'pending',
            'foto'          => $path,
            'tgl_pengajuan' => now(),
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim');
    }

    // ================== EDIT ==================
    public function edit($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if ($pengaduan->status !== 'pending') {
            return redirect()->route('pengaduan.index')->with('error', 'Pengaduan tidak bisa diedit.');
        }

        $items  = Item::all();
        $lokasi = Lokasi::all();

        return view('pengaduan.edit', compact('pengaduan','items','lokasi'));
    }

    // ================== UPDATE ==================
    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if ($pengaduan->status !== 'pending') {
            return redirect()->route('pengaduan.index')->with('error','Pengaduan tidak bisa diedit.');
        }

        $request->validate([
            'deskripsi' => 'required|string',
            'id_item'   => 'required|exists:items,id_item',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'foto'      => 'nullable|image|max:5120',
        ]);

        $path = $pengaduan->foto;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->update([
            'deskripsi' => $request->deskripsi,
            'id_item'   => $request->id_item,
            'lokasi'    => $request->id_lokasi,
            'foto'      => $path,
        ]);

        return redirect()->route('pengaduan.index')->with('success','Pengaduan berhasil diperbarui.');
    }

    // ================== DESTROY ==================
    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if (!in_array($pengaduan->status, ['pending','selesai'])) {
            return redirect()->route('pengaduan.index')->with('error','Pengaduan tidak bisa dihapus.');
        }

        $pengaduan->delete();
        return redirect()->route('pengaduan.index')->with('success','Pengaduan berhasil dihapus.');
    }

    // ================== RIWAYAT ==================
    public function riwayat()
    {
        $user = Auth::user();
        
        $riwayat = Pengaduan::where('id_user', $user->id_user)
            ->whereIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->get();

        return view('pengaduan.riwayat', [
            'riwayat' => $riwayat,
            'totalSelesai' => Pengaduan::where('id_user', $user->id_user)->where('status', 'selesai')->count(),
            'totalDitolak' => Pengaduan::where('id_user', $user->id_user)->where('status', 'ditolak')->count(),
        ]);
    }

    // ================== SARAN ==================
    public function saran()
    {
        $lokasi = Lokasi::all();
        return view('pengaduan.saran', compact('lokasi'));
    }

    // ================== STORE SARAN ==================
    public function storeSaran(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'deskripsi' => 'required|string',
        ]);

        // Simpan saran sebagai pengaduan dengan status khusus atau buat tabel terpisah
        // Untuk saat ini kita simpan ke pengaduan dengan deskripsi khusus
        Pengaduan::create([
            'deskripsi'     => "SARAN ITEM: {$request->nama_item}\n\nDeskripsi: {$request->deskripsi}",
            'id_item'       => null, // Karena ini saran item baru
            'lokasi'        => $request->id_lokasi,
            'id_user'       => Auth::user()->id_user,
            'status'        => 'pending',
            'foto'          => null,
            'tgl_pengajuan' => now(),
        ]);

        return redirect()->route('pengaduan.saran')->with('success', 'Saran item berhasil dikirim.');
    }
}
