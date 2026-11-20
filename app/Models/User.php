<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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

    public function isPicLab(): bool
    {
        return $this->role === 'pic_lab';
    }

    public function isAnyWarehousePic(): bool
    {
        return in_array($this->role, ['pic_gudang', 'pic_lab']);
    }

    public function isWebAdmin(): bool
    {
        return $this->role === 'web_admin';
    }

    public function isApprover(): bool
    {
        return in_array($this->role, ['pic_gudang', 'pic_lab', 'web_admin']);
    }

    public function canManageWarehouse(): bool
    {
        return in_array($this->role, ['pic_gudang', 'pic_lab', 'web_admin']);
    }

    /**
     * Mengembalikan jenis warehouse yang dikelola PIC ('lapangan' untuk Gudang GA, 'lab' untuk Lab RdI, atau 'all' untuk Admin).
     */
    public function getWarehouseScope(): string
    {
        if ($this->role === 'pic_gudang') return 'lapangan';
        if ($this->role === 'pic_lab') return 'lab';
        return 'all';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'pic_gudang'        => 'PIC Warehouse (General Affair)',
            'pic_lab'           => 'PIC Warehouse (Riset & Inovasi)',
            'web_admin'         => 'Web Administrator',
            'admin_katalog'     => 'Admin Katalog',
            'pimpinan'          => 'Pimpinan / Direktur',
            'agronom'           => 'Agronom & Peneliti',
            'employee', 'karyawan' => 'Karyawan',
            'kepala_departemen' => 'Kepala Departemen',
            'kepala_divisi'     => 'Kepala Divisi',
            default             => ucfirst(str_replace('_', ' ', $this->role ?? 'User')),
        };
    }

    /**
     * Nama tampilan tanpa nama personal untuk akun manajerial operasional yang diwariskan.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isWebAdmin()) {
            return 'Web Administrator';
        }
        if ($this->isPicGudang()) {
            return 'PIC Warehouse (GA)';
        }
        if ($this->isPicLab()) {
            return 'PIC Warehouse (RdI)';
        }
        if ($this->role === 'admin_katalog') {
            return 'Admin Katalog';
        }
        return $this->name ?? 'User';
    }

    public function getDisplayInitialAttribute(): string
    {
        if ($this->isWebAdmin()) {
            return 'W';
        }
        if ($this->isPicGudang() || $this->isPicLab()) {
            return 'P';
        }
        if ($this->role === 'admin_katalog') {
            return 'K';
        }
        return strtoupper(substr($this->name ?? 'U', 0, 1));
    }

    /**
     * Requester adalah siapapun yang bisa membuat permintaan barang.
     * PIC dan Web Admin adalah manajerial/approver, BUKAN requester.
     */
    public function isRequester(): bool
    {
        return !in_array($this->role, ['web_admin', 'pic_gudang', 'pic_lab', 'admin_katalog']);
    }

    /**
     * Apakah user dapat mengisi logbook kerja harian.
     * PIC dan Web Admin tidak mengisi logbook.
     */
    public function canFillLogbook(): bool
    {
        return !in_array($this->role, ['web_admin', 'pic_gudang', 'pic_lab', 'admin_katalog']);
    }

    /**
     * Apakah user dapat mengakses menu gudang (gudang inventory).
     */
    public function canAccessGudang(): bool
    {
        return in_array($this->role, [
            'pic_gudang', 'pic_lab', 'web_admin', 'agronom', 'pimpinan', 
            'employee', 'karyawan', 'warehouse'
        ]);
    }
}
