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
                'countUsers'        => User::count(),
                'countPetugas'      => Petugas::count(),
                'countItems'        => Item::count(),
                'countLokasi'       => Lokasi::count(),
                'countPengaduan'    => Pengaduan::count(),
                'users'             => User::orderBy('created_at', 'desc')->get(),
                'petugas'           => Petugas::orderBy('created_at', 'desc')->get(),
                'items'             => Item::with('lokasi')->orderBy('created_at', 'desc')->get(),
                'lokasi'            => Lokasi::orderBy('created_at', 'desc')->get(),
                'pengaduanAktif'    => Pengaduan::with(['user','item'])
                                                ->whereIn('status', ['pending', 'diproses'])
                                                ->latest()
                                                ->get(),
                'pengaduanSelesai'  => Pengaduan::with(['user','item'])
                                                ->whereIn('status', ['selesai', 'ditolak'])
                                                ->latest()
                                                ->take(10)
                                                ->get(),
          ]);
    }

    // ================== PETUGAS ==================
    public function listPetugas()
    {
        $petugas = Petugas::all();
        return view('admin.petugas.index', compact('petugas'));
    }

    public function storePetugas(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string',
            'jabatan'      => 'nullable|string',
            'username'     => 'required|string|unique:users,username',
            'password'     => 'required|min:6',
        ]);

        // Tambahkan akun user untuk login
        $user = User::create([
            'username' => $request->username,
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

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas baru berhasil ditambahkan');
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
        $items = Item::with('lokasi')->get();
        return view('admin.items.index', compact('items'));
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
        return view('admin.lokasi.index', compact('lokasi'));
    }

    public function storeLokasi(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|unique:lokasi,nama_lokasi',
        ]);

        Lokasi::create($request->only('nama_lokasi'));

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }
// ================== UPDATE STATUS PENGADUAN ==================
public function updateStatusPengaduan(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,diproses,selesai,ditolak'
    ]);

    $pengaduan = Pengaduan::findOrFail($id);
    $pengaduan->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status pengaduan berhasil diperbarui menjadi ' . ucfirst($request->status));
}

    // ================== PENGADUAN ==================
    public function listPengaduan()
    {
        $pengaduan = Pengaduan::with(['user','item','lokasiRelation'])
            ->whereIn('status', ['pending', 'diproses'])
            ->latest()
            ->get();
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function riwayatPengaduan()
    {
        $pengaduan = Pengaduan::with(['user','item','lokasiRelation'])
            ->whereIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->get();
        return view('admin.pengaduan.riwayat', compact('pengaduan'));
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
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name'     => 'required|string',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,petugas,pengguna',
        ]);

        User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        $request->validate([
            'username' => 'required|string|unique:users,username,'.$id.',id_user',
            'name'     => 'required|string',
            'role'     => 'required|in:admin,petugas,pengguna',
            'password' => 'nullable|min:6',
        ]);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

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
    public function generateLaporan()
    {
        $pengaduan = Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        $stats = [
            'total' => $pengaduan->count(),
            'pending' => $pengaduan->where('status', 'pending')->count(),
            'diproses' => $pengaduan->where('status', 'diproses')->count(),
            'selesai' => $pengaduan->where('status', 'selesai')->count(),
        ];

        return view('admin.laporan', compact('pengaduan', 'stats'));
    }
}