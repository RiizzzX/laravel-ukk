<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class PetugasController extends Controller
{
    // Dashboard Petugas
    public function dashboard()
    {
        return view('petugas.dashboard', [
            'totalPengaduan'   => Pengaduan::count(),
            'pengaduanPending' => Pengaduan::where('status','pending')->count(),
            'pengaduanProses'  => Pengaduan::where('status','diproses')->count(),
            'pengaduanSelesai' => Pengaduan::where('status','selesai')->count(),
        ]);
    }

    // Halaman daftar pengaduan (pending & diproses)
    public function listPengaduan()
    {
        $pengaduan = Pengaduan::with(['user', 'item', 'lokasiRelation'])
                        ->whereIn('status', ['pending', 'diproses'])
                        ->latest()
                        ->get();
        
        return view('petugas.pengaduan', compact('pengaduan'));
    }

    // Halaman riwayat pengaduan (selesai & ditolak)
    public function riwayatPengaduan()
    {
        $pengaduan = Pengaduan::with(['user', 'item', 'lokasiRelation'])
                        ->whereIn('status', ['selesai', 'ditolak'])
                        ->latest()
                        ->get();
        
        return view('petugas.riwayat', compact('pengaduan'));
    }

    // Update status pengaduan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->status = $request->status;
        $pengaduan->save();

        return back()->with('success', 'Status pengaduan berhasil diperbarui');
    }
}
