<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        return Auth::user()->bookmarks()->with('post')->latest()->get();
    }

    public function store()
    {
        $user = Auth::user();

        if ($user->likes()->where('post_id', request()->postId)->exists()) {
            return back()->with('error', 'You have already liked this post.');
        }

        $user->likes()->create([
            "post_id" => request()->postId
        ]);

        return back()->with('success', 'Post liked successfully.');
    }

    public function destroy()
    {
        $user = Auth::user();

        $user->likes()->where('post_id', request()->postId)->delete();

        return back()->with('success', 'Post unliked successfully.');
    }
}
