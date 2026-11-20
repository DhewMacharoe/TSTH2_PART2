<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(
        Request $request,
        Logbook $logbook
    )
    {
        /*
        | Hanya Pimpinan yang dapat memberikan komentar
        */

        if (auth()->user()->role !== 'leader') {

            abort(403);

        }


        $validated = $request->validate([

            'comment' => 'required|string|max:1000',

        ]);


        Comment::create([

            'logbook_id' => $logbook->id,

            'user_id' => auth()->id(),

            'comment' => $validated['comment'],

        ]);


        return redirect()
            ->route(
                'leader.logbooks.show',
                $logbook
            )
            ->with(
                'success',
                'Komentar berhasil ditambahkan.'
            );
    }
}