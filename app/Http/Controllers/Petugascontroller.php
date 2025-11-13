<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\Notifikasi;

class PetugasController extends Controller
{
    // Dashboard Petugas
    public function dashboard()
    {
        // Ambil data petugas yang login
        $petugas = Petugas::where('id_user', auth()->id())->first();
        
        if (!$petugas) {
            return redirect()->route('login')->with('error', 'Petugas tidak ditemukan');
        }

        // Hitung pengaduan baru untuk CARD (tetap muncul sampai ada yang diproses)
        $pengaduanBaru = Pengaduan::where('status', 'diterima')
                                  ->whereNull('id_petugas')
                                  ->count();
        
        // Hitung pengaduan baru untuk ALERT & SIDEBAR (hilang setelah buka halaman)
        $pengaduanBaruAlert = 0;
        if (!session()->has('pengaduan_viewed_' . $petugas->id_petugas)) {
            $pengaduanBaruAlert = $pengaduanBaru;
        }

        // === STATISTIK KINERJA PETUGAS (LEADERBOARD) ===
        $leaderboard = Petugas::withCount([
            'pengaduan as total_selesai' => function($query) {
                $query->where('status', 'selesai');
            },
            'pengaduan as sedang_diproses' => function($query) {
                $query->where('status', 'diproses');
            },
            'pengaduan as total_ditangani' => function($query) {
                $query->whereIn('status', ['diproses', 'selesai']);
            }
        ])
        ->withAvg('pengaduan as avg_response_time', \DB::raw('TIMESTAMPDIFF(HOUR, created_at, tanggal_selesai)'))
        ->having('total_ditangani', '>', 0)
        ->orderByDesc('total_selesai')
        ->orderByDesc('total_ditangani')
        ->take(5)
        ->get();

        // Hitung ranking petugas yang login
        $myRank = Petugas::withCount([
            'pengaduan as total_selesai' => function($query) {
                $query->where('status', 'selesai');
            }
        ])
        ->having('total_selesai', '>=', $petugas->pengaduan()->where('status', 'selesai')->count())
        ->count();

        return view('petugas.dashboard', [
            'petugas'            => $petugas,
            'totalPengaduan'     => Pengaduan::where('id_petugas', $petugas->id_petugas)->count(),
            'pengaduanBaru'      => $pengaduanBaru, // Notifikasi badge untuk pengaduan baru
            'pengaduanDiproses'  => Pengaduan::where('id_petugas', $petugas->id_petugas)
                                             ->where('status', 'diproses')
                                             ->count(),
            'pengaduanSelesai'   => Pengaduan::where('id_petugas', $petugas->id_petugas)
                                             ->where('status', 'selesai')
                                             ->count(),
            // Pengaduan yang tersedia (diterima tapi belum ada yang ambil) + yang sedang dikerjakan petugas ini
            'pengaduanAktif'     => Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                                             ->where(function($q) use ($petugas) {
                                                 // Pengaduan baru yang belum ada yang ambil
                                                 $q->where(function($query) {
                                                     $query->where('status', 'diterima')
                                                           ->whereNull('id_petugas');
                                                 })
                                                 // Atau pengaduan yang sedang dikerjakan petugas ini
                                                 ->orWhere(function($query) use ($petugas) {
                                                     $query->where('id_petugas', $petugas->id_petugas)
                                                           ->whereIn('status', ['diproses']);
                                                 });
                                             })
                                             ->latest()
                                             ->get(),
            // Riwayat (selesai oleh petugas ini)
            'pengaduanRiwayat'   => Pengaduan::with(['user', 'item', 'lokasiRelation'])
                                             ->where('id_petugas', $petugas->id_petugas)
                                             ->where('status', 'selesai')
                                             ->latest()
                                             ->take(10)
                                             ->get(),
            // Statistik Leaderboard
            'leaderboard'        => $leaderboard,
            'myRank'             => $myRank,
            'pengaduanBaruAlert' => $pengaduanBaruAlert, // Untuk alert kelap-kelip
        ]);
    }

    // Halaman daftar pengaduan (diterima & diproses)
    public function listPengaduan()
    {
        $petugas = Petugas::where('id_user', auth()->id())->first();
        
        // Mark bahwa petugas sudah buka halaman pengaduan (notif hilang)
        session()->put('pengaduan_viewed_' . $petugas->id_petugas, true);
        
        // PETUGAS: Lihat pengaduan yang tersedia (status diterima dari admin) + yang sedang dikerjakan sendiri
        // TIDAK bisa lihat pengaduan user tertentu, hanya yang sudah di-approve admin
        $pengaduan = Pengaduan::with(['user', 'item', 'lokasiRelation', 'petugas'])
                        ->where(function($q) use ($petugas) {
                            // Pengaduan baru yang tersedia untuk semua petugas (dari admin)
                            $q->where(function($query) {
                                $query->where('status', 'diterima')
                                      ->whereNull('id_petugas');
                            })
                            // Atau pengaduan yang sedang dikerjakan petugas ini
                            ->orWhere(function($query) use ($petugas) {
                                $query->where('id_petugas', $petugas->id_petugas)
                                      ->where('status', 'diproses');
                            });
                        })
                        ->latest()
                        ->paginate(10);
        
        return view('petugas.pengaduan', compact('pengaduan', 'petugas'));
    }

    // Halaman riwayat pengaduan (selesai)
    public function riwayatPengaduan()
    {
        $petugas = Petugas::where('id_user', auth()->id())->first();
        
        // PETUGAS: Hanya lihat riwayat pengaduan yang DIKERJAKAN SENDIRI (yang id_petugas = petugas ini)
        $pengaduan = Pengaduan::with(['user', 'item', 'lokasiRelation'])
                        ->where('id_petugas', $petugas->id_petugas)
                        ->where('status', 'selesai')
                        ->latest()
                        ->paginate(10);
        
        return view('petugas.riwayat', compact('pengaduan'));
    }

    // Update status pengaduan (petugas)
    public function updateStatus(Request $request, $id)
    {
        // Validasi berbeda tergantung status yang dipilih
        if ($request->status === 'selesai') {
            $request->validate([
                'status' => 'required|in:diproses,selesai',
                'catatan_petugas' => 'nullable|string',
                'foto_penyelesaian' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048', // Max 2MB
            ]);
        } else {
            $request->validate([
                'status' => 'required|in:diproses,selesai',
                'catatan_petugas' => 'nullable|string',
            ]);
        }

        $petugas = Petugas::where('id_user', auth()->id())->first();
        $pengaduan = Pengaduan::findOrFail($id);

        // Validasi berdasarkan status
        if ($pengaduan->status === 'diterima' && $pengaduan->id_petugas === null) {
            // Petugas mengambil pengaduan baru (claim)
            if ($request->status !== 'diproses') {
                return back()->with('error', 'Ambil pengaduan terlebih dahulu dengan mengubah status ke Diproses');
            }
            // Assign ke petugas ini
            $pengaduan->id_petugas = $petugas->id_petugas;
            
        } elseif ($pengaduan->status === 'diproses') {
            // Cek apakah ini pengaduan milik petugas yang login
            if ($pengaduan->id_petugas !== $petugas->id_petugas) {
                return back()->with('error', 'Pengaduan ini sedang dikerjakan petugas lain');
            }
        } else {
            return back()->with('error', 'Pengaduan tidak dapat diubah');
        }

        $pengaduan->status = $request->status;
        $pengaduan->catatan_petugas = $request->catatan_petugas;
        $pengaduan->is_read = false; // Reset notifikasi untuk user

        if ($request->status === 'selesai') {
            $pengaduan->tanggal_selesai = now();
            
            // Upload foto penyelesaian
            if ($request->hasFile('foto_penyelesaian')) {
                $path = $request->file('foto_penyelesaian')->store('penyelesaian', 'public');
                $pengaduan->foto_penyelesaian = $path;
            }
        }

        $pengaduan->save();

        // Kirim notifikasi real-time ke user
        if ($request->status === 'diproses') {
            Notifikasi::createNotification(
                $pengaduan->id_user,
                'status_update',
                '🔧 Pengaduan Sedang Diproses',
                'Pengaduan Anda "' . $pengaduan->judul_laporan . '" sedang dikerjakan oleh petugas ' . $petugas->nama_petugas,
                route('pengaduan.index'),
                $pengaduan->id_pengaduan
            );

            // Notify admin bahwa petugas telah mengambil pengaduan
            Notifikasi::notifyAllAdmins(
                'petugas_claim',
                '👤 Petugas Mengambil Pengaduan',
                'Petugas ' . $petugas->nama_petugas . ' telah mengambil pengaduan: "' . $pengaduan->judul_laporan . '"',
                route('admin.pengaduan.riwayat'),
                $pengaduan->id_pengaduan
            );

        } elseif ($request->status === 'selesai') {
            Notifikasi::createNotification(
                $pengaduan->id_user,
                'status_update',
                '✅ Pengaduan Selesai',
                'Pengaduan Anda "' . $pengaduan->judul_laporan . '" telah selesai dikerjakan.' . 
                ($request->catatan_petugas ? ' Catatan: ' . $request->catatan_petugas : ''),
                route('pengaduan.index'),
                $pengaduan->id_pengaduan
            );

            // Notify admin bahwa pengaduan telah selesai
            Notifikasi::notifyAllAdmins(
                'pengaduan_selesai',
                '✅ Pengaduan Selesai',
                'Petugas ' . $petugas->nama_petugas . ' telah menyelesaikan pengaduan: "' . $pengaduan->judul_laporan . '"',
                route('admin.pengaduan.riwayat'),
                $pengaduan->id_pengaduan
            );
        }

        $message = $request->status === 'selesai' 
            ? 'Pengaduan selesai dikerjakan' 
            : 'Pengaduan berhasil diambil dan sedang diproses';

        return back()->with('success', $message);
    }
}
