<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class PetugasController extends Controller
{
    // Dashboard petugas
    public function dashboard()
    {
        $pengaduan = Pengaduan::with(['user','item'])->latest()->get();

        return view('petugas.dashboard', [
            'pengaduan' => $pengaduan,
            'countPending' => Pengaduan::where('status','pending')->count(),
            'countProses' => Pengaduan::where('status','proses')->count(),
            'countSelesai' => Pengaduan::where('status','selesai')->count(),
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

        return back()->with('success','Status pengaduan berhasil diperbarui.');
    }
}
