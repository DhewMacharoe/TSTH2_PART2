<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'logbook_type',
        'date',
        'activity',
        'obstacle',
        'next_plan',
        'documentation_image',
        'documentation_images',
        'documentation_link',
        'documentation_links',
        'status',
        'progress',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'date' => 'datetime',
        'reviewed_at' => 'datetime',
        'progress' => 'integer',
        'documentation_images' => 'array',
        'documentation_links' => 'array',
    ];

    /**
     * Dapatkan daftar seluruh gambar dokumentasi.
     * Kompatibel ke belakang jika logbook lama hanya memiliki `documentation_image`.
     */
    public function getDocumentationImagesAttribute(): array
    {
        $raw = $this->attributes['documentation_images'] ?? null;
        if (!empty($raw)) {
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            if (is_array($decoded) && count($decoded) > 0) {
                return array_values(array_filter($decoded));
            }
        }

        if (!empty($this->attributes['documentation_image'])) {
            return [$this->attributes['documentation_image']];
        }

        return [];
    }

    /**
     * Dapatkan daftar seluruh link dokumentasi.
     * Kompatibel ke belakang jika logbook lama hanya memiliki `documentation_link`.
     */
    public function getDocumentationLinksAttribute(): array
    {
        $raw = $this->attributes['documentation_links'] ?? null;
        if (!empty($raw)) {
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            if (is_array($decoded) && count($decoded) > 0) {
                return array_values(array_filter($decoded));
            }
        }

        if (!empty($this->attributes['documentation_link'])) {
            return [$this->attributes['documentation_link']];
        }

        return [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}