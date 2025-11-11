<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemporaryItem;
use App\Models\Item;
use App\Models\Pengaduan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TemporaryItemController extends Controller
{
    /**
     * Display a listing of temporary items.
     */
    public function index()
    {
        $temporaryItems = TemporaryItem::with(['creator', 'lokasi', 'approver'])
            ->latest()
            ->paginate(20);

        $pendingCount = TemporaryItem::where('status', 'pending')->count();
        $approvedCount = TemporaryItem::where('status', 'approved')->count();
        $rejectedCount = TemporaryItem::where('status', 'rejected')->count();

        return view('admin.temporary-items.index', compact(
            'temporaryItems',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Approve temporary item dan convert ke item permanent.
     */
    public function approve(Request $request, $id)
    {
        $tempItem = TemporaryItem::findOrFail($id);

        if ($tempItem->status !== 'pending') {
            return back()->with('error', 'Item ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // 1. Buat item permanent di tabel items
            $newItem = Item::create([
                'nama_item' => $tempItem->nama_item,
                'deskripsi' => $tempItem->deskripsi ?? 'Item yang disetujui dari temporary item',
            ]);

            // 2. Tambahkan ke list_lokasi
            DB::table('list_lokasi')->insert([
                'id_item' => $newItem->id_item,
                'id_lokasi' => $tempItem->id_lokasi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Update status temporary item
            $tempItem->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // 4. Update pengaduan yang menggunakan temporary item ini
            $updatedCount = Pengaduan::where('temporary_item_id', $tempItem->id_temporary_item)
                ->where('status', 'pending_item')
                ->update([
                    'id_item' => $newItem->id_item,
                    'status' => 'pending', // Ubah dari pending_item ke pending
                ]);

            // 5. Notify user yang mengajukan
            Notifikasi::createNotification(
                $tempItem->created_by,
                'item_approved',
                'Item Temporary Disetujui',
                'Item temporary "' . $tempItem->nama_item . '" Anda telah disetujui oleh admin dan ditambahkan ke daftar item.',
                route('pengaduan.index'),
                $tempItem->id_temporary_item
            );

            DB::commit();
            return back()->with('success', 'Item temporary berhasil disetujui dan ditambahkan ke daftar item.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui item: ' . $e->getMessage());
        }
    }

    /**
     * Reject temporary item.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $tempItem = TemporaryItem::findOrFail($id);

        if ($tempItem->status !== 'pending') {
            return back()->with('error', 'Item ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // 1. Update status temporary item
            $tempItem->update([
                'status' => 'rejected',
                'alasan_penolakan' => $request->alasan_penolakan,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // 2. Update atau hapus pengaduan yang menggunakan temporary item ini
            // Opsi 1: Set status pengaduan menjadi ditolak
            Pengaduan::where('temporary_item_id', $tempItem->id_temporary_item)
                ->where('status', 'pending_item')
                ->update([
                    'status' => 'ditolak',
                ]);

            // 3. Notify user yang mengajukan
            Notifikasi::createNotification(
                $tempItem->created_by,
                'item_rejected',
                'Item Temporary Ditolak',
                'Item temporary "' . $tempItem->nama_item . '" Anda ditolak. Alasan: ' . $request->alasan_penolakan,
                route('pengaduan.index'),
                $tempItem->id_temporary_item
            );

            DB::commit();
            return back()->with('success', 'Item temporary ditolak dan user telah diberitahu.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak item: ' . $e->getMessage());
        }
    }

    /**
     * Delete temporary item (permanent delete).
     */
    public function destroy($id)
    {
        $tempItem = TemporaryItem::findOrFail($id);

        // Hapus relasi pengaduan terlebih dahulu
        Pengaduan::where('temporary_item_id', $tempItem->id_temporary_item)
            ->update(['temporary_item_id' => null]);

        $tempItem->delete();

        return back()->with('success', 'Temporary item berhasil dihapus.');
    }
}
