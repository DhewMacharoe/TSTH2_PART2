<?php
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
                echo "Fixed in $path\n";
            }
        }
    }
}

replaceInDir(__DIR__ . '/resources/views', "permintaan('", "request('");
replaceInDir(__DIR__ . '/resources/views', "permintaan()", "request()");
replaceInDir(__DIR__ . '/app', "permintaan('", "request('");
replaceInDir(__DIR__ . '/app', "permintaan()", "request()");

echo "Request helper fixed.\n";
