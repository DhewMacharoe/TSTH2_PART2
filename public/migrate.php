<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

$files = Storage::disk('public')->files('plants');
$count = 0;
foreach ($files as $file) {
    if (!Storage::disk('filebase')->exists($file)) {
        Storage::disk('filebase')->put($file, Storage::disk('public')->get($file));
        $count++;
        echo "Berhasil memindahkan: " . $file . "<br>";
    }
}
echo "<h2>Selesai! Total file dipindahkan: $count</h2>";
echo "<p><strong>PENTING:</strong> Hapus file migrate.php ini setelah selesai!</p>";