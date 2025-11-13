<?php

/**
 * Script untuk migrasi foto dari public/uploads ke storage/app/public
 * dan update path di database
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "=== MIGRASI FOTO PENGADUAN ===\n\n";

// 1. Migrasi foto pengaduan dari public/uploads/pengaduan ke storage/app/public/pengaduan
$publicUploadsDir = public_path('uploads/pengaduan');
$targetStorageDir = storage_path('app/public/pengaduan');

if (!file_exists($publicUploadsDir)) {
    echo "❌ Folder public/uploads/pengaduan tidak ditemukan\n";
} else {
    echo "📂 Memindahkan foto dari public/uploads/pengaduan...\n";
    
    // Buat folder target jika belum ada
    if (!file_exists($targetStorageDir)) {
        mkdir($targetStorageDir, 0755, true);
        echo "✅ Folder storage/app/public/pengaduan dibuat\n";
    }
    
    $files = glob($publicUploadsDir . '/*');
    $migratedCount = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            $filename = basename($file);
            $targetFile = $targetStorageDir . '/' . $filename;
            
            // Copy file (jangan move, biar aman)
            if (copy($file, $targetFile)) {
                echo "  ✓ {$filename}\n";
                $migratedCount++;
                
                // Update database: ganti nama file jadi pengaduan/filename
                DB::table('pengaduan')
                    ->where('foto', $filename)
                    ->update(['foto' => 'pengaduan/' . $filename]);
            }
        }
    }
    
    echo "\n✅ Berhasil migrasi {$migratedCount} foto pengaduan\n\n";
}

// 2. Migrasi foto temporary items
$publicTemporaryDir = public_path('uploads/temporary');
$targetTemporaryDir = storage_path('app/public/temporary');

if (file_exists($publicTemporaryDir)) {
    echo "📂 Memindahkan foto dari public/uploads/temporary...\n";
    
    if (!file_exists($targetTemporaryDir)) {
        mkdir($targetTemporaryDir, 0755, true);
        echo "✅ Folder storage/app/public/temporary dibuat\n";
    }
    
    $files = glob($publicTemporaryDir . '/*');
    $migratedCount = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            $filename = basename($file);
            $targetFile = $targetTemporaryDir . '/' . $filename;
            
            if (copy($file, $targetFile)) {
                echo "  ✓ {$filename}\n";
                $migratedCount++;
                
                // Update database
                DB::table('temporary_item')
                    ->where('foto', $filename)
                    ->update(['foto' => 'temporary/' . $filename]);
            }
        }
    }
    
    echo "\n✅ Berhasil migrasi {$migratedCount} foto temporary items\n\n";
}

echo "=== SELESAI ===\n";
echo "Catatan: Foto lama di public/uploads/ TIDAK dihapus (untuk backup)\n";
echo "Anda bisa hapus manual setelah verifikasi semua foto tampil dengan benar.\n";
