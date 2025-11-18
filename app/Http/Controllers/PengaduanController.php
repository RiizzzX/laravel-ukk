<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Item;
use App\Models\Lokasi;
use App\Models\TemporaryItem;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    // ================== DASHBOARD ==================
    public function dashboard()
    {
        $user = Auth::user();

        return view('dashboard', [
            // PENGADUAN AKTIF - Hanya milik user ini
            'pengaduan'        => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                    ->where('id_user', $user->id_user) // FILTER BY USER
                                    ->where('tipe_pengaduan', 'normal')
                                    ->whereIn('status', ['pending', 'pending_item', 'diproses', 'diterima'])
                                    ->latest()
                                    ->get(),
            // TEMPORARY ITEMS - Hanya milik user ini
            'temporaryItems'   => TemporaryItem::where('id_user', $user->id_user) // FILTER BY USER
                                    ->orderBy('created_at', 'desc')
                                    ->get(),
            // RIWAYAT - Hanya milik user ini
            'riwayat'          => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                    ->where('id_user', $user->id_user) // FILTER BY USER
                                    ->whereIn('status', ['ditolak', 'selesai'])
                                    ->latest()
                                    ->take(10)
                                    ->get(),
            // STATISTIK - Hanya milik user ini
            'totalPengaduan'   => Pengaduan::where('id_user', $user->id_user)->where('tipe_pengaduan', 'normal')->count(),
            'totalTemporary'   => TemporaryItem::where('id_user', $user->id_user)->count(),
            'pengaduanProses'  => Pengaduan::where('id_user', $user->id_user)->whereIn('status', ['diproses', 'diterima'])->count(),
            'pengaduanSelesai' => Pengaduan::where('id_user', $user->id_user)->where('status', 'selesai')->count(),
        ]);
    }

    // ================== INDEX ==================
    public function index()
    {
        $user = Auth::user();

        return view('pengaduan.index', [
            // PENGADUAN AKTIF - Hanya milik user ini
            'pengaduan'        => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                    ->where('id_user', $user->id_user) // FILTER BY USER
                                    ->where('tipe_pengaduan', 'normal')
                                    ->whereIn('status', ['pending', 'pending_item', 'diproses', 'diterima'])
                                    ->latest()
                                    ->paginate(10),
            // TEMPORARY ITEMS - Hanya milik user ini
            'temporaryItems'   => TemporaryItem::where('id_user', $user->id_user) // FILTER BY USER
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10, ['*'], 'temp_page'),
            // STATISTIK - Hanya milik user ini
            'totalPengaduan'   => Pengaduan::where('id_user', $user->id_user)->where('tipe_pengaduan', 'normal')->count(),
            'totalTemporary'   => TemporaryItem::where('id_user', $user->id_user)->count(),
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
        // Determine submission type
        $isTemporary = $request->tipe_pengaduan === 'temporary';
        
        // Build validation rules
        $rules = [
            'tipe_pengaduan' => 'required|in:normal,temporary',
            'deskripsi' => 'required|string',
        ];
        
        if ($isTemporary) {
            // Temporary mode - photo optional, at least one checkbox required
            $rules['foto'] = 'nullable|image|mimes:jpeg,jpg,png|max:2048';
            $rules['ajukan_item_baru'] = 'nullable|boolean';
            $rules['ajukan_lokasi_baru'] = 'nullable|boolean';
            $rules['nama_barang_baru'] = 'nullable|string|max:255';
            $rules['lokasi_barang_baru'] = 'nullable|string|max:255';
            
            // Custom validation: at least one checkbox must be checked
            $request->validate($rules);
            
            if (!$request->ajukan_item_baru && !$request->ajukan_lokasi_baru) {
                return back()->withErrors(['error' => 'Pilih minimal satu: Item Baru atau Lokasi Baru'])->withInput();
            }
        } else {
            // Normal mode - photo required, lokasi and item required
            $rules['foto'] = 'required|image|mimes:jpeg,jpg,png|max:2048';
            $rules['id_lokasi'] = 'required|exists:lokasi,id_lokasi';
            $rules['id_item'] = 'required|exists:items,id_item';
        }
        
        $validated = $request->validate($rules);
        
        // Handle file upload
        $filename = null;
        if ($request->hasFile('foto')) {
              $filename = $request->file('foto')->store('pengaduan', 'public');
        }
        
        if ($isTemporary) {
            // TEMPORARY MODE: Langsung masuk ke tabel temporary_item
            $temporaryItem = TemporaryItem::create([
                'id_user' => auth()->id(),
                'nama_barang_baru' => $request->ajukan_item_baru ? $request->nama_barang_baru : null,
                'lokasi_barang_baru' => $request->ajukan_lokasi_baru ? $request->lokasi_barang_baru : null,
                'deskripsi' => $request->deskripsi,
                'foto' => $filename,
                'status' => 'pending',
            ]);
            
            // Notify admins about temporary item
            Notifikasi::notifyAllAdmins(
                'temporary-item',
                '📝 Pengajuan Item/Lokasi Baru',
                'User ' . auth()->user()->name . ' mengajukan item/lokasi baru yang perlu direview.',
                route('admin.temporary-items.index'),
                $temporaryItem->id_temporary
            );
            
            return redirect()->route('pengaduan.index')->with('success', 'Pengajuan item/lokasi baru berhasil dikirim! Tunggu persetujuan admin.');
        } else {
            // NORMAL MODE: Masuk ke tabel pengaduan dengan status pending
            $pengaduan = Pengaduan::create([
                'id_user' => auth()->id(),
                'lokasi' => $request->id_lokasi,
                'id_item' => $request->id_item,
                'deskripsi' => $request->deskripsi,
                'foto' => $filename,
                'status' => 'pending',
                'tipe_pengaduan' => 'normal',
            ]);
            
            // Notify admins about new pengaduan
            Notifikasi::notifyAllAdmins(
                'pengaduan_baru',
                '🔔 Pengaduan Baru Masuk',
                'Pengaduan baru dari ' . auth()->user()->name . ': "' . substr($request->deskripsi, 0, 50) . '..."',
                route('admin.pengaduan.index'),
                $pengaduan->id_pengaduan
            );
            
            return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dibuat!');
        }
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
        
        // RIWAYAT - Hanya milik user ini (selesai + ditolak)
        $riwayat = Pengaduan::where('id_user', $user->id_user) // FILTER BY USER
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
        
        // Get total count of unread notifications
        $totalUnread = \App\Models\Notifikasi::where('id_user', $user->id_user)
            ->where('is_read', false)
            ->count();
        
        // Ambil notifikasi dari tabel notifikasi (limit 10 untuk dropdown, keduanya unread dan read)
        $notifikasi = \App\Models\Notifikasi::where('id_user', $user->id_user)
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
            'notifikasi' => $notifikasi,
            'totalUnread' => $totalUnread  // Return total count for badge
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
