<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Item;
use App\Models\Lokasi;
use App\Models\TemporaryItem;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    // ================== DASHBOARD ==================
    public function dashboard()
    {
        $user = Auth::user();

        // Hitung notifikasi pengaduan yang statusnya berubah (untuk user)
        $notifikasiCount = Pengaduan::where('id_user', $user->id_user)
            ->whereIn('status', ['diterima', 'diproses', 'selesai', 'ditolak'])
            ->where('is_read', false) // Pengaduan yang belum dibaca user
            ->count();

        // Pengaduan dengan status terbaru (untuk notifikasi detail)
        $notifikasiBaru = Pengaduan::with(['item', 'lokasiRelation', 'petugas'])
            ->where('id_user', $user->id_user)
            ->whereIn('status', ['diterima', 'diproses', 'selesai', 'ditolak'])
            ->where('is_read', false)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard', [
            'pengaduan'        => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                    ->where('id_user', $user->id_user)
                                    ->whereIn('status', ['pending', 'pending_item', 'diproses', 'diterima'])
                                    ->latest()
                                    ->get(),
            'riwayat'          => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                    ->where('id_user', $user->id_user)
                                    ->whereIn('status', ['ditolak', 'selesai'])
                                    ->latest()
                                    ->take(10)
                                    ->get(),
            'totalPengaduan'   => Pengaduan::where('id_user', $user->id_user)->count(),
            'pengaduanProses'  => Pengaduan::where('id_user', $user->id_user)->whereIn('status', ['diproses', 'diterima'])->count(),
            'pengaduanSelesai' => Pengaduan::where('id_user', $user->id_user)->where('status', 'selesai')->count(),
            'notifikasiCount'  => $notifikasiCount, // Badge notifikasi
            'notifikasiBaru'   => $notifikasiBaru,  // Detail notifikasi
        ]);
    }

    // ================== INDEX ==================
    public function index()
    {
        $user = Auth::user();

        return view('pengaduan.index', [
            'pengaduan'        => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                    ->where('id_user', $user->id_user)
                                    ->whereIn('status', ['pending', 'pending_item', 'diproses', 'diterima'])
                                    ->latest()
                                    ->paginate(10),
            'totalPengaduan'   => Pengaduan::where('id_user', $user->id_user)->count(),
            'pengaduanProses'  => Pengaduan::where('id_user', $user->id_user)->whereIn('status', ['diproses', 'diterima'])->count(),
            'pengaduanSelesai' => Pengaduan::where('id_user', $user->id_user)->where('status', 'selesai')->count(),
        ]);
    }

    // ================== CREATE ==================
    public function create()
    {
        $items  = Item::with('listLokasi.lokasi')->get();
        $lokasi = Lokasi::all(); 
        return view('pengaduan.create', compact('items', 'lokasi'));
    }

    // ================== STORE ==================
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'deskripsi'  => 'required|string',
            'id_lokasi'  => 'required|exists:lokasi,id_lokasi',
            'foto'       => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048', // Max 2MB dengan format tertentu
        ];

        // Jika temporary item
        if ($request->id_item === 'temporary') {
            $rules['nama_item_temporary'] = 'required|string|max:255';
        } else {
            $rules['id_item'] = 'required|exists:items,id_item';
        }

        $request->validate($rules);

        // Handle temporary item
        $itemId = $request->id_item;
        
        if ($request->id_item === 'temporary' && $request->nama_item_temporary) {
            // Create temporary item request di tabel terpisah
            $tempItem = TemporaryItem::create([
                'nama_item' => $request->nama_item_temporary,
                'deskripsi' => 'Item temporary yang diajukan oleh user',
                'id_lokasi' => $request->id_lokasi,
                'created_by' => Auth::id(),
                'status' => 'pending',
            ]);

            // Notify all admins about new temporary item request
            \App\Models\Notifikasi::notifyAllAdmins(
                'item_temporary',
                'Request Item Temporary Baru',
                'User ' . Auth::user()->username . ' mengajukan item temporary: "' . $request->nama_item_temporary . '" untuk lokasi ' . $tempItem->lokasi->nama_lokasi,
                route('admin.temporary-items.index'),
                $tempItem->id_temporary_item
            );

            // Simpan null untuk id_item, nanti diisi setelah approved
            $itemId = null;
        }

        // Upload foto
        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        // Create pengaduan
        $pengaduan = Pengaduan::create([
            'deskripsi'     => $request->deskripsi,
            'id_item'       => $itemId, // NULL jika temporary item
            'lokasi'        => $request->id_lokasi,
            'id_user'       => Auth::id(),
            'status'        => $itemId === null ? 'pending_item' : 'pending', // Status khusus untuk pending item approval
            'foto'          => $path,
            'tgl_pengajuan' => now(),
        ]);

        // Jika temporary item, simpan relasi
        if ($request->id_item === 'temporary') {
            $pengaduan->temporary_item_id = $tempItem->id_temporary_item;
            $pengaduan->save();
        }

        $message = 'Pengaduan berhasil dikirim.';
        if ($request->id_item === 'temporary') {
            $message .= ' Item temporary Anda akan ditinjau oleh admin terlebih dahulu.';
        }

        return redirect()->route('pengaduan.index')->with('success', $message);
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
            'foto'      => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048', // Max 2MB
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
            ->with(['item', 'lokasiRelation', 'petugas'])
            ->latest()
            ->paginate(10);

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

    // ================== MARK NOTIFIKASI AS READ ==================
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        
        // Ambil notifikasi dari tabel notifikasi
        $notifikasi = \App\Models\Notifikasi::where('id_user', $user->id_user)
            ->where('is_read', false)
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function($notif) {
                return [
                    'id_notifikasi' => $notif->id_notifikasi,
                    'judul' => $notif->judul,
                    'isi' => $notif->isi,
                    'is_read' => $notif->is_read,
                    'created_at' => $notif->created_at,
                    'link' => $notif->link,
                ];
            });

        return response()->json([
            'success' => true,
            'notifikasi' => $notifikasi
        ]);
    }

    public function notifikasiIndex()
    {
        $user = Auth::user();
        
        // Ambil semua notifikasi (termasuk yang sudah dibaca)
        $notifikasi = \App\Models\Notifikasi::where('id_user', $user->id_user)
            ->latest('created_at')
            ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markNotificationRead()
    {
        $user = Auth::user();
        
        // Check if specific notification ID is provided
        if (request()->has('id_notifikasi')) {
            $idNotifikasi = request()->input('id_notifikasi');
            
            \App\Models\Notifikasi::where('id_notifikasi', $idNotifikasi)
                ->where('id_user', $user->id_user)
                ->update(['is_read' => true]);
        } else {
            // Mark semua notifikasi user yang belum dibaca menjadi sudah dibaca
            \App\Models\Notifikasi::where('id_user', $user->id_user)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }
}
