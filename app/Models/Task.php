<?php

namespace App\Models;

use App\Models\Department;
use App\Models\SubTarget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
    {
    protected $fillable = [
        'target_id',
        'sub_target_id',
        'assigned_by',
        'assigned_to',
        'department_id',
        'division_id',
        'title',
        'description',
        'target_progress',
        'reported_progress',
        'verified_progress',
        'status',
        'work_type',
        'other_work_type',
    ];

    protected function casts(): array
    {
    return [
        'weight' => 'integer',
        'target_progress' => 'integer',
        'reported_progress' => 'integer',
        'verified_progress' => 'integer',
        ];
    }

    /**
     * Target departemen.
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }

    /**
     * Kepala Divisi yang memberikan pekerjaan.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Karyawan yang menerima pekerjaan.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Departemen pekerjaan.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Divisi pekerjaan.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Sub Target dari pekerjaan ini.
     */
    public function subTarget(): BelongsTo
    {
        return $this->belongsTo(SubTarget::class);
    }

    /**
     * Logbook dari pekerjaan ini.
     */
    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function getCurrentProgressAttribute(): int
    {
        $latestApproved = $this->logbooks()
            ->where('status', 'approved')
            ->latest('date')
            ->latest('id')
            ->first();

        return $latestApproved?->progress ?? 0;
    }
}