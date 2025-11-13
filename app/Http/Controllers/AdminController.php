<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Petugas;
use App\Models\Item;
use App\Models\Lokasi;
use App\Models\Pengaduan;
use App\Models\TemporaryItem;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ================== DASHBOARD ==================
    public function dashboard()
    {
          return view('admin.dashboard', [
                'countUsers'        => User::where('role', 'pengguna')->count(),
                'countPetugas'      => Petugas::whereHas('user', function($q) { $q->where('role', 'petugas'); })->count(),
                'countItems'        => Item::count(),
                'countLokasi'       => Lokasi::count(),
                'countPengaduan'    => Pengaduan::where('tipe_pengaduan', 'normal')->count(),
                'users'             => User::where('role', 'pengguna')->orderBy('created_at', 'desc')->get(),
                'petugas'           => Petugas::whereHas('user', function($q) { $q->where('role', 'petugas'); })->orderBy('created_at', 'desc')->get(),
                'items'             => Item::with('listLokasi.lokasi')->orderBy('created_at', 'desc')->get(),
                'lokasi'            => Lokasi::orderBy('created_at', 'desc')->get(),
                // Pengaduan yang perlu review admin (status: pending) - SEMUA USER
                'pengaduanPending'  => Pengaduan::with(['user','item'])
                                                ->where('status', 'pending')
                                                ->where('tipe_pengaduan', 'normal')
                                                ->latest()
                                                ->get(),
                // Riwayat pengaduan (ditolak + selesai) - SEMUA USER
                'pengaduanRiwayat'  => Pengaduan::with(['user','item', 'petugas'])
                                                ->whereIn('status', ['ditolak', 'selesai'])
                                                ->where('tipe_pengaduan', 'normal')
                                                ->latest()
                                                ->take(20)
                                                ->get(),
          ]);
    }

    // ================== PETUGAS ==================
    public function listPetugas()
    {
        // Filter hanya petugas, jangan tampilkan admin
        $petugas = Petugas::with(['user', 'pengaduan'])
            ->whereHas('user', function($query) {
                $query->where('role', 'petugas');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.petugas.index', compact('petugas'));
    }

    public function storePetugas(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string',
            'username'     => 'required|string|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'jabatan'      => 'nullable|string',
            'password'     => 'required|min:6',
        ]);

        // Tambahkan akun user untuk login
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'name'     => $request->nama_petugas,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
        ]);

        // Simpan ke tabel petugas dengan id_user
        Petugas::create([
            'id_user'      => $user->id_user,
            'nama_petugas' => $request->nama_petugas,
            'jabatan'      => $request->jabatan,
        ]);

        return back()->with('success', 'Petugas baru berhasil ditambahkan');
    }

    public function updatePetugas(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'required|string',
            'jabatan'      => 'nullable|string',
        ]);

        $petugas = Petugas::findOrFail($id);
        $petugas->update([
            'nama_petugas' => $request->nama_petugas,
            'jabatan'      => $request->jabatan,
        ]);

        // Update user name juga
        if ($petugas->id_user) {
            $user = User::find($petugas->id_user);
            if ($user) {
                $user->update(['name' => $request->nama_petugas]);
            }
        }

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas berhasil diupdate');
    }

    public function destroyPetugas($id)
    {
        $petugas = Petugas::findOrFail($id);
        
        // Hapus user terkait jika ada
        if ($petugas->id_user) {
            User::where('id_user', $petugas->id_user)->delete();
        }
        
        $petugas->delete();

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus');
    }

    // ================== ITEM ==================
    public function listItems()
    {
        $items = Item::with('listLokasi.lokasi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Statistik Item
        $totalItems = Item::count();
        
        // Hitung item berdasarkan jumlah pengaduan
        $itemStats = \DB::table('pengaduan')
            ->join('item', 'pengaduan.id_item', '=', 'item.id_item')
            ->select('item.id_item', 'item.nama_item', \DB::raw('COUNT(pengaduan.id_pengaduan) as total_pengaduan'))
            ->groupBy('item.id_item', 'item.nama_item')
            ->orderByDesc('total_pengaduan')
            ->limit(5)
            ->get();
        
        $totalPengaduanItem = \DB::table('pengaduan')->whereNotNull('id_item')->count();
        
        return view('admin.items.index', compact('items', 'totalItems', 'itemStats', 'totalPengaduanItem'));
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
        $lokasi = Lokasi::orderBy('nama_lokasi', 'asc')->paginate(10);
        
        // Statistik Lokasi
        $totalLokasi = Lokasi::count();
        
        // Hitung lokasi berdasarkan jumlah pengaduan
        $lokasiStats = \DB::table('pengaduan')
            ->join('item', 'pengaduan.id_item', '=', 'item.id_item')
            ->join('list_lokasi', 'item.id_item', '=', 'list_lokasi.id_item')
            ->join('lokasi', 'list_lokasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->select('lokasi.id_lokasi', 'lokasi.nama_lokasi', \DB::raw('COUNT(DISTINCT pengaduan.id_pengaduan) as total_pengaduan'))
            ->groupBy('lokasi.id_lokasi', 'lokasi.nama_lokasi')
            ->orderByDesc('total_pengaduan')
            ->limit(5)
            ->get();
        
        $totalPengaduanLokasi = \DB::table('pengaduan')->count();
        
        return view('admin.lokasi.index', compact('lokasi', 'totalLokasi', 'lokasiStats', 'totalPengaduanLokasi'));
    }

    public function storeLokasi(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|unique:lokasi,nama_lokasi',
        ]);

        Lokasi::create($request->only('nama_lokasi'));

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    // ================== UPDATE STATUS PENGADUAN (ADMIN) ==================
    public function updateStatusPengaduan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);

        // Hanya bisa update jika status masih pending
        if ($pengaduan->status !== 'pending') {
            return back()->with('error', 'Pengaduan sudah diproses sebelumnya');
        }

        $pengaduan->status = $request->status;
        $pengaduan->catatan_admin = $request->catatan_admin;
        $pengaduan->tanggal_diterima = now();
        $pengaduan->is_read = false; // Reset notifikasi untuk user

        // id_petugas akan diisi saat petugas mulai mengerjakan (status: diproses)
        
        $pengaduan->save();

        // Kirim notifikasi real-time ke user yang membuat pengaduan
        $statusText = $request->status === 'diterima' ? 'diterima' : 'ditolak';
        $emoji = $request->status === 'diterima' ? '✅' : '❌';
        
        Notifikasi::createNotification(
            $pengaduan->id_user,
            'status_update',
            $emoji . ' Pengaduan ' . ucfirst($statusText),
            'Pengaduan Anda "' . $pengaduan->judul_laporan . '" telah ' . $statusText . ' oleh admin.' . 
            ($request->catatan_admin ? ' Catatan: ' . $request->catatan_admin : ''),
            route('pengaduan.index'),
            $pengaduan->id_pengaduan
        );

        // Jika diterima, notify semua petugas bahwa ada pengaduan baru tersedia
        if ($request->status === 'diterima') {
            Notifikasi::notifyAllPetugas(
                'pengaduan_tersedia',
                '📋 Pengaduan Tersedia',
                'Pengaduan baru tersedia: "' . $pengaduan->judul_laporan . '" dari ' . $pengaduan->user->name,
                route('petugas.pengaduan.index'),
                $pengaduan->id_pengaduan
            );
        }

        $message = $request->status === 'diterima' 
            ? 'Pengaduan diterima dan tersedia untuk semua petugas' 
            : 'Pengaduan ditolak';

        return back()->with('success', $message);
    }

    // ================== PENGADUAN ==================
    public function listPengaduan()
    {
        // Admin lihat SEMUA pengaduan dengan status pending untuk direview
        $pengaduan = Pengaduan::with(['user','item','lokasiRelation'])
            ->where('status', 'pending')
            ->where('tipe_pengaduan', 'normal') // Hanya pengaduan normal, bukan temporary
            ->latest()
            ->paginate(10);
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function riwayatPengaduan()
    {
        // Admin lihat SEMUA riwayat pengaduan (sudah diproses)
        $pengaduan = Pengaduan::with(['user','item','lokasiRelation','petugas'])
            ->whereIn('status', ['diterima', 'diproses', 'selesai', 'ditolak'])
            ->where('tipe_pengaduan', 'normal') // Hanya pengaduan normal
            ->latest()
            ->paginate(10);
        
        // Statistik
        $totalDiterima = Pengaduan::where('status', 'diterima')->where('tipe_pengaduan', 'normal')->count();
        $totalDiproses = Pengaduan::where('status', 'diproses')->where('tipe_pengaduan', 'normal')->count();
        $totalSelesai = Pengaduan::where('status', 'selesai')->where('tipe_pengaduan', 'normal')->count();
        $totalDitolak = Pengaduan::where('status', 'ditolak')->where('tipe_pengaduan', 'normal')->count();
        
        return view('admin.pengaduan.riwayat', compact('pengaduan', 'totalDiterima', 'totalDiproses', 'totalSelesai', 'totalDitolak'));
    }

    public function destroyPengaduan($id)
    {
        $pengaduan = \App\Models\Pengaduan::findOrFail($id);

        // Hanya hapus jika status sudah selesai
        if ($pengaduan->status === 'selesai') {
            // kalau ada foto hapus juga dari storage
            if ($pengaduan->foto && \Storage::disk('public')->exists($pengaduan->foto)) {
                \Storage::disk('public')->delete($pengaduan->foto);
            }
            $pengaduan->delete();
            return back()->with('success', 'Pengaduan berhasil dihapus.');
        }

        return back()->with('error', 'Hanya pengaduan yang selesai yang bisa dihapus.');
    }

    // ================== USER MANAGEMENT ==================
    public function listUsers()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'name'     => 'required|string',
            'password' => 'required|min:6',
            'role'     => 'required|in:pengguna',
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'name'     => $request->name,
            'password' => Hash::make($request->password),
            'role'     => 'pengguna',
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        $request->validate([
            'username' => 'required|string|unique:users,username,'.$id.',id_user',
            'email'    => 'required|email|unique:users,email,'.$id.',id_user',
            'role'     => 'required|in:pengguna,petugas',
            'password' => 'nullable|min:6',
        ]);

        $oldRole = $user->role;
        $newRole = $request->role;

        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $newRole;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Jika role berubah dari pengguna ke petugas, buatkan record di tabel petugas
        if ($oldRole === 'pengguna' && $newRole === 'petugas') {
            // Cek apakah sudah ada record petugas untuk user ini
            $existingPetugas = Petugas::where('id_user', $user->id_user)->first();
            
            if (!$existingPetugas) {
                Petugas::create([
                    'id_user' => $user->id_user,
                    'nama_petugas' => $user->name,
                    'jabatan' => 'Staff', // Default jabatan
                ]);
            }
        }

        // Jika role berubah dari petugas ke pengguna, hapus record di tabel petugas
        if ($oldRole === 'petugas' && $newRole === 'pengguna') {
            Petugas::where('id_user', $user->id_user)->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroyUser($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        // Cegah hapus diri sendiri
        if ($user->id_user === auth()->user()->id_user) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }

    // ================== LAPORAN ==================
    public function generateLaporan(Request $request)
    {
        $query = Pengaduan::with(['user', 'item.listLokasi.lokasi', 'lokasiRelation', 'petugas']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        
        $pengaduan = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $stats = [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'diterima' => (clone $query)->where('status', 'diterima')->count(),
            'ditolak' => (clone $query)->where('status', 'ditolak')->count(),
            'diproses' => (clone $query)->where('status', 'diproses')->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];

        return view('admin.laporan', compact('pengaduan', 'stats'));
    }

    // ================== APPROVE/REJECT TEMPORARY ITEM ==================
    public function temporaryItemIndex()
    {
        $temporaryItems = TemporaryItem::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $stats = [
            'total' => TemporaryItem::count(),
            'pending' => TemporaryItem::where('status', 'pending')->count(),
            'approved' => TemporaryItem::where('status', 'approved')->count(),
            'rejected' => TemporaryItem::where('status', 'rejected')->count(),
        ];
        
        return view('admin.temporary-items.index', compact('temporaryItems', 'stats'));
    }
    
    public function approveTemporaryItem(Request $request, $id)
    {
        $temporaryItem = TemporaryItem::with('user')->findOrFail($id);
        
        // Validasi: harus ada minimal satu pengajuan
        if (!$temporaryItem->nama_barang_baru && !$temporaryItem->lokasi_barang_baru) {
            return back()->with('error', 'Temporary item tidak valid: tidak ada item atau lokasi yang diajukan.');
        }
        
        DB::beginTransaction();
        try {
            $newItem = null;
            $newLokasi = null;
            $messages = [];
            
            // Create item if nama_barang_baru exists
            if ($temporaryItem->nama_barang_baru) {
                // Check if item already exists
                $existingItem = Item::where('nama_item', $temporaryItem->nama_barang_baru)->first();
                
                if ($existingItem) {
                    $newItem = $existingItem;
                    $messages[] = 'Item "' . $newItem->nama_item . '" sudah ada (digunakan yang existing)';
                } else {
                    $newItem = Item::create([
                        'nama_item' => $temporaryItem->nama_barang_baru,
                    ]);
                    $messages[] = 'Item "' . $newItem->nama_item . '" berhasil dibuat';
                }
                
                // Update temporary_item to reference the new item
                $temporaryItem->id_item = $newItem->id_item;
            }
            
            // Create lokasi if lokasi_barang_baru exists
            if ($temporaryItem->lokasi_barang_baru) {
                // Check if lokasi already exists
                $existingLokasi = Lokasi::where('nama_lokasi', $temporaryItem->lokasi_barang_baru)->first();
                
                if ($existingLokasi) {
                    $newLokasi = $existingLokasi;
                    $messages[] = 'Lokasi "' . $newLokasi->nama_lokasi . '" sudah ada (digunakan yang existing)';
                } else {
                    $newLokasi = Lokasi::create([
                        'nama_lokasi' => $temporaryItem->lokasi_barang_baru,
                    ]);
                    $messages[] = 'Lokasi "' . $newLokasi->nama_lokasi . '" berhasil dibuat';
                }
            }
            
            // If both created, link them in list_lokasi (item_lokasi)
            if ($newItem && $newLokasi) {
                // Check if link already exists
                $existingLink = DB::table('list_lokasi')
                    ->where('id_item', $newItem->id_item)
                    ->where('id_lokasi', $newLokasi->id_lokasi)
                    ->first();
                
                if (!$existingLink) {
                    DB::table('list_lokasi')->insert([
                        'id_item' => $newItem->id_item,
                        'id_lokasi' => $newLokasi->id_lokasi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $messages[] = 'Link item-lokasi berhasil dibuat di tabel item_lokasi';
                }
            }
            
            // Update temporary item status to approved
            $temporaryItem->status = 'approved';
            $temporaryItem->save();
            
            // Tentukan id_user untuk pengaduan (fallback jika id_user null)
            $userId = $temporaryItem->id_user;
            if (!$userId && $temporaryItem->id_pengaduan) {
                $oldPengaduan = \App\Models\Pengaduan::find($temporaryItem->id_pengaduan);
                $userId = $oldPengaduan ? $oldPengaduan->id_user : null;
            }
            
            // Create pengaduan entry in riwayat (status: selesai) hanya jika ada user
            if ($userId) {
                $pengaduan = Pengaduan::create([
                    'id_user' => $userId,
                    'id_item' => $newItem ? $newItem->id_item : null,
                    'lokasi' => $newLokasi ? $newLokasi->id_lokasi : null,
                    'deskripsi' => $temporaryItem->deskripsi ?? 'Pengajuan item/lokasi baru',
                    'foto' => $temporaryItem->foto,
                    'status' => 'selesai', // Langsung masuk riwayat
                    'tipe_pengaduan' => 'temporary',
                    'temporary_item_id' => $temporaryItem->id_temporary,
                    'tanggal_diterima' => now(),
                    'tanggal_selesai' => now(),
                    'catatan_admin' => 'Item/lokasi baru disetujui dan ditambahkan ke sistem.',
                ]);
                
                // Notify user
                Notifikasi::createNotification(
                    $userId,
                    'temporary_item_approved',
                    '✅ Item/Lokasi Baru Disetujui',
                    'Pengajuan Anda telah disetujui! ' . 
                    ($newItem ? 'Item "' . $newItem->nama_item . '" ' : '') . 
                    ($newLokasi ? 'Lokasi "' . $newLokasi->nama_lokasi . '" ' : '') . 
                    'telah ditambahkan ke sistem dan pengaduan Anda telah masuk ke riwayat.',
                    route('pengaduan.riwayat'),
                    $pengaduan->id_pengaduan
                );
            }
            
            DB::commit();
            
            $successMessage = 'Temporary item berhasil disetujui! ' . implode('. ', $messages) . '. Pengaduan telah masuk ke riwayat.';
            return back()->with('success', $successMessage);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approve temporary item failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }
    
    public function rejectTemporaryItem(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);
        
        $temporaryItem = TemporaryItem::with('user')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Update temporary item status to rejected
            $temporaryItem->status = 'rejected';
            $temporaryItem->alasan_penolakan = $request->alasan_penolakan;
            $temporaryItem->save();
            
            // Notify user (hanya jika id_user ada)
            if ($temporaryItem->id_user) {
                Notifikasi::createNotification(
                    $temporaryItem->id_user,
                    'temporary_item_rejected',
                    '❌ Item/Lokasi Ditolak',
                    'Pengajuan Anda ditolak. Alasan: ' . $request->alasan_penolakan,
                    route('temporary-items.index'),
                    $temporaryItem->id_temporary
                );
            } else {
                // Fallback: cari user dari pengaduan jika ada
                if ($temporaryItem->id_pengaduan) {
                    $pengaduan = \App\Models\Pengaduan::find($temporaryItem->id_pengaduan);
                    if ($pengaduan && $pengaduan->id_user) {
                        Notifikasi::createNotification(
                            $pengaduan->id_user,
                            'temporary_item_rejected',
                            '❌ Item/Lokasi Ditolak',
                            'Pengajuan Anda ditolak. Alasan: ' . $request->alasan_penolakan,
                            route('temporary-items.index'),
                            $temporaryItem->id_temporary
                        );
                    }
                }
            }
            
            DB::commit();
            return back()->with('success', 'Temporary item ditolak dan tetap tersimpan di tabel temporary dengan status ditolak.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }

    // ================== OLD TEMPORARY ITEM METHODS (DEPRECATED) ==================
    public function approveTemporaryItemOld($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        
        if ($pengaduan->status !== 'pending_item') {
            return back()->with('error', 'Pengaduan ini bukan temporary item request.');
        }

        \DB::beginTransaction();
        try {
            // 1. Buat item permanent
            $newItem = Item::create([
                'nama_item' => $pengaduan->nama_item_temporary,
                'deskripsi' => $pengaduan->deskripsi_item_temporary ?? 'Item yang disetujui dari request user',
            ]);

            // 2. Tambahkan ke list_lokasi
            \DB::table('list_lokasi')->insert([
                'id_item' => $newItem->id_item,
                'id_lokasi' => $pengaduan->lokasi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Update pengaduan
            $pengaduan->update([
                'id_item' => $newItem->id_item,
                'status' => 'pending',
                'item_approved_at' => now(),
                'item_approved_by' => auth()->id(),
            ]);

            // 4. Notify user
            \App\Models\Notifikasi::createNotification(
                $pengaduan->id_user,
                'item_approved',
                'Item Baru Disetujui',
                'Item "' . $pengaduan->nama_item_temporary . '" telah disetujui dan pengaduan Anda akan diproses.',
                route('pengaduan.index'),
                $pengaduan->id_pengaduan
            );

            \DB::commit();
            return back()->with('success', 'Item berhasil disetujui dan ditambahkan ke database.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menyetujui item: ' . $e->getMessage());
        }
    }

    public function rejectTemporaryItemOld(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        
        if ($pengaduan->status !== 'pending_item') {
            return back()->with('error', 'Pengaduan ini bukan temporary item request.');
        }

        \DB::beginTransaction();
        try {
            // Update pengaduan
            $pengaduan->update([
                'status' => 'ditolak',
                'alasan_penolakan_item' => $request->alasan_penolakan,
                'item_approved_at' => now(),
                'item_approved_by' => auth()->id(),
            ]);

            // Notify user
            \App\Models\Notifikasi::createNotification(
                $pengaduan->id_user,
                'item_rejected',
                'Item Ditolak',
                'Item "' . $pengaduan->nama_item_temporary . '" ditolak. Alasan: ' . $request->alasan_penolakan,
                route('pengaduan.index'),
                $pengaduan->id_pengaduan
            );

            \DB::commit();
            return back()->with('success', 'Item ditolak dan user telah diberitahu.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menolak item: ' . $e->getMessage());
        }
    }
}