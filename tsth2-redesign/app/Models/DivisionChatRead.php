<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionChatRead extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
