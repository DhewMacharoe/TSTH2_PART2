<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\AktivitasGudang;
use App\Services\PermintaanFifoService;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanStatusController extends Controller
{
    protected $fifoService;

    public function __construct(PermintaanFifoService $fifoService)
    {
        $this->fifoService = $fifoService;
    }

    public function updateStatus(Request $request, $id)
    {
        $permintaan = PermintaanBarang::findOrFail($id);
        $user       = Auth::user();
        $action     = $request->action;

        // PIC gudang atau kepala yang berwenang
        $isApprover = in_array($user->role, ['pic_gudang', 'web_admin', 'kepala_divisi', 'kepala_departemen']);

        if ($isApprover) {
            return $this->handleApproverActions($request, $permintaan, $action, $user);
        }

        if ($user->id === $permintaan->user_id) {
            return $this->handleRequesterActions($request, $permintaan, $action);
        }

        return redirect()->route('permintaan.index')->with('error', 'Aksi tidak diizinkan.');
    }

    // ─── Aksi untuk pihak yang berwenang menyetujui ───────────────────────────
    private function handleApproverActions(Request $request, PermintaanBarang $permintaan, $action, $user)
    {
        if ($action === 'approve') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_MENUNGGU_APPROVAL, 422, 'Status tidak valid.');

            // Kepala divisi hanya bisa approve kalau target masih kepada mereka
            if ($user->role === 'kepala_divisi' && $permintaan->target_approval !== PermintaanBarang::TARGET_KEPALA_DIVISI) {
                return back()->with('error', 'Permintaan ini sudah dieskalasi ke Kepala Departemen.');
            }
            // Kepala departemen hanya bisa approve kalau sudah dieskalasi ke mereka
            if ($user->role === 'kepala_departemen' && $permintaan->target_approval !== PermintaanBarang::TARGET_KEPALA_DEPARTEMEN) {
                return back()->with('error', 'Permintaan ini belum dieskalasi ke level Anda.');
            }

            $request->validate([
                'jadwal_pengambilan' => 'required|date|after_or_equal:today',
                'catatan_pic'        => 'required|string|max:1000',
            ], ['catatan_pic.required' => 'Catatan wajib diisi saat menyetujui.']);

            $permintaan->update([
                'status'             => PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN,
                'jadwal_pengambilan' => $request->jadwal_pengambilan,
                'catatan_pic'        => $request->catatan_pic,
                'pic_id'             => $user->id,
                'approved_by'        => $user->id,
                'approved_at'        => now(),
                'approval_level'     => $user->role,
            ]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'approve',
                "Permintaan #{$permintaan->id} disetujui oleh {$user->name} ({$user->role}). Jadwal: " . $request->jadwal_pengambilan . '.'
            );

            return redirect()->route('permintaan.index')->with('success', 'Permintaan disetujui. Jadwal pengambilan telah ditetapkan.');
        }

        if ($action === 'reject') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_MENUNGGU_APPROVAL, 422, 'Status tidak valid.');

            $permintaan->update([
                'status'            => PermintaanBarang::STATUS_DITOLAK,
                'catatan_pic'       => $request->catatan_pic,
                'catatan_penolakan' => $request->catatan_pic,
                'pic_id'            => $user->id,
                'rejected_by'       => $user->id,
                'rejected_at'       => now(),
                'closed_at'         => now(),
                'forum_ends_at'     => now()->addDays(3),
            ]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'tolak',
                "Permintaan #{$permintaan->id} ditolak oleh {$user->name} ({$user->role}). Alasan: " . ($request->catatan_pic ?? '-') . '.'
            );

            return redirect()->route('permintaan.index')->with('success', 'Permintaan ditolak.');
        }

        if ($action === 'reschedule') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_PENDING, 422, 'Hanya permintaan pending yang bisa dijadwalkan ulang.');
            $request->validate([
                'jadwal_pengambilan' => 'required|date|after_or_equal:today',
                'catatan_pic'        => 'nullable|string|max:1000',
            ]);

            $permintaan->update([
                'status'             => PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN,
                'jadwal_pengambilan' => $request->jadwal_pengambilan,
                'catatan_pic'        => $request->catatan_pic ?? $permintaan->catatan_pic,
                'pic_id'             => $user->id,
                'pending_at'         => null,
            ]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'reschedule',
                "Permintaan #{$permintaan->id} dijadwalkan ulang oleh {$user->name}. Jadwal baru: " . $request->jadwal_pengambilan . '.'
            );

            return redirect()->route('permintaan.index')->with('success', 'Jadwal pengambilan telah diperbarui.');
        }

        if ($action === 'cancel' && in_array($permintaan->status, [
            PermintaanBarang::STATUS_MENUNGGU_APPROVAL,
            PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN,
            PermintaanBarang::STATUS_PENDING,
        ])) {
            $permintaan->update([
                'status'        => PermintaanBarang::STATUS_DIBATALKAN,
                'catatan_pic'   => ($permintaan->catatan_pic ? $permintaan->catatan_pic . "\n" : '') .
                    '[PIC] ' . ($request->catatan_pic ?? 'Permintaan dibatalkan.'),
                'closed_at'     => now(),
                'forum_ends_at' => now()->addDays(3),
            ]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'batalkan',
                "Permintaan #{$permintaan->id} dibatalkan oleh {$user->name}."
            );

            return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil dibatalkan.');
        }

        if ($action === 'verify_return') {
            $result = $this->fifoService->handleVerifyReturn($permintaan);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'verifikasi_kembali',
                "Permintaan #{$permintaan->id}: pengembalian barang diverifikasi oleh {$user->name}.",
                AktivitasGudang::MUTASI_MASUK,
                $permintaan->barang_id,
                $permintaan->barang?->nama_barang,
                $permintaan->jumlah_dikembalikan,
                $permintaan->satuan_diminta
            );

            return $result;
        }

        if ($action === 'close') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_BARANG_DIKEMBALIKAN, 422, 'Hanya permintaan yang barangnya sudah dikembalikan yang bisa diselesaikan.');
            $permintaan->closeRequest();

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'selesai',
                "Permintaan #{$permintaan->id} ditutup/diselesaikan oleh {$user->name}."
            );

            return redirect()->route('permintaan.index')->with('success', 'Permintaan selesai. Forum diskusi akan berakhir dalam 3 hari.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    // ─── Aksi untuk pemohon (requester) ──────────────────────────────────────
    private function handleRequesterActions(Request $request, PermintaanBarang $permintaan, $action)
    {
        if ($action === 'cancel' && in_array($permintaan->status, [
            PermintaanBarang::STATUS_MENUNGGU_APPROVAL,
            PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN,
            PermintaanBarang::STATUS_PENDING,
        ])) {
            $permintaan->update([
                'status'        => PermintaanBarang::STATUS_DIBATALKAN,
                'closed_at'     => now(),
                'forum_ends_at' => now()->addDays(3),
            ]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'batalkan',
                "Permintaan #{$permintaan->id} dibatalkan sendiri oleh pemohon " . Auth::user()->name . '.'
            );

            return redirect()->route('permintaan.index')->with('success', 'Permintaan dibatalkan.');
        }

        if ($action === 'pickup') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN, 422, 'Status tidak valid.');

            if ($permintaan->jadwal_pengambilan && \Carbon\Carbon::parse($permintaan->jadwal_pengambilan)->endOfDay()->isPast()) {
                $permintaan->update([
                    'status'      => PermintaanBarang::STATUS_PENDING,
                    'pending_at'  => now(),
                    'catatan_pic' => ($permintaan->catatan_pic ? $permintaan->catatan_pic . "\n" : '') .
                        '[Sistem] Pengambilan melewati jadwal. Menunggu penjadwalan ulang dari PIC.',
                ]);
                return redirect()->route('permintaan.index')
                    ->with('error', 'Jadwal pengambilan sudah terlewat. Permintaan masuk status Pending, hubungi PIC untuk jadwal ulang.');
            }

            $request->validate(['foto_pengambilan' => 'required|image|mimes:jpeg,png,jpg|max:5120']);
            $fotoPath = $request->file('foto_pengambilan')->store('pengambilan', 'public');

            $result = $this->fifoService->handleVerifyPickup($permintaan, $fotoPath);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'pengambilan',
                "Permintaan #{$permintaan->id}: barang diambil oleh " . Auth::user()->name . '.',
                AktivitasGudang::MUTASI_KELUAR,
                $permintaan->barang_id,
                $permintaan->barang?->nama_barang,
                $permintaan->jumlah,
                $permintaan->satuan_diminta
            );

            return $result;
        }

        if ($action === 'return') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_BARANG_DIAMBIL, 422, 'Status tidak valid.');

            $isHabis = $request->boolean('is_habis_pakai_saat_lapor');
            $rules   = [
                'foto_pengembalian'         => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'is_habis_pakai_saat_lapor' => 'required|boolean',
            ];
            if (!$isHabis) {
                $rules['kondisi_pengembalian'] = 'required|string|max:100';
                $rules['jumlah_dikembalikan']  = 'required|integer|min:1|max:' . $permintaan->jumlah;
            }
            $request->validate($rules);

            $permintaan->update([
                'status'                    => PermintaanBarang::STATUS_VERIFIKASI_PENGEMBALIAN,
                'tanggal_pengembalian'      => now(),
                'foto_pengembalian'         => $request->file('foto_pengembalian')->store('pengembalian', 'public'),
                'is_habis_pakai_saat_lapor' => $isHabis,
                'kondisi_pengembalian'      => $isHabis ? 'Habis Terpakai' : $request->kondisi_pengembalian,
                'jumlah_dikembalikan'       => $isHabis ? 0 : $request->jumlah_dikembalikan,
            ]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_PERMINTAAN,
                'lapor_kembali',
                "Permintaan #{$permintaan->id}: laporan pengembalian dikirim oleh " . Auth::user()->name . '.'
            );

            return redirect()->route('permintaan.index')->with('success', 'Laporan dikirim. Menunggu verifikasi PIC Gudang.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
