<?php
define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$pages = ['/', '/galeri', '/berita', '/penelitian', '/profil-tsth2', '/layanan'];

foreach ($pages as $url) {
    $req  = Illuminate\Http\Request::create($url, 'GET');
    $resp = $kernel->handle($req);
    $code = $resp->getStatusCode();
    echo "[{$code}] {$url}";
    if ($code !== 200) {
        // Extract error message from HTML
        preg_match('/<title>(.*?)<\/title>/s', $resp->getContent(), $m);
        echo " — " . ($m[1] ?? 'Unknown error');
        // Try to get Blade/PHP error
        preg_match('/<span class="exception-message">(.*?)<\/span>/s', $resp->getContent(), $m2);
        if (!empty($m2[1])) echo "\n  MSG: " . strip_tags($m2[1]);
        preg_match('/<div class="exception-message"[^>]*>(.*?)<\/div>/s', $resp->getContent(), $m3);
        if (!empty($m3[1])) echo "\n  MSG: " . strip_tags($m3[1]);
    }
    echo PHP_EOL;
    $kernel->terminate($req, $resp);
}
