<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Department memiliki banyak Division.
     */
    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    /**
     * Department memiliki banyak User.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Department memiliki banyak Target.
     */
    public function targets(): HasMany
    {
        return $this->hasMany(Target::class);
    }
}