<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'divisi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────────────────────

    public function isPicGudang(): bool
    {
        return $this->role === 'pic_gudang';
    }

    public function isWebAdmin(): bool
    {
        return $this->role === 'web_admin';
    }

    /**
     * Requester adalah siapapun yang bisa membuat permintaan barang:
     * semua role kecuali web_admin.
     */
    public function isRequester(): bool
    {
        return !$this->isWebAdmin();
    }

    /**
     * Apakah user dapat mengakses menu gudang (gudang inventory).
     */
    public function canAccessGudang(): bool
    {
        return in_array($this->role, ['pic_gudang', 'web_admin', 'agronom', 'pimpinan']);
    }
}
