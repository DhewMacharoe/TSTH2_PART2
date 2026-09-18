<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DivisionChat;
use App\Models\DivisionChatRead;
use Illuminate\Support\Facades\Auth;

class DivisionChatController extends Controller
{
    public function index()
    {
        $chats = DivisionChat::with('user')->orderBy('created_at', 'asc')->get();
        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string'
        ]);

        $chat = DivisionChat::create([
            'user_id' => Auth::id(),
            'pesan' => $request->pesan
        ]);

        return response()->json($chat->load('user'));
    }

    public function markAsRead()
    {
        DivisionChatRead::updateOrCreate(
            ['user_id' => Auth::id()],
            ['read_at' => now()]
        );
        
        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $user = Auth::user();
        
        $lastRead = DivisionChatRead::where('user_id', $user->id)->value('read_at');
        
        $query = DivisionChat::where('user_id', '!=', $user->id);
        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        }
        
        $count = $query->count();
        
        return response()->json(['count' => $count]);
    }
}
