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
    // ================== DASHBOARD ==================
    public function dashboard()
    {
          return view('admin.dashboard', [
                'countUsers'        => User::where('role', 'pengguna')->count(),
                'countPetugas'      => Petugas::whereHas('user', function($q) { $q->where('role', 'petugas'); })->count(),
                'countItems'        => Item::count(),
                'countLokasi'       => Lokasi::count(),
                'countPengaduan'    => Pengaduan::count(),
                'users'             => User::where('role', 'pengguna')->orderBy('created_at', 'desc')->get(),
                'petugas'           => Petugas::whereHas('user', function($q) { $q->where('role', 'petugas'); })->orderBy('created_at', 'desc')->get(),
                'items'             => Item::with('listLokasi.lokasi')->orderBy('created_at', 'desc')->get(),
                'lokasi'            => Lokasi::orderBy('created_at', 'desc')->get(),
                // Pengaduan yang perlu review admin (status: pending)
                'pengaduanPending'  => Pengaduan::with(['user','item'])
                                                ->where('status', 'pending')
                                                ->latest()
                                                ->get(),
                // Riwayat pengaduan (ditolak + selesai)
                'pengaduanRiwayat'  => Pengaduan::with(['user','item', 'petugas'])
                                                ->whereIn('status', ['ditolak', 'selesai'])
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
            ->get();
            
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
        $items = Item::with('listLokasi.lokasi')->get();
        
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
        $lokasi = Lokasi::all();
        
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

        $message = $request->status === 'diterima' 
            ? 'Pengaduan diterima dan tersedia untuk semua petugas' 
            : 'Pengaduan ditolak';

        return back()->with('success', $message);
    }

    // ================== PENGADUAN ==================
    public function listPengaduan()
    {
        $pengaduan = Pengaduan::with(['user','item','lokasiRelation'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function riwayatPengaduan()
    {
        $pengaduan = Pengaduan::with(['user','item','lokasiRelation','petugas'])
            ->whereIn('status', ['diterima', 'diproses', 'selesai', 'ditolak'])
            ->latest()
            ->paginate(10);
        
        // Statistik
        $totalDiterima = Pengaduan::where('status', 'diterima')->count();
        $totalDiproses = Pengaduan::where('status', 'diproses')->count();
        $totalSelesai = Pengaduan::where('status', 'selesai')->count();
        $totalDitolak = Pengaduan::where('status', 'ditolak')->count();
        
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
        
        $pengaduan = $query->orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total' => $pengaduan->count(),
            'pending' => $pengaduan->where('status', 'pending')->count(),
            'diterima' => $pengaduan->where('status', 'diterima')->count(),
            'ditolak' => $pengaduan->where('status', 'ditolak')->count(),
            'diproses' => $pengaduan->where('status', 'diproses')->count(),
            'selesai' => $pengaduan->where('status', 'selesai')->count(),
        ];

        return view('admin.laporan', compact('pengaduan', 'stats'));
    }
}