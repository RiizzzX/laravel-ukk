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
            'pengaduan'        => Pengaduan::with(['user','item','lokasi'])->latest()->get(),
            'totalPengaduan'   => Pengaduan::count(),
            'pengaduanPending' => Pengaduan::where('status','pending')->count(),
            'pengaduanProses'  => Pengaduan::where('status','proses')->count(),
            'pengaduanSelesai' => Pengaduan::where('status','selesai')->count(),
        ]);
    }

    // Update status pengaduan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->status = $request->status;
        $pengaduan->save();

        return back()->with('success', 'Status pengaduan berhasil diperbarui');
    }
}
