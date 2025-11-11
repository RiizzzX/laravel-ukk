<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // Get all pengaduan for authenticated user
    public function index(Request $request)
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation', 'user'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan berhasil diambil',
            'data' => $pengaduan
        ]);
    }

    // Create new pengaduan
    public function store(Request $request)
    {
        $request->validate([
            'id_item'   => 'required|integer', // Item index dari JSON lokasi
            'lokasi'    => 'required|exists:lokasi,id_lokasi',
            'deskripsi' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        // Ambil nama item dari JSON lokasi
        $lokasi = \App\Models\Lokasi::find($request->lokasi);
        $itemName = 'Item tidak diketahui';
        
        if ($lokasi) {
            $items = is_string($lokasi->items) ? json_decode($lokasi->items, true) : $lokasi->items;
            if (is_array($items) && isset($items[$request->id_item])) {
                $itemName = $items[$request->id_item]['nama_item'] ?? 'Item tidak diketahui';
            }
        }

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        // Simpan dengan deskripsi yang include nama item
        $deskripsiLengkap = "Item: {$itemName}\n\n{$request->deskripsi}";

        $pengaduan = Pengaduan::create([
            'id_user'       => Auth::id(),
            'id_item'       => null, // Set null karena item dari JSON
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $deskripsiLengkap,
            'foto'          => $path,
            'status'        => 'pending',
            'tgl_pengajuan' => now(),
        ]);

        $pengaduan->load(['item', 'lokasiRelation']);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dibuat',
            'data' => $pengaduan
        ], 201);
    }

    // Show single pengaduan
    public function show($id)
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation', 'user'])
            ->where('id_user', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengaduan',
            'data' => $pengaduan
        ]);
    }

    // Update pengaduan (only if status is pending)
    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::where('id_user', Auth::id())
            ->where('id_pengaduan', $id)
            ->firstOrFail();

        if ($pengaduan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak dapat diubah karena sudah diproses'
            ], 403);
        }

        $request->validate([
            'id_item'   => 'nullable|exists:items,id_item',
            'lokasi'    => 'nullable|exists:lokasi,id_lokasi',
            'deskripsi' => 'nullable|string',
            'foto'      => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($request->filled('id_item')) $pengaduan->id_item = $request->id_item;
        if ($request->filled('lokasi')) $pengaduan->lokasi = $request->lokasi;
        if ($request->filled('deskripsi')) $pengaduan->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->save();
        $pengaduan->load(['item', 'lokasiRelation']);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diperbarui',
            'data' => $pengaduan
        ]);
    }

    // Delete pengaduan (only if status is pending)
    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('id_user', Auth::id())
            ->where('id_pengaduan', $id)
            ->firstOrFail();

        if ($pengaduan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak dapat dihapus karena sudah diproses'
            ], 403);
        }

        // Delete photo if exists
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dihapus'
        ]);
    }

    // Get pending pengaduan
    public function pending()
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation'])
            ->where('id_user', Auth::id())
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan pending',
            'data' => $pengaduan
        ]);
    }

    // Get diproses pengaduan
    public function diproses()
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation'])
            ->where('id_user', Auth::id())
            ->where('status', 'diproses')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan sedang diproses',
            'data' => $pengaduan
        ]);
    }

    // Get selesai pengaduan
    public function selesai()
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation'])
            ->where('id_user', Auth::id())
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan selesai',
            'data' => $pengaduan
        ]);
    }

    // Get ditolak pengaduan
    public function ditolak()
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation'])
            ->where('id_user', Auth::id())
            ->where('status', 'ditolak')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan ditolak',
            'data' => $pengaduan
        ]);
    }

    // Get riwayat (selesai + ditolak)
    public function riwayat()
    {
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation'])
            ->where('id_user', Auth::id())
            ->whereIn('status', ['selesai', 'ditolak'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data riwayat pengaduan',
            'data' => $pengaduan
        ]);
    }

    // Submit saran item baru
    public function saran(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string',
            'lokasi'    => 'required|exists:lokasi,id_lokasi',
            'alasan'    => 'required|string',
        ]);

        $pengaduan = Pengaduan::create([
            'id_user'       => Auth::id(),
            'id_item'       => null, // Saran tidak punya item existing
            'lokasi'        => $request->lokasi,
            'deskripsi'     => "SARAN ITEM: {$request->nama_item}\n\nAlasan: {$request->alasan}",
            'foto'          => null,
            'status'        => 'pending',
            'tgl_pengajuan' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saran item berhasil dikirim',
            'data' => $pengaduan
        ], 201);
    }

    // Get statistics for user dashboard
    public function statistics()
    {
        $userId = Auth::id();

        $stats = [
            'total' => Pengaduan::where('id_user', $userId)->count(),
            'pending' => Pengaduan::where('id_user', $userId)->where('status', 'pending')->count(),
            'diproses' => Pengaduan::where('id_user', $userId)->where('status', 'diproses')->count(),
            'selesai' => Pengaduan::where('id_user', $userId)->where('status', 'selesai')->count(),
            'ditolak' => Pengaduan::where('id_user', $userId)->where('status', 'ditolak')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik pengaduan',
            'data' => $stats
        ]);
    }
}

