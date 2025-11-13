<?php
$path = __DIR__ . '/../app/Http/Controllers/TemporaryItemController.php';
if (!file_exists($path)) {
    echo "file not found: $path\n";
    exit(1);
}
$content = file_get_contents($path);
// remove UTF-8 BOM if present
$content = preg_replace('/^\x{FEFF}/u', '', $content);
$content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
file_put_contents($path, $content);
echo "stripped BOM from $path\n";
