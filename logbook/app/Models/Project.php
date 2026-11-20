<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'progress',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    | User yang membuat project.
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Targets
    |--------------------------------------------------------------------------
    | Satu project dapat memiliki banyak Target
    | dari divisi-divisi yang berbeda.
    */

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class, 'project_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Divisions
    |--------------------------------------------------------------------------
    | Satu project dapat melibatkan banyak divisi.
    |
    | Contoh:
    | Project A
    | ├── Administrasi
    | ├── Operasional
    | └── Keuangan
    |--------------------------------------------------------------------------
    */

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(
            Division::class,
            'project_divisions',
            'project_id',
            'division_id'
        );
    }
}
