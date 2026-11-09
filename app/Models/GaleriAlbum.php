<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriAlbum extends Model
{
    protected $table = 'galeri_albums';
    protected $fillable = ['title', 'description', 'event_date', 'photos'];

    protected $casts = [
        'photos'     => 'array',
        'event_date' => 'date',
    ];

    /**
     * Accessor dinamis agar pemanggilan $item->photos tetap bekerja
     * baik sebagai array foto maupun objek photo compatible
     */
    public function getPhotosListAttribute(): array
    {
        return is_array($this->photos) ? $this->photos : [];
    }

    /**
     * Dapatkan path cover (foto pertama)
     */
    public function getCoverAttribute(): ?string
    {
        $list = $this->photos_list;
        return count($list) > 0 ? $list[0] : null;
    }

    /**
     * Cek apakah file berupa video berdasarkan ekstensinya
     */
    public static function isVideoFile(?string $path): bool
    {
        if (!$path) return false;
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv']);
    }

    /**
     * Hitung jumlah foto & video di dalam album
     */
    public function getMediaStatsAttribute(): array
    {
        $photos = 0;
        $videos = 0;
        foreach ($this->photos_list as $item) {
            if (self::isVideoFile($item)) {
                $videos++;
            } else {
                $photos++;
            }
        }
        return ['photos' => $photos, 'videos' => $videos, 'total' => count($this->photos_list)];
    }
}
