<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    // store pengaduan (create "todo")
    public function store(Request $request)
    {
        $request->validate([
            'id_item'   => 'required|exists:items,id_item',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'deskripsi' => 'required|string',
            'foto'      => 'nullable|image|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create([
            'id_user'       => Auth::id(), // will use id_user primary
            'id_item'       => $request->id_item,
            'id_lokasi'     => $request->id_lokasi,
            'deskripsi'     => $request->deskripsi,
            'foto'          => $path,
            'status'        => 'pending',
            'tgl_pengajuan' => now(),
        ]);

        return response()->json(['message'=>'Pengaduan diterima','data'=>$pengaduan], 201);
    }

    // history for authenticated user
    public function history(Request $request)
    {
        $user = $request->user();
        $list = Pengaduan::with(['item','lokasi'])
            ->where('id_user', $user->id_user)
            ->orderBy('created_at','desc')
            ->get();

        return response()->json($list);
    }

    // optional: show single
    public function show($id)
    {
        $p = Pengaduan::with(['item','lokasi','user'])->findOrFail($id);
        return response()->json($p);
    }
}
