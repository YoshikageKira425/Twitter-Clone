<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RetweetController extends Controller
{
    public function index()
    {
        return Auth::user()->retweets()->with('post')->latest()->get();
    }

    public function store()
    {
        $user = Auth::user();

        if ($user->retweets()->where('post_id', request()->postId)->exists()) {
            return back()->with('error', 'You have already retweeted this post.');
        }

        $user->retweets()->create([
            "post_id" => request()->postId
        ]);

        return back()->with('success', 'Post retweeted successfully.');
    }

    public function destroy()
    {
        $user = Auth::user();

        $user->retweets()->where('post_id', request()->postId)->delete();

        return back()->with('success', 'Post unretweeted successfully.');
    }
}
