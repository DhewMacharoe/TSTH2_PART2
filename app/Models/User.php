<?php

namespace App\Models;
use App\Models\Project;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'division_id',
        'employee_code',
        'join_year',
        'profile_photo',
        'birth_date',
        'phone',
        'asal',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'         => 'hashed',
            'birth_date'       => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGBOOK
    |--------------------------------------------------------------------------
    */

    /**
     * User memiliki banyak logbook.
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'user_id');
    }


    /*
    |--------------------------------------------------------------------------
    | TASK
    |--------------------------------------------------------------------------
    */

    /**
     * Task yang diberikan/ditugaskan kepada user.
     *
     * tasks.assigned_to -> users.id
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Task yang dibuat/ditugaskan oleh user.
     *
     * tasks.assigned_by -> users.id
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT
    |--------------------------------------------------------------------------
    */

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }


    /*
    |--------------------------------------------------------------------------
    | COMMENT
    |--------------------------------------------------------------------------
    */

    /**
     * User memiliki banyak komentar.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

    /**
     * User berada pada satu department.
     */
    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DIVISION
    |--------------------------------------------------------------------------
    */

    /**
     * User berada pada satu division.
     */
    public function divisionRelation()
    {
        return $this->belongsTo(
            Division::class,
            'division_id'
        );
    }

    /**
     * Nama tampilan tanpa nama personal untuk akun manajerial operasional yang diwariskan.
     */
    public function getDisplayNameAttribute(): string
    {
        if (in_array($this->role, ['web_admin', 'admin'])) {
            return 'Web Administrator';
        }
        if ($this->role === 'pic_gudang') {
            return 'PIC Warehouse (GA)';
        }
        if ($this->role === 'pic_lab') {
            return 'PIC Warehouse (RdI)';
        }
        if ($this->role === 'admin_katalog') {
            return 'Admin Katalog';
        }
        return $this->name ?? 'User';
    }

    public function getDisplayInitialAttribute(): string
    {
        if (in_array($this->role, ['web_admin', 'admin'])) {
            return 'W';
        }
        if (in_array($this->role, ['pic_gudang', 'pic_lab'])) {
            return 'P';
        }
        if ($this->role === 'admin_katalog') {
            return 'K';
        }
        return strtoupper(substr($this->name ?? 'U', 0, 1));
    }
}