<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PermintaanBarang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'closed_at'                 => 'datetime',
        'forum_ends_at'             => 'datetime',
        'tanggal_pengambilan'       => 'datetime',
        'tanggal_pengembalian'      => 'datetime',
        'jadwal_pengambilan'        => 'datetime',
        'pending_at'                => 'datetime',
        'approved_at'               => 'datetime',
        'rejected_at'               => 'datetime',
        'escalated_at'              => 'datetime',
        'verified_at'               => 'datetime',
        'is_habis_pakai'            => 'boolean',
        'is_habis_pakai_saat_lapor' => 'boolean',
    ];

    /**
     * Cek apakah jadwal pengambilan sudah terlewat saat status Menunggu Pengambilan.
     */
    public function isSchedulePast(): bool
    {
        return $this->jadwal_pengambilan && $this->jadwal_pengambilan->isPast();
    }

    /**
     * Cek apakah status Pending sudah berlangsung selama 3 hari atau lebih.
     */
    public function isPendingOverdue(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $pendingSince = $this->pending_at ?? $this->updated_at;
        return $pendingSince && $pendingSince->diffInDays(now()) >= 3;
    }

    /**
     * Cek apakah permintaan yang menunggu approval di level kepala_divisi
     * sudah 3 hari tanpa respons — sehingga layak dieskalasi ke kepala_departemen.
     */
    public function isEscalatable(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_APPROVAL
            && $this->target_approval === self::TARGET_KEPALA_DIVISI
            && $this->created_at->diffInDays(now()) >= 3;
    }

    /**
     * Jumlah hari pending.
     */
    public function getPendingDaysAttribute(): int
    {
        $pendingSince = $this->pending_at ?? $this->updated_at;
        return $pendingSince ? (int) $pendingSince->diffInDays(now()) : 0;
    }

    // ─── Konstanta Status ───────────────────────────────────────────────────
    const STATUS_MENUNGGU_APPROVAL      = 'Menunggu Approval';
    const STATUS_MENUNGGU_PENGAMBILAN   = 'Menunggu Pengambilan';
    const STATUS_BARANG_DIAMBIL         = 'Barang Diambil';
    const STATUS_VERIFIKASI_PENGEMBALIAN = 'Menunggu Verifikasi Pengembalian';
    const STATUS_BARANG_DIKEMBALIKAN    = 'Barang Dikembalikan';
    const STATUS_PENDING                = 'Pending (Jadwal Ulang)';
    const STATUS_SELESAI                = 'Selesai';
    const STATUS_DITOLAK                = 'Ditolak';
    const STATUS_DIBATALKAN             = 'Dibatalkan';
    const STATUS_DIESKALASI             = 'Dieskalasi';

    // Target approval dalam hirarki
    const TARGET_KEPALA_DIVISI      = 'kepala_divisi';
    const TARGET_KEPALA_DEPARTEMEN  = 'kepala_departemen';

    // Tipe permintaan
    const TIPE_LAPANGAN = 'lapangan';
    const TIPE_LAB      = 'lab';

    // Status "aktif" — forum masih berjalan
    const ACTIVE_STATUSES = [
        self::STATUS_MENUNGGU_APPROVAL,
        self::STATUS_MENUNGGU_PENGAMBILAN,
        self::STATUS_BARANG_DIAMBIL,
        self::STATUS_VERIFIKASI_PENGEMBALIAN,
        self::STATUS_BARANG_DIKEMBALIKAN,
        self::STATUS_PENDING,
    ];

    // Status "closed" — forum akan/sudah berakhir
    const CLOSED_STATUSES = [
        self::STATUS_SELESAI,
        self::STATUS_DITOLAK,
        self::STATUS_DIBATALKAN,
    ];

    // ─── Relasi ─────────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function chats()
    {
        return $this->hasMany(ObrolanPermintaan::class, 'permintaan_barang_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function staffGudang()
    {
        return $this->belongsTo(User::class, 'staff_gudang_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->whereIn('status', self::ACTIVE_STATUSES);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', self::CLOSED_STATUSES);
    }

    public function scopeForumAlive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('forum_ends_at')
              ->orWhere('forum_ends_at', '>', now());
        });
    }

    // Filter by jenis_barang (lapangan/lab) — menggunakan kolom yang aktif
    public function scopeLapangan($query)
    {
        return $query->where('jenis_barang', 'lapangan');
    }

    public function scopeLab($query)
    {
        return $query->where('jenis_barang', 'lab');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────
    public function getJenisLabelAttribute(): string
    {
        return $this->jenis_barang === 'lab' ? 'Barang Lab' : 'Barang Lapangan';
    }

    public function getTargetApprovalLabelAttribute(): string
    {
        return match ($this->target_approval) {
            self::TARGET_KEPALA_DIVISI     => 'Kepala Divisi Operasional',
            self::TARGET_KEPALA_DEPARTEMEN => 'Kepala Departemen General Affair',
            default                        => ucfirst(str_replace('_', ' ', $this->target_approval ?? '-')),
        };
    }

    public function canBeApprovedBy(?User $user): bool
    {
        if (!$user || $this->status !== self::STATUS_MENUNGGU_APPROVAL) {
            return false;
        }

        if ($user->isWebAdmin() || $user->role === 'pimpinan') {
            return true;
        }

        if ($this->target_approval === self::TARGET_KEPALA_DIVISI && $user->role === 'kepala_divisi') {
            return true;
        }

        if ($this->target_approval === self::TARGET_KEPALA_DEPARTEMEN && $user->role === 'kepala_departemen') {
            return true;
        }

        return false;
    }

    public function isForumActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES);
    }

    public function forumCountdown(): ?string
    {
        if (!$this->forum_ends_at) return null;
        if ($this->forum_ends_at->isPast()) return 'Berakhir';
        return $this->forum_ends_at->diffForHumans();
    }

    public function forumDaysLeft(): ?int
    {
        if (!$this->forum_ends_at) return null;
        if ($this->forum_ends_at->isPast()) return 0;
        return (int) now()->diffInDays($this->forum_ends_at, false);
    }

    /**
     * Tutup permintaan: set closed_at dan forum_ends_at (+3 hari)
     */
    public function closeRequest(): void
    {
        $this->status        = self::STATUS_SELESAI;
        $this->closed_at     = now();
        $this->forum_ends_at = now()->addDays(3);
        $this->save();
    }
}