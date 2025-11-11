<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['listLokasi.lokasi', 'creator'])->paginate(10);
        
        // Statistik Item
        $totalItems = Item::count();
        $totalPengaduanItem = \DB::table('pengaduan')->whereNotNull('id_item')->count();
        
        // Hitung item dari field deskripsi (yang berformat "Item: xxx\n\n...")
        // Parse dari deskripsi pengaduan
        $pengaduanList = \DB::table('pengaduan')
            ->select('deskripsi')
            ->whereNotNull('deskripsi')
            ->get();
        
        $itemCounts = [];
        foreach ($pengaduanList as $p) {
            // Extract item name dari format "Item: xxx\n\n"
            if (preg_match('/^Item:\s*(.+?)(\n|$)/i', $p->deskripsi, $matches)) {
                $itemName = trim($matches[1]);
                if (!empty($itemName)) {
                    if (!isset($itemCounts[$itemName])) {
                        $itemCounts[$itemName] = 0;
                    }
                    $itemCounts[$itemName]++;
                }
            }
        }
        
        // Sort dan ambil top 5
        arsort($itemCounts);
        $itemStats = collect(array_slice($itemCounts, 0, 5, true))
            ->map(function($count, $name) {
                return (object)[
                    'id_item' => crc32($name), // Generate simple ID
                    'nama_item' => $name,
                    'total_pengaduan' => $count
                ];
            })
            ->values();
        
        return view('admin.items.index', compact('items', 'totalItems', 'itemStats', 'totalPengaduanItem'));
    }

    public function create()
    {
        $lokasi = Lokasi::all();
        return view('admin.items.create', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_lokasi' => 'nullable|exists:lokasi,id_lokasi'
        ]);

        Item::create([
            'nama_item' => $request->nama_item,
            'deskripsi' => $request->deskripsi,
            'id_lokasi' => $request->id_lokasi
        ]);

        return redirect()->route('admin.items.index')->with('success','Item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = Item::where('id_item', $id)->firstOrFail();
        $lokasi = Lokasi::all();
        return view('admin.items.edit', compact('item', 'lokasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_lokasi' => 'nullable|exists:lokasi,id_lokasi'
        ]);

        $item = Item::where('id_item', $id)->firstOrFail();
        $item->update([
            'nama_item' => $request->nama_item,
            'deskripsi' => $request->deskripsi,
            'id_lokasi' => $request->id_lokasi
        ]);

        return redirect()->route('admin.items.index')->with('success','Item berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Item::where('id_item', $id)->firstOrFail();
        
        // Notify creator if temporary item is rejected
        if ($item->is_temporary && $item->created_by) {
            \App\Models\Notifikasi::createNotification(
                $item->created_by,
                'item_rejected',
                'Item Temporary Ditolak',
                'Item temporary "' . $item->nama_item . '" yang Anda buat telah ditolak oleh admin.',
                route('pengaduan.index'),
                $item->id_item
            );
        }
        
        $item->delete();

        return redirect()->route('admin.items.index')->with('success','Item berhasil dihapus');
    }

    public function approve($id)
    {
        $item = Item::where('id_item', $id)->firstOrFail();
        
        if (!$item->is_temporary) {
            return redirect()->route('admin.items.index')->with('error', 'Item ini bukan item temporary');
        }
        
        $item->update([
            'is_approved' => true,
        ]);

        // Notify creator
        if ($item->created_by) {
            \App\Models\Notifikasi::createNotification(
                $item->created_by,
                'item_approved',
                'Item Temporary Disetujui',
                'Item temporary "' . $item->nama_item . '" yang Anda buat telah disetujui oleh admin dan sekarang tersedia untuk semua user.',
                route('pengaduan.index'),
                $item->id_item
            );
        }

        return redirect()->route('admin.items.index')->with('success', 'Item temporary berhasil disetujui');
    }

    // API: Get items by lokasi (dari JSON items di lokasi)
    public function byLokasi($id_lokasi)
    {
        $lokasi = Lokasi::find($id_lokasi);
        
        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan',
                'data' => []
            ], 404);
        }

        // Ambil items dari JSON field di lokasi
        $items = $lokasi->items ?? [];
        
        if (is_string($items)) {
            $items = json_decode($items, true) ?? [];
        }
        
        // Format items untuk dropdown
        $formattedItems = [];
        if (is_array($items)) {
            foreach ($items as $index => $item) {
                if (!empty($item['nama_item'])) {
                    $formattedItems[] = [
                        'id_item' => $index, // index sebagai ID
                        'nama_item' => $item['nama_item'],
                        'jumlah' => $item['jumlah'] ?? 1,
                        'kondisi' => $item['kondisi'] ?? 'Baik'
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $formattedItems
        ], 200);
    }
}
