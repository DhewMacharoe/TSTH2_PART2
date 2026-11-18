<?php

$files = [
    'app/Models/Barang.php',
    'app/Models/ObrolanPermintaan.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $content = str_replace("public function request()", "public function permintaan()", $content);
        file_put_contents($path, $content);
        echo "Reverted relation in $file\n";
    }
}
