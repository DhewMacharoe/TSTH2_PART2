<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = ['category', 'title', 'description', 'date', 'image_path', 'status'];

    /**
     * Hanya berita yang sudah dipublikasikan (untuk halaman publik).
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
