<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermintaanBarang;
use App\Models\Barang;
use App\Models\RequestChat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RequestController extends Controller
{
    public function index()
    {
        // Auto-update to 'Ditunda' if 'On Progress' and schedule has passed
        $overdueRequests = PermintaanBarang::where('status', 'On Progress')
            ->whereNotNull('jadwal_pengambilan')
            ->where('jadwal_pengambilan', '<', now())
            ->get();
            
        foreach ($overdueRequests as $req) {
            $req->status = 'Ditunda';
            $req->save();
        }

        $user = Auth::user();

        $query = PermintaanBarang::with(['user', 'barang', 'pic', 'staffGudang'])
            ->orderBy('created_at', 'desc');
        if ($user->role === 'agronom') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'warehouse') {
            $query->where(function($q) use ($user) {
                $q->whereNull('staff_gudang_id')
                  ->orWhere('staff_gudang_id', $user->id);
            });
        }
        
        $requests = $query->get();
        $warehouseStaff = \App\Models\User::where('role', 'warehouse')->get();
        return view('request.index', compact('requests', 'warehouseStaff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000'
        ]);

        PermintaanBarang::create([
            'user_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'catatan' => $request->catatan,
            'status' => 'Waiting'
        ]);

        return redirect()->route('request.index')->with('success', 'Permintaan barang berhasil diajukan.');
    }

    public function assign(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'pic_warehouse') {
            return redirect()->route('request.index')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'staff_gudang_id' => 'nullable|exists:users,id'
        ]);

        $permintaan = PermintaanBarang::findOrFail($id);
        $permintaan->staff_gudang_id = $request->staff_gudang_id;
        $permintaan->save();

        return redirect()->route('request.index')->with('success', 'Tugas berhasil di-assign.');
    }

    public function updateStatus(Request $request, $id)
    {
        $permintaan = PermintaanBarang::findOrFail($id);
        $user = Auth::user();

        // 1. Aksi oleh PIC Gudang (Tetapkan Jadwal / Jadwal Ulang)
        if ($user->role === 'pic_warehouse') {
            $request->validate([
                'jadwal_pengambilan' => 'required|date|after:now',
            ]);

            $permintaan->status = 'On Progress';
            $permintaan->jadwal_pengambilan = $request->jadwal_pengambilan;
            $permintaan->pic_id = $user->id;
            $permintaan->save();

            return redirect()->route('request.index')->with('success', 'Jadwal pengambilan berhasil ditetapkan.');
        }

        // 2. Aksi oleh Staff Gudang (Selesaikan & Upload Foto)
        if ($user->role === 'warehouse') {
            $request->validate([
                'foto_pengambilan' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($permintaan->staff_gudang_id && $permintaan->staff_gudang_id !== $user->id) {
                return redirect()->route('request.index')->with('error', 'Permintaan ini sudah di-assign ke staff lain.');
            }

            if ($permintaan->status !== 'On Progress') {
                return redirect()->route('request.index')->with('error', 'Status permintaan tidak valid untuk diselesaikan.');
            }

            $barang = $permintaan->barang;
            
            if ($barang->stok < $permintaan->jumlah) {
                return redirect()->route('request.index')->with('error', 'Stok barang tidak mencukupi saat ini. Tunggu restock.');
            }

            // FIFO logic across batches
            $remaining = $permintaan->jumlah;
            $batches = $barang->batches()->where('stok', '>', 0)->orderBy('tanggal_kedatangan', 'asc')->get();
            
            foreach ($batches as $batch) {
                if ($remaining <= 0) break;
                
                if ($batch->stok >= $remaining) {
                    $batch->stok -= $remaining;
                    $batch->save();
                    $remaining = 0;
                } else {
                    $remaining -= $batch->stok;
                    $batch->stok = 0;
                    $batch->save();
                }
            }

            // Update total stock
            $barang->stok -= $permintaan->jumlah;
            $barang->status = $barang->stok == 0 ? 'Habis' : ($barang->stok > 50 ? 'Tersedia' : 'Stok Kritis');
            $barang->save();

            $permintaan->status = 'Selesai';
            $permintaan->staff_gudang_id = $user->id;
            $permintaan->foto_pengambilan = $request->file('foto_pengambilan')->store('pengambilan', 'public');
            $permintaan->save();

            return redirect()->route('request.index')->with('success', 'Permintaan telah diselesaikan dan stok berhasil dipotong.');
        }

        // 3. Aksi oleh Staff Lapangan (Batalkan)
        if ($user->role === 'agronom') {
            if (in_array($permintaan->status, ['Waiting', 'On Progress'])) {
                $permintaan->status = 'Dibatalkan';
                $permintaan->save();
                return redirect()->route('request.index')->with('success', 'Permintaan berhasil dibatalkan.');
            }
            return redirect()->route('request.index')->with('error', 'Permintaan tidak dapat dibatalkan pada status saat ini.');
        }

        return redirect()->route('request.index')->with('error', 'Aksi tidak diizinkan.');
    }


}