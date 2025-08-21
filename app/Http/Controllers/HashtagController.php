<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HashtagController extends Controller
{
    public function index()
    {
        $hashtags = Hashtag::select('hashtags.*')
            ->selectRaw('COUNT(hashtag_tweet.hashtag_id) as usage_count')
            ->join('hashtag_tweet', 'hashtags.id', '=', 'hashtag_tweet.hashtag_id')
            ->groupBy('hashtags.id', 'hashtags.name', 'hashtags.created_at', 'hashtags.updated_at')
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get();;

        return response()->json(['hashtags' => $hashtags]);
    }

    public function show($hashtag)
    {
        $userId = Auth::id();

        $hashtagModel = Hashtag::where('name', $hashtag)
            ->with([
                'tweets' => fn($query) => $query->withCount([
                    'comments',
                    'likes',
                    'retweets',
                    'bookmarks'
                ])
                    ->withExists([
                        'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
                        'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
                        'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
                    ])
                    ->with('user')
                    ->latest()
            ])
            ->firstOrFail();

        return Inertia::render('hashtag', [
            'hashtag' => $hashtagModel->name,
            'tweets' => $hashtagModel->tweets,
        ]);
    }
}
