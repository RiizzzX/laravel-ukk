<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $pengaduan = Pengaduan::where('id_user', $user->id)->get();

        return response()->json($pengaduan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $pengaduan = Pengaduan::create([
            'id_user' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Pengaduan berhasil dibuat', 'data' => $pengaduan], 201);
    }
}
