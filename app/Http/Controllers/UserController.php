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
            "user" => User::with('tweets')->firstWhere('name', $username),
            "tweets" => Tweet::whereHas('user', function ($query) use ($username) {
                $query->where('name', $username);
            })->withCount([
                'comments' => function ($query) {
                    $query->where('commentable_type', Tweet::class);
                },
                'likes' => function ($query) {
                    $query->where('likeable_type', Tweet::class);
                },
                'retweets' => function ($query) {
                    $query->where('retweetable_type', Tweet::class);
                },
                'bookmarks' => function ($query) {
                    $query->where('bookmarkable_type', Tweet::class);
                }
            ])->withExists([
                'likes as is_liked_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('likeable_type', Tweet::class);
                },
                'retweets as is_retweeted_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('retweetable_type', Tweet::class);
                },
                'bookmarks as is_bookmarked_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('bookmarkable_type', Tweet::class);
                },
            ])->with('user')->latest()->get()
        ]);
    }

    public function comment($username)
    {
        $userId = Auth::id();

        return Inertia::render("account", [
            "user" => User::with('tweets')->firstWhere('name', $username),
            "tweets" => Comment::whereHas('user', function ($query) use ($username) {
                $query->where('name', $username);
            })->withCount([
                'comments' => function ($query) {
                    $query->where('commentable_type', Comment::class);
                },
                'likes' => function ($query) {
                    $query->where('likeable_type', Comment::class);
                },
                'retweets' => function ($query) {
                    $query->where('retweetable_type', Comment::class);
                },
                'bookmarks' => function ($query) {
                    $query->where('bookmarkable_type', Comment::class);
                }
            ])->withExists([
                'likes as is_liked_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('likeable_type', Comment::class);
                },
                'retweets as is_retweeted_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('retweetable_type', Comment::class);
                },
                'bookmarks as is_bookmarked_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('bookmarkable_type', Comment::class);
                },
            ])->with('user')->latest()->get()
        ]);
    }

    public function retweet($username)
    {
        $userId = Auth::id();

        return Inertia::render("account", [
            "user" => User::with('tweets')->firstWhere('name', $username),
            "tweets" => Tweet::with('user')
                ->withCount([
                    'comments',
                    'likes as likes_count',
                    'retweets as retweets_count',
                    'bookmarks as bookmarks_count'
                ])
                ->withExists([
                    'likes as is_liked_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    },
                    'retweets as is_retweeted_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    },
                    'bookmarks as is_bookmarked_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    },
                ])
                ->whereHas('retweets', function ($query) use ($username) {
                    $query->whereHas('user', function ($q) use ($username) {
                        $q->where('name', $username);
                    });
                })
                ->get()
        ]);
    }
}
