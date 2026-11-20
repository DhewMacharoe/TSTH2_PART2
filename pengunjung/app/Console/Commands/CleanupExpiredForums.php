<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PermintaanBarang;
use App\Models\ObrolanPermintaan;
use Illuminate\Support\Facades\DB;

class CleanupExpiredForums extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gudang:cleanup-forums {--dry-run : Lihat apa yang akan dihapus tanpa benar-benar menghapus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus percakapan forum diskusi permintaan yang sudah melewati masa forum_ends_at';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        // Cari semua permintaan yang forum-nya sudah expired
        $expiredRequests = PermintaanBarang::whereNotNull('forum_ends_at')
            ->where('forum_ends_at', '<', now())
            ->withCount('chats')
            ->get();

        if ($expiredRequests->isEmpty()) {
            $this->info('✅ Tidak ada forum yang kadaluarsa. Tidak ada yang perlu dihapus.');
            return Command::SUCCESS;
        }

        $this->info("🔍 Ditemukan {$expiredRequests->count()} forum yang kadaluarsa:");
        $this->table(
            ['Request ID', 'Barang', 'Status', 'Forum Berakhir', 'Jumlah Chat'],
            $expiredRequests->map(fn($r) => [
                '#REQ' . str_pad($r->id, 4, '0', STR_PAD_LEFT),
                $r->barang?->nama_barang ?? '-',
                $r->status,
                $r->forum_ends_at?->format('d M Y H:i'),
                $r->chats_count,
            ])->toArray()
        );

        if ($isDryRun) {
            $this->warn('⚠️  Dry-run mode: tidak ada data yang dihapus.');
            return Command::SUCCESS;
        }

        // Hapus semua chat dari permintaan yang expired
        $deletedCount = 0;
        foreach ($expiredRequests as $req) {
            $count = $req->chats()->delete();
            $deletedCount += $count;
        }

        $this->info("🗑️  Berhasil menghapus {$deletedCount} pesan dari {$expiredRequests->count()} forum.");
        return Command::SUCCESS;
    }
}
