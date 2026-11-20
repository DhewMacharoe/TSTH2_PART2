<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Berita;
use App\Models\Penelitian;
use App\Models\GaleriAlbum;

class TsthidDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = base_path('resources/views/gudang/tsthid_database.sql');

        if (!file_exists($sqlPath)) {
            $this->command->error("File tsthid_database.sql tidak ditemukan di {$sqlPath}");
            return;
        }

        $sql = file_get_contents($sqlPath);

        // 1. Seed Admins -> Users Table
        $this->seedAdmins($sql);

        // 2. Seed News -> Berita Table
        $this->seedNews($sql);

        // 3. Seed Researches -> Penelitian Table
        $this->seedResearches($sql);

        // 4. Seed Gallery -> Galeri Table
        $this->seedGallery($sql);
    }

    private function seedAdmins(string $sql): void
    {
        if (!preg_match_all('/INSERT INTO `admins` \((.*?)\) VALUES\s*(.*?);/s', $sql, $matches)) {
            return;
        }

        foreach ($matches[2] as $valuesBlock) {
            $rows = $this->parseSqlValues($valuesBlock);
            foreach ($rows as $row) {
                if (count($row) < 3) continue;

                $id = $row[0];
                $username = trim($row[1]);
                $password = trim($row[2]);

                $email = str_contains($username, '@') ? $username : $username . '@tsth2.id';

                // Jika hash bcrypt, gunakan langsung. Jika plaintext, hash dulu.
                $hashedPassword = str_starts_with($password, '$2y$') ? $password : Hash::make($password);

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => ucfirst(str_replace('_', ' ', $username)),
                        'password' => $hashedPassword,
                        'role' => 'web_admin',
                    ]
                );
            }
        }
        $this->command->info('Admins dari tsthid_database.sql berhasil dimigrasikan ke tabel users.');
    }

    private function seedNews(string $sql): void
    {
        if (!preg_match_all('/INSERT INTO `news` \((.*?)\) VALUES\s*(.*?);/s', $sql, $matches)) {
            return;
        }

        foreach ($matches[2] as $valuesBlock) {
            $rows = $this->parseSqlValues($valuesBlock);
            foreach ($rows as $row) {
                if (count($row) < 8) continue;

                $title = trim($row[1]);
                $headerImage = trim($row[3]);
                $content = trim($row[4]);
                $status = trim($row[5]);
                $postDate = trim($row[6]);
                $createdAt = trim($row[7]);

                Berita::updateOrCreate(
                    ['title' => $title],
                    [
                        'category' => 'Berita',
                        'description' => $content,
                        'date' => $postDate,
                        'image_path' => $headerImage ?: null,
                        'created_at' => $createdAt ?: now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
        $this->command->info('News dari tsthid_database.sql berhasil dimigrasikan ke tabel berita.');
    }

    private function seedResearches(string $sql): void
    {
        if (!preg_match_all('/INSERT INTO `researches` \((.*?)\) VALUES\s*(.*?);/s', $sql, $matches)) {
            return;
        }

        foreach ($matches[2] as $valuesBlock) {
            $rows = $this->parseSqlValues($valuesBlock);
            foreach ($rows as $row) {
                if (count($row) < 8) continue;

                $title = trim($row[1]);
                $headerImage = trim($row[2]);
                $description = trim($row[3]);
                $objective = trim($row[4]);
                $status = trim($row[5]);
                $leadResearcher = trim($row[6]);
                $createdAt = trim($row[7]);

                $fullDescription = $description;
                if (!empty($objective)) {
                    $fullDescription .= "\n\nTujuan:\n" . $objective;
                }
                if (!empty($leadResearcher)) {
                    $fullDescription .= "\n\nPeneliti Utama: " . $leadResearcher;
                }

                Penelitian::updateOrCreate(
                    ['title' => $title],
                    [
                        'description' => $fullDescription,
                        'image_path' => $headerImage ?: null,
                        'created_at' => $createdAt ?: now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
        $this->command->info('Researches dari tsthid_database.sql berhasil dimigrasikan ke tabel penelitian.');
    }

    private function seedGallery(string $sql): void
    {
        // Mendukung gallery jika ada data gallery_folders dan gallery_photos
        if (!preg_match_all('/INSERT INTO `gallery_photos` \((.*?)\) VALUES\s*(.*?);/s', $sql, $matches)) {
            return;
        }

        foreach ($matches[2] as $valuesBlock) {
            $rows = $this->parseSqlValues($valuesBlock);
            foreach ($rows as $row) {
                if (count($row) < 3) continue;

                $photoPath = trim($row[2]);
                $albumTitle = 'Dokumentasi TSTH2 ' . $row[0];

                $album = GaleriAlbum::firstOrCreate(
                    ['title' => $albumTitle],
                    [
                        'description' => 'Dokumentasi kegiatan TSTH2',
                        'event_date'  => now(),
                        'photos'      => [$photoPath],
                    ]
                );

                if (!in_array($photoPath, $album->photos ?? [])) {
                    $photos = $album->photos ?? [];
                    $photos[] = $photoPath;
                    $album->update(['photos' => $photos]);
                }
            }
        }
        $this->command->info('Gallery dari tsthid_database.sql berhasil dimigrasikan ke galeri_albums.');
    }

    /**
     * Helper parser SQL VALUES (...)
     */
    private function parseSqlValues(string $valuesSql): array
    {
        $rows = [];
        $length = strlen($valuesSql);
        $inString = false;
        $quoteChar = '';
        $currentValue = '';
        $currentRow = [];

        for ($i = 0; $i < $length; $i++) {
            $char = $valuesSql[$i];

            if ($inString) {
                if ($char === '\\') {
                    $currentValue .= $valuesSql[++$i] ?? '';
                } elseif ($char === $quoteChar) {
                    if (isset($valuesSql[$i + 1]) && $valuesSql[$i + 1] === $quoteChar) {
                        $currentValue .= $quoteChar;
                        $i++;
                    } else {
                        $inString = false;
                    }
                } else {
                    $currentValue .= $char;
                }
            } else {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $quoteChar = $char;
                } elseif ($char === ',') {
                    $currentRow[] = $currentValue;
                    $currentValue = '';
                } elseif ($char === '(') {
                    $currentRow = [];
                    $currentValue = '';
                } elseif ($char === ')') {
                    $currentRow[] = $currentValue;
                    $rows[] = $currentRow;
                    $currentRow = [];
                    $currentValue = '';
                } elseif (!ctype_space($char)) {
                    $currentValue .= $char;
                }
            }
        }

        return $rows;
    }
}
