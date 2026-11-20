<?php
$cssFile = __DIR__ . '/public/css/landing.css';
if (file_exists($cssFile)) {
    $content = file_get_contents($cssFile);
    $content = str_replace('research-focus', 'penelitian-focus', $content);
    $content = str_replace('research-grid', 'penelitian-grid', $content);
    $content = str_replace('research-card', 'penelitian-card', $content);
    file_put_contents($cssFile, $content);
    echo "Fixed CSS classes in landing.css\n";
}
