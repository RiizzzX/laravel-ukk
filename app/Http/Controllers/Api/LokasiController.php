<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    // Get all lokasi
    public function index()
    {
        $lokasi = Lokasi::orderBy('nama_lokasi', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data lokasi berhasil diambil',
            'data' => $lokasi
        ]);
    }

    // Get single lokasi with items
    public function show($id)
    {
        $lokasi = Lokasi::with('items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail lokasi',
            'data' => $lokasi
        ]);
    }
}
