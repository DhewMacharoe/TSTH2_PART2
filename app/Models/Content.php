<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['section', 'key', 'value', 'type', 'image_path'];
}
