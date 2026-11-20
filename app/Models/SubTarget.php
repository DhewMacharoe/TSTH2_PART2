<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class SubTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_id',
        'title',
        'description',
        'percentage',
        'progress',
        'period_start',
        'period_end',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'integer',
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class, 'target_id');
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'sub_target_id');
    }
}