<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    protected $table = 'penelitian';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'code',
        'field',
        'status_riset',
        'facility',
        'location',
        'peneliti',
        'tujuan',
        'roadmap',
        'params',
        'benchmarks',
        'metrics',
    ];

    protected $casts = [
        'roadmap'    => 'array',
        'params'     => 'array',
        'benchmarks' => 'array',
        'metrics'    => 'array',
    ];
}