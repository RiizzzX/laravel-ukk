<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\TemporaryItem;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // Get all pengaduan NORMAL for authenticated user
    public function index(Request $request)
    {
        // ✅ FILTER BY USER - hanya tampilkan pengaduan milik user yang login
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation', 'user'])
            ->where('id_user', Auth::id())
            ->where('tipe_pengaduan', 'normal') // Hanya pengaduan normal
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan normal berhasil diambil',
            'data' => $pengaduan
        ]);
    }

    // Create new pengaduan NORMAL
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengaduan' => 'nullable|string|max:255', // Judul pengaduan (opsional)
            'id_item'        => 'required|exists:items,id_item',
            'lokasi'         => 'required|exists:lokasi,id_lokasi',
            'deskripsi'      => 'required|string',
            'foto'           => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            // Simpan ke storage/app/public/pengaduan (sama seperti web)
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        // Create pengaduan dengan id_user dari user yang login
        $pengaduan = Pengaduan::create([
            'id_user'        => Auth::id(), // ✅ FILTER BY USER - simpan pengaduan milik user yang login
            'nama_pengaduan' => $request->nama_pengaduan ?? substr($request->deskripsi, 0, 50),
            'id_item'        => $request->id_item,
            'lokasi'         => $request->lokasi,
            'deskripsi'      => $request->deskripsi,
            'foto'           => $path,
            'status'         => 'pending',
            'tipe_pengaduan' => 'normal',
            'tgl_pengajuan'  => now(),
        ]);

        // Notify admins
        Notifikasi::notifyAllAdmins(
            'pengaduan_baru',
            '🔔 Pengaduan Baru dari Mobile',
            'Pengaduan baru dari ' . auth()->user()->name . ': "' . substr($request->deskripsi, 0, 50) . '..."',
            route('admin.pengaduan.index'),
            $pengaduan->id_pengaduan
        );

        $pengaduan->load(['item', 'lokasiRelation']);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan normal berhasil dibuat',
            'data' => $pengaduan
        ], 201);
    }

    // Show single pengaduan
    public function show($id)
    {
        // ✅ FILTER BY USER - hanya bisa lihat pengaduan milik sendiri
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
        // ✅ FILTER BY USER - hanya bisa update pengaduan milik sendiri
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
            'nama_pengaduan' => 'nullable|string|max:255',
            'id_item'        => 'nullable|exists:item,id_item',
            'lokasi'         => 'nullable|exists:lokasi,id_lokasi',
            'deskripsi'      => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($request->filled('nama_pengaduan')) $pengaduan->nama_pengaduan = $request->nama_pengaduan;
        if ($request->filled('id_item')) $pengaduan->id_item = $request->id_item;
        if ($request->filled('lokasi')) $pengaduan->lokasi = $request->lokasi;
        if ($request->filled('deskripsi')) $pengaduan->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($pengaduan->foto && \Storage::disk('public')->exists($pengaduan->foto)) {
                \Storage::disk('public')->delete($pengaduan->foto);
            }
            
            // Simpan foto baru ke storage/app/public/pengaduan
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
        // ✅ FILTER BY USER - hanya bisa hapus pengaduan milik sendiri
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
        if ($pengaduan->foto && \Storage::disk('public')->exists($pengaduan->foto)) {
            \Storage::disk('public')->delete($pengaduan->foto);
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
        // ✅ FILTER BY USER
        $pengaduan = Pengaduan::with(['item', 'lokasiRelation'])
            ->where('id_user', Auth::id())
            ->where('tipe_pengaduan', 'normal')
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
            ->where('tipe_pengaduan', 'normal')
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
            ->where('tipe_pengaduan', 'normal')
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
            ->where('tipe_pengaduan', 'normal')
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

        // Statistik pengaduan normal
        $normal = [
            'total' => Pengaduan::where('id_user', $userId)->where('tipe_pengaduan', 'normal')->count(),
            'pending' => Pengaduan::where('id_user', $userId)->where('tipe_pengaduan', 'normal')->where('status', 'pending')->count(),
            'diterima' => Pengaduan::where('id_user', $userId)->where('tipe_pengaduan', 'normal')->where('status', 'diterima')->count(),
            'diproses' => Pengaduan::where('id_user', $userId)->where('tipe_pengaduan', 'normal')->where('status', 'diproses')->count(),
            'selesai' => Pengaduan::where('id_user', $userId)->where('tipe_pengaduan', 'normal')->where('status', 'selesai')->count(),
            'ditolak' => Pengaduan::where('id_user', $userId)->where('tipe_pengaduan', 'normal')->where('status', 'ditolak')->count(),
        ];

        // Statistik temporary items
        $temporary = [
            'total' => TemporaryItem::where('id_user', $userId)->count(),
            'pending' => TemporaryItem::where('id_user', $userId)->where('status', 'pending')->count(),
            'approved' => TemporaryItem::where('id_user', $userId)->where('status', 'approved')->count(),
            'rejected' => TemporaryItem::where('id_user', $userId)->where('status', 'rejected')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik pengaduan',
            'data' => [
                'normal' => $normal,
                'temporary' => $temporary,
            ]
        ]);
    }

    // ========== TEMPORARY ITEM ENDPOINTS ==========

    // Get all temporary items for authenticated user
    public function temporaryItemIndex()
    {
        $temporaryItems = TemporaryItem::with(['user'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data temporary items berhasil diambil',
            'data' => $temporaryItems
        ]);
    }

    // Create new temporary item
    public function storeTemporaryItem(Request $request)
    {
        $request->validate([
            'nama_barang_baru' => 'required_without:lokasi_barang_baru|nullable|string|max:255',
            'lokasi_barang_baru' => 'required_without:nama_barang_baru|nullable|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ], [
            'nama_barang_baru.required_without' => 'Pilih minimal satu: Item Baru atau Lokasi Baru',
            'lokasi_barang_baru.required_without' => 'Pilih minimal satu: Item Baru atau Lokasi Baru',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            // Simpan ke storage/app/public/temporary (sama seperti web)
            $path = $request->file('foto')->store('temporary', 'public');
        }

        $temporaryItem = TemporaryItem::create([
            'id_user' => Auth::id(),
            'nama_barang_baru' => $request->nama_barang_baru,
            'lokasi_barang_baru' => $request->lokasi_barang_baru,
            'deskripsi' => $request->deskripsi,
            'foto' => $path,
            'status' => 'pending',
        ]);

        // Notify admins
        Notifikasi::notifyAllAdmins(
            'temporary-item',
            '📝 Pengajuan Item/Lokasi Baru dari Mobile',
            'User ' . auth()->user()->name . ' mengajukan item/lokasi baru yang perlu direview.',
            route('admin.temporary-items.index'),
            $temporaryItem->id_temporary
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan item/lokasi baru berhasil dikirim! Tunggu persetujuan admin.',
            'data' => $temporaryItem
        ], 201);
    }

    // Show single temporary item
    public function showTemporaryItem($id)
    {
        $temporaryItem = TemporaryItem::with(['user'])
            ->where('id_user', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail temporary item',
            'data' => $temporaryItem
        ]);
    }
}

