<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Retweet;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index($username)
    {
        $userId = Auth::id();

        return Inertia::render("account", [
            "user" => User::with(['followers', 'following'])
                ->withExists([
                    'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
                ])
                ->where('name', $username)
                ->firstOrFail(),
            "tweets" => Tweet::whereHas('user', fn($query) => $query->where('name', $username))
                ->with('user') 
                ->withCount([
                    'likes as likes_count',
                    'comments as comments_count',
                    'retweets as retweets_count',
                ])
                ->withExists([
                    'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
                    'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
                    'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
                ])
                ->latest()
                ->get(),
        ]);
    }

    public function comment($username)
    {
        $userId = Auth::id();

        return Inertia::render("account", [
            "user" => User::with(['followers', 'following'])
                ->withExists([
                    'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
                ])
                ->where('name', $username)
                ->firstOrFail(),
            "tweets" => Comment::whereHas('user', fn($query) => $query->where('name', $username))
                ->with('user')
                ->withCount([
                    'comments as comments_count',
                    'likes as likes_count',
                    'retweets as retweets_count',
                ])
                ->withExists([
                    'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
                    'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
                    'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
                ])
                ->latest()
                ->get(),
        ]);
    }

    public function retweet($username)
    {
        $userId = Auth::id();

        return Inertia::render("account", [
            "user" => User::with(['followers', 'following'])
                ->withExists([
                    'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
                ])
                ->where('name', $username)
                ->firstOrFail(),
            "tweets" => Tweet::with('user')
                ->withCount([
                    'comments as comments_count',
                    'likes as likes_count',
                    'retweets as retweets_count',
                ])
                ->withExists([
                    'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
                    'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
                    'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
                ])
                ->whereHas('retweets', function ($query) use ($username) {
                    $query->whereHas('user', function ($q) use ($username) {
                        $q->where('name', $username);
                    });
                })
                ->latest()
                ->get(),
        ]);
    }

    public function getUser()
    {
        $search = request()->query('search');

        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(5)
            ->get();

        return response()->json($users);
    }
}
