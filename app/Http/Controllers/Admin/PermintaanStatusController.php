<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermintaanBarang;
use App\Services\PermintaanFifoService;
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

        if ($user->isPicGudang()) {
            return $this->handlePicActions($request, $permintaan, $action, $user);
        }

        if ($user->id === $permintaan->user_id) {
            return $this->handleRequesterActions($request, $permintaan, $action);
        }

        return redirect()->route('permintaan.index')->with('error', 'Aksi tidak diizinkan.');
    }

    private function handlePicActions(Request $request, PermintaanBarang $permintaan, $action, $user)
    {
        if ($action === 'approve') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_MENUNGGU_APPROVAL, 422, 'Status tidak valid.');
            $request->validate([
                'jadwal_pengambilan' => 'required|date|after_or_equal:today',
                'catatan_pic'        => 'required|string|max:1000',
            ], ['catatan_pic.required' => 'Catatan PIC wajib diisi saat menyetujui.']);

            $permintaan->update([
                'status'             => PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN,
                'jadwal_pengambilan' => $request->jadwal_pengambilan,
                'catatan_pic'        => $request->catatan_pic,
                'pic_id'             => $user->id,
            ]);
            return redirect()->route('permintaan.index')->with('success', 'Permintaan disetujui. Jadwal pengambilan telah ditetapkan.');
        }

        if ($action === 'reject') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_MENUNGGU_APPROVAL, 422, 'Status tidak valid.');
            $permintaan->update([
                'status'        => PermintaanBarang::STATUS_DITOLAK,
                'catatan_pic'   => $request->catatan_pic,
                'pic_id'        => $user->id,
                'closed_at'     => now(),
                'forum_ends_at' => now()->addDays(3),
            ]);
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
            ]);
            return redirect()->route('permintaan.index')->with('success', 'Jadwal pengambilan telah diperbarui.');
        }

        if ($action === 'verify_return') {
            return $this->fifoService->handleVerifyReturn($permintaan);
        }

        if ($action === 'close') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_BARANG_DIKEMBALIKAN, 422, 'Hanya permintaan yang barangnya sudah dikembalikan yang bisa diselesaikan.');
            $permintaan->closeRequest();
            return redirect()->route('permintaan.index')->with('success', 'Permintaan selesai. Forum diskusi akan berakhir dalam 3 hari.');
        }

        return back()->with('error', 'Aksi PIC tidak valid.');
    }

    private function handleRequesterActions(Request $request, PermintaanBarang $permintaan, $action)
    {
        if ($action === 'cancel' && in_array($permintaan->status, [
            PermintaanBarang::STATUS_MENUNGGU_APPROVAL,
            PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN,
        ])) {
            $permintaan->update([
                'status'        => PermintaanBarang::STATUS_DIBATALKAN,
                'closed_at'     => now(),
                'forum_ends_at' => now()->addDays(3),
            ]);
            return redirect()->route('permintaan.index')->with('success', 'Permintaan dibatalkan.');
        }

        if ($action === 'pickup') {
            abort_if($permintaan->status !== PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN, 422, 'Status tidak valid.');

            if ($permintaan->jadwal_pengambilan && \Carbon\Carbon::parse($permintaan->jadwal_pengambilan)->endOfDay()->isPast()) {
                $permintaan->update([
                    'status'      => PermintaanBarang::STATUS_PENDING,
                    'catatan_pic' => ($permintaan->catatan_pic ? $permintaan->catatan_pic . "\n" : '') .
                        '[Sistem] Pengambilan melewati jadwal pada ' . now()->format('d M Y H:i') . '. Menunggu penjadwalan ulang dari PIC.',
                ]);
                return redirect()->route('permintaan.index')
                    ->with('error', 'Jadwal pengambilan sudah terlewat. Permintaan masuk status Pending, hubungi PIC untuk jadwal ulang.');
            }

            $request->validate(['foto_pengambilan' => 'required|image|mimes:jpeg,png,jpg|max:5120']);
            $fotoPath = $request->file('foto_pengambilan')->store('pengambilan', 'public');

            return $this->fifoService->handleVerifyPickup($permintaan, $fotoPath);
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

            $updateData = [
                'status'                    => PermintaanBarang::STATUS_VERIFIKASI_PENGEMBALIAN,
                'tanggal_pengembalian'      => now(),
                'foto_pengembalian'         => $request->file('foto_pengembalian')->store('pengembalian', 'public'),
                'is_habis_pakai_saat_lapor' => $isHabis,
                'kondisi_pengembalian'      => $isHabis ? 'Habis Terpakai' : $request->kondisi_pengembalian,
                'jumlah_dikembalikan'       => $isHabis ? 0 : $request->jumlah_dikembalikan,
            ];

            $permintaan->update($updateData);
            return redirect()->route('permintaan.index')->with('success', 'Laporan dikirim. Menunggu verifikasi PIC Gudang.');
        }

        return back()->with('error', 'Aksi requester tidak valid.');
    }
}
