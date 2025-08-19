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

        return Inertia::render("tweet", [
            "tweet" => Tweet::with('user')
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
                        $query->where('user_id', $userId)->where('likeable_type', Tweet::class);
                    },
                    'retweets as is_retweeted_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId)->where('retweetable_type', Tweet::class);
                    },
                    'bookmarks as is_bookmarked_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId)->where('bookmarkable_type', Tweet::class);
                    },
                ])
                ->findOrFail($id),
            "comments" => Tweet::find($id)
                ->comments()
                ->with("user")
                ->withCount([
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
                ])
                ->withExists([
                    'likes as is_liked_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId)->where('likeable_type', Comment::class);
                    },
                    'retweets as is_retweeted_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId)->where('retweetable_type', Comment::class);
                    },
                    'bookmarks as is_bookmarked_by_user' => function ($query) use ($userId) {
                        $query->where('user_id', $userId)->where('bookmarkable_type', Comment::class);
                    },
                ])
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

    // public function edit($id)
    // {
    //     $tweet = Tweet::findOrFail($id);

    //     if (Auth::id() !== $tweet->user_id) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     return view('tweets.edit', compact('tweet'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $tweet = Tweet::findOrFail($id);

    //     if (Auth::id() !== $tweet->user_id) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $request->validate([
    //         'content' => 'required|string|max:280',
    //     ]);

    //     $tweet->update([
    //         'content' => $request->content,
    //     ]);

    //     return redirect()->route('tweets.show', $tweet)->with('success', 'Tweet updated successfully.');
    // }

    // public function destroy($id)
    // {
    //     $tweet = Tweet::findOrFail($id);

    //     if (Auth::id() !== $tweet->user_id) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $tweet->delete();

    //     return redirect()->route('tweets.index')->with('success', 'Tweet deleted successfully.');
    // }
}
