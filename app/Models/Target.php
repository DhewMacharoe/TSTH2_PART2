<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Target extends Model
{
    protected $fillable = [
        'project_id',
        'department_id',
        'division_id',
        'title',
        'description',
        'target_progress',
        'period_start',
        'period_end',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    | Project merupakan wadah/program yang dapat melibatkan
    | beberapa divisi.
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Division
    |--------------------------------------------------------------------------
    | Target tetap dimiliki oleh divisi tertentu.
    */

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Sub Targets
    |--------------------------------------------------------------------------
    | Satu Target dapat memiliki beberapa Sub Target.
    */

    public function subTargets(): HasMany
    {
        return $this->hasMany(SubTarget::class, 'target_id');
    }
}
