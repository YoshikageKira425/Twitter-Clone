<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        return Inertia::render("home", ["tweets" => Tweet::with('user')
            ->withCount([
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
                },
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
            ->get()]);
    }

    public function show($id)
    {
        $userId = Auth::id();
        
        return Inertia::render("tweet", ["tweet" => $tweet = Tweet::with('user')
            ->withCount([
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
            ->findOrFail($id)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tweet_images', 'public');
        }

        $newTweet = Auth::user()->tweets()->create([
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        $newTweet->load('user');

        return response()->json([
            'tweet' => $newTweet,
            'success' => 'Tweet was made successfully.'
        ], 201);
    }

    public function edit($id)
    {
        $tweet = Tweet::findOrFail($id);

        if (Auth::id() !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tweets.edit', compact('tweet'));
    }

    public function update(Request $request, $id)
    {
        $tweet = Tweet::findOrFail($id);

        if (Auth::id() !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $tweet->update([
            'content' => $request->content,
        ]);

        return redirect()->route('tweets.show', $tweet)->with('success', 'Tweet updated successfully.');
    }

    public function destroy($id)
    {
        $tweet = Tweet::findOrFail($id);

        if (Auth::id() !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $tweet->delete();

        return redirect()->route('tweets.index')->with('success', 'Tweet deleted successfully.');
    }
}
