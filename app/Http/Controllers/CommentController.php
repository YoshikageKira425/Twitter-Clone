<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        return Tweet::firstWhere("id", request()->postId)->comments()->get();
    }

    public function store()
    {
        request()->validate([
            'postId' => "required|number",
            'content' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        Comment::created([
            "user_id" => $user->id,
            "post_id" => request()->postId,
            "content" => request()->content
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }

    public function destroy()
    {
        $post = Tweet::firstWhere("id", request()->postId);

        $post->comments()->where('id', request()->commentId)->delete();

        return back()->with('success', 'Comment is deleted successfully.');
    }
}
