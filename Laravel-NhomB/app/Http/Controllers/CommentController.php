<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostComment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000',
        ]);

        $comment = PostComment::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return response()->json(['success' => true, 'id' => $comment->comment_id]);
    }
}