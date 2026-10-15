<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    
    protected $table = 'konten';protected $fillable = ['section', 'key', 'value', 'type', 'image_path'];
}
