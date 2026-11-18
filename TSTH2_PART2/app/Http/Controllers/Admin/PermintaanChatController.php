<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermintaanBarang;
use App\Models\ObrolanPermintaan;
use Illuminate\Support\Facades\Auth;

class PermintaanChatController extends Controller
{
    // ─── CHAT: GET ────────────────────────────────────────────────────────────
    public function getChat($id)
    {
        $permintaan = PermintaanBarang::findOrFail($id);
        $user       = Auth::user();

        if (!$user->isPicGudang() && !$user->isWebAdmin() && $permintaan->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chats = $permintaan->chats()
            ->with('user:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'message'    => $c->message,
                'user_id'    => $c->user_id,
                'user_name'  => $c->user->name,
                'user_role'  => $c->user->role,
                'is_mine'    => $c->user_id === $user->id,
                'created_at' => $c->created_at->format('d M Y, H:i'),
            ]);

        return response()->json([
            'chats'           => $chats,
            'is_forum_active' => $permintaan->isForumActive(),
            'forum_ends_at'   => $permintaan->forum_ends_at?->toISOString(),
            'days_left'       => $permintaan->forumDaysLeft(),
            'permintaan_id'     => $permintaan->id,
            'permintaan_status' => $permintaan->status,
            'barang_name'     => $permintaan->barang?->nama_barang,
            'permintaaner_name' => $permintaan->user?->name,
        ]);
    }

    // ─── CHAT: STORE ─────────────────────────────────────────────────────────
    public function storeChat(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $permintaan = PermintaanBarang::findOrFail($id);
        $user       = Auth::user();

        if (!$user->isPicGudang() && !$user->isWebAdmin() && $permintaan->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if (!$permintaan->isForumActive()) {
            return response()->json(['error' => 'Forum telah berakhir.'], 403);
        }

        $chat = ObrolanPermintaan::create([
            'permintaan_barang_id' => $permintaan->id,
            'user_id'              => $user->id,
            'message'              => $request->message,
            'channel'              => 'permintaan',
        ]);

        return response()->json([
            'id'         => $chat->id,
            'message'    => $chat->message,
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'user_role'  => $user->role,
            'is_mine'    => true,
            'created_at' => $chat->created_at->format('d M Y, H:i'),
        ]);
    }
}
