<?php

$mappings = [
    // Database tables
    'contents' => 'konten',
    'request_chats' => 'obrolan_permintaan',
    'chat_reads' => 'baca_obrolan',
    'division_chat_reads' => 'baca_obrolan_divisi',
    'division_chats' => 'obrolan_divisi',
    'about_bullets' => 'poin_tentang',
    'news' => 'berita',
    'research' => 'penelitian',
    'galleries' => 'galeri',
    'home_backgrounds' => 'latar_beranda',

    // Models & Classes
    'AboutBullet' => 'PoinTentang',
    'ChatRead' => 'BacaObrolan',
    'Content' => 'Konten',
    'DivisionChat' => 'ObrolanDivisi',
    'DivisionChatRead' => 'BacaObrolanDivisi',
    'Gallery' => 'Galeri',
    'HomeBackground' => 'LatarBeranda',
    'News' => 'Berita',
    'RequestChat' => 'ObrolanPermintaan',
    'Research' => 'Penelitian',

    // Controllers
    'DashboardController' => 'DasborController',
    'ProfileController' => 'ProfilController',
    'RequestController' => 'PermintaanController',
    'WarehouseController' => 'GudangController',

    // Others
    'ContentSeeder' => 'KontenSeeder',
    'DummyContentSeeder' => 'KontenDummySeeder',
    'DummyRequestSeeder' => 'PermintaanDummySeeder',

    // Views / URLs / General words
    'dashboard' => 'dasbor',
    'logbook' => 'catatan',
    'pages' => 'halaman',
    'profile' => 'profil',
    'request' => 'permintaan',
    'warehouse' => 'gudang',
    'web_management' => 'manajemen_web',
];

$directoriesToScan = [
    __DIR__ . '/app',
    __DIR__ . '/database/migrations',
    __DIR__ . '/database/seeders',
    __DIR__ . '/resources/views',
    __DIR__ . '/routes',
];

echo "Starting refactor...\n";

// We'll replace contents first, then rename files to avoid breaking paths mid-way.
function replaceInFile($file, $mappings) {
    $content = file_get_contents($file);
    $newContent = $content;
    
    // Sort mappings by length descending to avoid partial replacements (e.g. replacing 'chat' before 'request_chats')
    uksort($mappings, function($a, $b) {
        return strlen($b) - strlen($a);
    });

    foreach ($mappings as $search => $replace) {
        // Simple string replacement. For more complex cases we might need regex, but let's see.
        $newContent = str_replace($search, $replace, $newContent);
    }

    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "Updated content in: $file\n";
    }
}

function processDirectoryContent($dir, $mappings) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            processDirectoryContent($path, $mappings);
        } else {
            if (pathinfo($path, PATHINFO_EXTENSION) === 'php' || pathinfo($path, PATHINFO_EXTENSION) === 'js') {
                replaceInFile($path, $mappings);
            }
        }
    }
}

function renameFilesAndDirectories($dir, $mappings) {
    $files = scandir($dir);
    $renames = [];

    // Sort mappings by length descending
    uksort($mappings, function($a, $b) {
        return strlen($b) - strlen($a);
    });

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        
        // Find if any mapping applies to the filename
        $newName = $file;
        foreach ($mappings as $search => $replace) {
            if (strpos($newName, $search) !== false) {
                // If the word matches, we rename.
                $newName = str_replace($search, $replace, $newName);
            }
        }
        
        if ($newName !== $file) {
            $newPath = $dir . '/' . $newName;
            rename($path, $newPath);
            echo "Renamed: $path -> $newPath\n";
            $path = $newPath; // Update path for recursion if it's a dir
        }
        
        if (is_dir($path)) {
            renameFilesAndDirectories($path, $mappings);
        }
    }
}

foreach ($directoriesToScan as $dir) {
    if (is_dir($dir)) {
        processDirectoryContent($dir, $mappings);
    }
}

foreach ($directoriesToScan as $dir) {
    if (is_dir($dir)) {
        renameFilesAndDirectories($dir, $mappings);
    }
}

echo "Refactoring completed.\n";
