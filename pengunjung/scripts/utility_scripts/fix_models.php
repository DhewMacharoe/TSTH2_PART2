<?php
$models = [
    'Konten.php' => 'konten',
    'Berita.php' => 'berita',
    'Galeri.php' => 'galeri',
    'LatarBeranda.php' => 'latar_beranda',
    'PoinTentang.php' => 'poin_tentang',
    'ObrolanPermintaan.php' => 'obrolan_permintaan',
    'BacaObrolan.php' => 'baca_obrolan',
    'ObrolanDivisi.php' => 'obrolan_divisi',
    'BacaObrolanDivisi.php' => 'baca_obrolan_divisi'
];

foreach ($models as $file => $table) {
    $path = __DIR__ . '/app/Models/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        if (strpos($content, 'protected $table') === false) {
            // Find class definition and opening brace
            $content = preg_replace('/(class\s+\w+\s+(extends\s+\w+\s+)?\{\s*(\s*use\s+[\w\\\,;\s]+;)?)/', "$1\n    protected \$table = '$table';", $content);
            file_put_contents($path, $content);
            echo "Added \$table = '$table' to $file\n";
        }
    }
}
