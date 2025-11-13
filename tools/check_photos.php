<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK PATH FOTO DI DATABASE ===\n\n";

$pengaduan = DB::table('pengaduan')
    ->whereNotNull('foto')
    ->select('id_pengaduan', 'foto', 'id_user', 'status')
    ->orderBy('id_pengaduan', 'desc')
    ->take(5)
    ->get();

foreach ($pengaduan as $p) {
    echo "ID: {$p->id_pengaduan} | User: {$p->id_user} | Status: {$p->status}\n";
    echo "Foto: {$p->foto}\n";
    
    // Check if file exists
    $fullPath = storage_path('app/public/' . $p->foto);
    if (file_exists($fullPath)) {
        echo "✅ File exists\n";
    } else {
        echo "❌ File NOT found at: {$fullPath}\n";
    }
    echo "\n";
}
