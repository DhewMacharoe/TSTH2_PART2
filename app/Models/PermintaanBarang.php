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
        'closed_at'    => 'datetime',
        'forum_ends_at' => 'datetime',
        'is_habis_pakai' => 'boolean',
        'is_habis_pakai_saat_lapor' => 'boolean',
    ];

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

    // ─── Helpers ────────────────────────────────────────────────────────────
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