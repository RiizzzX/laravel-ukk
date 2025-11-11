<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    // Get all items
    public function index()
    {
        $items = Item::with('lokasis')
            ->orderBy('nama_item', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data item berhasil diambil',
            'data' => $items
        ]);
    }

    // Get items by lokasi
    public function byLokasi($id_lokasi)
    {
        // Items yang ada di lokasi tertentu via list_lokasi (many-to-many)
        $items = Item::whereHas('lokasis', function($query) use ($id_lokasi) {
                $query->where('list_lokasi.id_lokasi', $id_lokasi);
            })
            ->with(['lokasis' => function($query) use ($id_lokasi) {
                $query->where('list_lokasi.id_lokasi', $id_lokasi);
            }])
            ->orderBy('nama_item', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data item berdasarkan lokasi',
            'data' => $items
        ]);
    }
}
