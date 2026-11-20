<?php
$filesToFixRequest = [
    'app/Providers/AppServiceProvider.php',
    'resources/views/partials/navbar.blade.php',
    'resources/views/layouts/dasbor.blade.php'
];

foreach ($filesToFixRequest as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $content = str_replace("permintaan('search')", "request('search')", $content);
        $content = str_replace('permintaan()->routeIs', 'request()->routeIs', $content);
        file_put_contents($path, $content);
        echo "Fixed request() in $file\n";
    }
}

$gudangController = __DIR__ . '/app/Http/Controllers/GudangController.php';
if (file_exists($gudangController)) {
    $content = file_get_contents($gudangController);
    $content = str_replace('Konten-Disposition', 'Content-Disposition', $content);
    $content = str_replace('Konten-Type', 'Content-Type', $content);
    file_put_contents($gudangController, $content);
    echo "Fixed headers in GudangController.php\n";
}

$userModel = __DIR__ . '/app/Models/User.php';
if (file_exists($userModel)) {
    $content = file_get_contents($userModel);
    $content = str_replace('canAccessWarehouse', 'canAccessGudang', $content);
    file_put_contents($userModel, $content);
    echo "Fixed canAccessGudang in User.php\n";
}

function replaceInDir($dir, $search, $replace) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            replaceInDir($path, $search, $replace);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $newContent = str_replace($search, $replace, $content);
            if ($content !== $newContent) {
                file_put_contents($path, $newContent);
            }
        }
    }
}

// Rename gallery.blade.php to galeri.blade.php
$galleryFiles = [
    'resources/views/manajemen_web/gallery.blade.php' => 'resources/views/manajemen_web/galeri.blade.php',
    'resources/views/halaman/gallery.blade.php' => 'resources/views/halaman/galeri.blade.php'
];

foreach ($galleryFiles as $old => $new) {
    $oldPath = __DIR__ . '/' . $old;
    $newPath = __DIR__ . '/' . $new;
    if (file_exists($oldPath)) {
        rename($oldPath, $newPath);
        echo "Renamed $old to $new\n";
    }
}

// Replace gallery occurrences globally
replaceInDir(__DIR__ . '/app', 'gallery', 'galeri');
replaceInDir(__DIR__ . '/resources/views', 'gallery', 'galeri');
replaceInDir(__DIR__ . '/routes', 'gallery', 'galeri');
replaceInDir(__DIR__ . '/app', 'Gallery', 'Galeri');
replaceInDir(__DIR__ . '/resources/views', 'Gallery', 'Galeri');
replaceInDir(__DIR__ . '/routes', 'Gallery', 'Galeri');

// There's a 'web.management.gallery' route that might have become 'web.management.galeri'
// And we also want to fix 'penelitianes'
$webPath = __DIR__ . '/routes/web.php';
if (file_exists($webPath)) {
    $content = file_get_contents($webPath);
    $content = str_replace('penelitianes', 'penelitian', $content);
    file_put_contents($webPath, $content);
}
$webControllerPath = __DIR__ . '/app/Http/Controllers/WebController.php';
if (file_exists($webControllerPath)) {
    $content = file_get_contents($webControllerPath);
    $content = str_replace('penelitianes', 'penelitian', $content);
    file_put_contents($webControllerPath, $content);
}

echo "Fixup complete.\n";
