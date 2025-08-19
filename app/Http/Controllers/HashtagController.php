<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use Illuminate\Http\Request;

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
}
