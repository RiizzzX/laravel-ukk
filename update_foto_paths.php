<?php

// Script untuk update path foto dari public/uploads ke storage
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "Update Foto Paths Script\n";
echo "========================================\n\n";

// 1. Update pengaduan table
echo "1. Updating pengaduan table...\n";
$pengaduanUpdated = DB::table('pengaduan')
    ->where('foto', 'NOT LIKE', 'pengaduan/%')
    ->whereNotNull('foto')
    ->get();

foreach ($pengaduanUpdated as $p) {
    $oldPath = $p->foto;
    $newPath = 'pengaduan/' . basename($oldPath);
    
    DB::table('pengaduan')
        ->where('id_pengaduan', $p->id_pengaduan)
        ->update(['foto' => $newPath]);
    
    echo "  ✓ ID {$p->id_pengaduan}: {$oldPath} → {$newPath}\n";
}

echo "  Total updated: " . count($pengaduanUpdated) . " records\n\n";

// 2. Update temporary_item table
echo "2. Updating temporary_item table...\n";
$tempUpdated = DB::table('temporary_item')
    ->where('foto', 'NOT LIKE', 'temporary/%')
    ->whereNotNull('foto')
    ->get();

foreach ($tempUpdated as $t) {
    $oldPath = $t->foto;
    
    // Check if file exists in old location
    if (file_exists(public_path('uploads/temporary/' . basename($oldPath)))) {
        // Copy to storage
        $storageDir = storage_path('app/public/temporary');
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        copy(
            public_path('uploads/temporary/' . basename($oldPath)),
            storage_path('app/public/temporary/' . basename($oldPath))
        );
    }
    
    $newPath = 'temporary/' . basename($oldPath);
    
    DB::table('temporary_item')
        ->where('id_temporary', $t->id_temporary)
        ->update(['foto' => $newPath]);
    
    echo "  ✓ ID {$t->id_temporary}: {$oldPath} → {$newPath}\n";
}

echo "  Total updated: " . count($tempUpdated) . " records\n\n";

echo "========================================\n";
echo "✓ Migration completed!\n";
echo "========================================\n";
