<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::paginate(10);
        
        // Statistik Lokasi
        $totalLokasi = Lokasi::count();
        $totalPengaduanLokasi = \DB::table('pengaduan')->count();
        
        // Hitung lokasi berdasarkan field 'lokasi' di tabel pengaduan
        $lokasiStats = \DB::table('pengaduan')
            ->select('lokasi as nama_lokasi', \DB::raw('COUNT(*) as total_pengaduan'))
            ->whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->groupBy('lokasi')
            ->orderByDesc('total_pengaduan')
            ->limit(5)
            ->get()
            ->map(function($item, $index) {
                $item->id_lokasi = $index + 1; // Generate ID for display
                return $item;
            });
        
        return view('admin.lokasi.index', compact('lokasi', 'totalLokasi', 'lokasiStats', 'totalPengaduanLokasi'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'gedung' => 'nullable|string|max:255',
            'items' => 'nullable|array',
            'items.*.nama_item' => 'required_with:items|string|max:255',
            'items.*.jumlah' => 'nullable|integer|min:1',
            'items.*.kondisi' => 'nullable|string|in:Baik,Rusak Ringan,Rusak Berat'
        ]);

        // Prepare items data
        $itemsData = [];
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if (!empty($item['nama_item'])) {
                    $itemsData[] = [
                        'nama_item' => $item['nama_item'],
                        'jumlah' => $item['jumlah'] ?? 1,
                        'kondisi' => $item['kondisi'] ?? 'Baik'
                    ];
                }
            }
        }

        Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'gedung' => $request->gedung,
            'items' => $itemsData
        ]);

        return redirect()->route('admin.lokasi.index')->with('success','Lokasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'gedung' => 'nullable|string|max:255',
            'items' => 'nullable|array',
            'items.*.nama_item' => 'required_with:items|string|max:255',
            'items.*.jumlah' => 'nullable|integer|min:1',
            'items.*.kondisi' => 'nullable|string|in:Baik,Rusak Ringan,Rusak Berat'
        ]);

        // Prepare items data
        $itemsData = [];
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if (!empty($item['nama_item'])) {
                    $itemsData[] = [
                        'nama_item' => $item['nama_item'],
                        'jumlah' => $item['jumlah'] ?? 1,
                        'kondisi' => $item['kondisi'] ?? 'Baik'
                    ];
                }
            }
        }

        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'gedung' => $request->gedung,
            'items' => $itemsData
        ]);

        return redirect()->route('admin.lokasi.index')->with('success','Lokasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')->with('success','Lokasi berhasil dihapus');
    }
}
