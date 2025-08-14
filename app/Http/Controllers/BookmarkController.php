<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        return Auth::user()->bookmarks()->with('post')->latest()->get();
    }

    public function store()
    {
        $user = Auth::user();

        if ($user->bookmarks()->where('post_id', request()->postId)->exists()) {
            return back()->with('error', 'You have already bookmarked this post.');
        }

        $user->bookmarks()->create([
            "post_id" => request()->postId
        ]);

        return back()->with('success', 'Post bookmarked successfully.');
    }

    public function destroy()
    {
        $user = Auth::user();

        $user->bookmarks()->where('post_id', request()->postId)->delete();

        return back()->with('success', 'Post unbookmarked successfully.');
    }
}
