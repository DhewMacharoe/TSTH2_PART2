<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Memulai pembersihan cache...</h1>";

$commands = [
    'optimize:clear',
    'config:cache',
    'route:cache',
    'view:cache'
];

foreach ($commands as $command) {
    echo "<b>Menjalankan: php artisan {$command}</b><br>";
    $status = $kernel->call($command);
    echo "<pre>{$status}</pre><hr>";
}

echo "<h2>Selesai! Silakan hapus file clear-cache.php ini demi keamanan.</h2>";
?>