<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinTentang extends Model
{
    protected $table = 'poin_tentangs';

    protected $fillable = [
        'content',
    ];
}