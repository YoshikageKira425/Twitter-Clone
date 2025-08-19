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

        return Inertia::render('hashtag', [
            'hashtag' => $hashtag,
            'tweets' => Hashtag::where('name', $hashtag)->firstOrFail()->tweets()->withCount([
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
            ])->with('user')->latest()->get(),
        ]);
    }
}
