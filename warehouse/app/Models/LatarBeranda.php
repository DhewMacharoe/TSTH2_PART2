<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatarBeranda extends Model
{
    protected $table = 'latar_berandas';

    protected $fillable = [
        'image_path',
    ];
}