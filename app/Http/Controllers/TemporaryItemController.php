<?php

namespace App\Http\Controllers;

use App\Models\TemporaryItem;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan untuk logging jika perlu

class TemporaryItemController extends Controller
{
    // Tambahkan middleware jika diperlukan, misalnya auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar temporary items milik user saat ini.
     */
    public function index()
    {
        // Ambil temporary items yang terkait dengan pengaduan user
        // Optimasi: Gunakan whereHas saja jika relasi pengaduan tidak perlu di-load
        // Kita asumsikan relasi 'pengaduan' di TemporaryItem mengarah ke model Pengaduan
        // Jika TemporaryItem langsung terkait dengan user, struktur ini bisa dioptimalkan lebih lanjut.
        // Misalnya, jika TemporaryItem punya id_pengaduan, maka kita filter Pengaduan milik user.
        // Kueri saat ini efisien jika relasi sudah benar.
        $temporaryItems = TemporaryItem::with(['pengaduan' => function($query) {
                $query->where('id_user', Auth::id()); // Load hanya pengaduan user ini
            }])
            ->whereHas('pengaduan', function($query) { // Filter temporary item berdasarkan pengaduan user ini
                $query->where('id_user', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Anda mungkin ingin menambahkan data tambahan untuk view
        // Contoh: status approve (jika sudah disetujui/ditolak)
        // $temporaryItems->loadMissing('status'); // Jika ada relasi status

        return view('temporary-items.index', compact('temporaryItems'));
    }

    /**
     * Menampilkan formulir untuk membuat temporary item baru.
     */
    public function create()
    {
        return view('temporary-items.create');
    }

    /**
     * Menyimpan temporary item baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi: minimal salah satu harus diisi
        $validatedData = $request->validate([
            'nama_barang_baru' => 'required_without:lokasi_barang_baru|nullable|string|max:255',
            'lokasi_barang_baru' => 'required_without:nama_barang_baru|nullable|string|max:255',
        ], [
            'nama_barang_baru.required_without' => 'Pilih minimal satu: Item Baru atau Lokasi Baru.',
            'lokasi_barang_baru.required_without' => 'Pilih minimal satu: Item Baru atau Lokasi Baru.',
        ]);

        // Filter data untuk menghindari menyimpan nilai null jika tidak diisi
        $dataToCreate = array_filter([
            'nama_barang_baru' => $validatedData['nama_barang_baru'] ?? null,
            'lokasi_barang_baru' => $validatedData['lokasi_barang_baru'] ?? null,
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        // Buat temporary item hanya dengan data yang diisi
        $temporaryItem = TemporaryItem::create($dataToCreate);

        // Notifikasi admin
        $user = Auth::user();
        $message = 'User ' . ($user->username ?? 'Tidak Diketahui') . ' mengajukan: ';
        $details = [];
        if (!empty($dataToCreate['nama_barang_baru'])) {
            $details[] = 'Item baru "' . e($dataToCreate['nama_barang_baru']) . '"'; // Gunakan e() untuk mencegah XSS
        }
        if (!empty($dataToCreate['lokasi_barang_baru'])) {
            $details[] = 'Lokasi baru "' . e($dataToCreate['lokasi_barang_baru']) . '"';
        }
        $message .= implode(' dan ', $details);

        try {
            Notifikasi::notifyAllAdmins(
                'Pengajuan Item/Lokasi Baru',
                $message,
                'temporary-item',
                $temporaryItem->id_temporary // Pastikan id ini benar sesuai model Anda
            );
        } catch (\Exception $e) {
            // Log error jika notifikasi gagal, tetapi tetap lanjutkan proses
            Log::warning('Gagal mengirim notifikasi approve item: ' . $e->getMessage());
        }

        // Gunakan flash message dengan kelas CSS untuk styling
        return redirect()->route('temporary-items.index')
            ->with('success', '✅ Pengajuan berhasil dikirim! Tunggu persetujuan admin.');
    }

    // --- Potongan tambahan untuk membantu mengidentifikasi bug approve ---
    // Jika fungsi approve ada di controller ini, tambahkan di sini.
    // public function approve($id) {
    //     // Pastikan menggunakan method POST/PATCH/PUT/DELETE
    //     // Pastikan formulir atau AJAX menyertakan @csrf
    //     // Contoh:
    //     $temporaryItem = TemporaryItem::findOrFail($id);
    //     // ... logika approve ...
    //     $temporaryItem->update(['status' => 'disetujui']);
    //     return redirect()->back()->with('success', 'Item berhasil disetujui.');
    // }
    // Jika approve dilakukan di controller lain, pastikan controller tersebut juga memerlukan CSRF token yang valid.
}