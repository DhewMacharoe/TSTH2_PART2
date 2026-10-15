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
}
