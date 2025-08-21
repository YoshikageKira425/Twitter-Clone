<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\HashtagPost;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        return Inertia::render("home", [
            "tweets" => Tweet::select(
                'id',
                'content',
                'image',
                'user_id',
                'created_at'
            )
                ->withCount([
                    'likes as likes_count',
                    'comments as comments_count',
                    'retweets as retweets_count',
                ])
                ->withExists([
                    'likes as is_liked_by_user' => fn($query) =>
                    $query->where('user_id', $userId),

                    'retweets as is_retweeted_by_user' => fn($query) =>
                    $query->where('user_id', $userId),

                    'bookmarks as is_bookmarked_by_user' => fn($query) =>
                    $query->where('user_id', $userId),
                ])
                ->with('user')
                ->latest() 
                ->get()
        ]);
    }

    public function show($id)
    {
        $userId = Auth::id();

        return Inertia::render("tweet", [
            "tweet" => Tweet::select(
                'id',
                'content',
                'image',
                'user_id',
                'created_at'
            )
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
                ->with('user')
                ->findOrFail($id),

            "comments" => Comment::where('commentable_id', $id)
                ->where('commentable_type', 'App\Models\Tweet')
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
                ->with('user')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required_without:image|string|max:280',
            'image'   => 'required_without:content|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tweet_images', 'public');
        }

        preg_match_all('/#([\p{L}\p{N}_]+)/u', $request->content ?? "", $matches);

        $newTweet = Auth::user()->tweets()->create([
            'content' => $request->content ?? "",
            'image'   => $imagePath ? asset('storage/' . $imagePath) : "",
        ]);

        if (!empty($matches[1])) {
            $hashtags = array_unique($matches[1]);

            foreach ($hashtags as $hashtag) {
                $hashtagModel = Hashtag::firstOrCreate(['name' => $hashtag]);

                HashtagPost::create([
                    'hashtag_id' => $hashtagModel->id,
                    'tweet_id' => $newTweet->id,
                ]);
            }
        }

        $newTweet->load('user');

        return response()->json([
            'tweet' => $newTweet,
            'success' => 'Tweet was made successfully.'
        ], 201);
    }
}
