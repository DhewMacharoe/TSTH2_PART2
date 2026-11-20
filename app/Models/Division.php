<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Division extends Model
{
    protected $fillable = [
        'department_id',
        'name',
    ];

    /**
     * Department induk dari divisi.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * User yang berada di divisi ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Project yang melibatkan divisi ini.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_divisions',
            'division_id',
            'project_id'
        );
    }

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class, 'division_id');
    }

}   