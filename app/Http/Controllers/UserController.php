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

        $user = User::where('name', $username)
            ->with(['followers', 'following'])
            ->withExists([
                'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
            ])
            ->with([
                'tweets' => fn($query) => $query->with('user')
                    ->withCount([
                        'likes as likes_count',
                        'comments as comments_count',
                        'retweets as retweets_count',
                    ])
                    ->withExists([
                        'likes as is_liked_by_user' => fn($q) => $q->where('user_id', $userId),
                        'retweets as is_retweeted_by_user' => fn($q) => $q->where('user_id', $userId),
                        'bookmarks as is_bookmarked_by_user' => fn($q) => $q->where('user_id', $userId),
                    ])
                    ->latest()
            ])
            ->firstOrFail();

        $tweetsWithTypes = $user->tweets->map(function ($tweet) {
            $tweet->type = 'tweet';
            return $tweet;
        });

        return Inertia::render("account", [
            "user" => $user,
            "tweets" => $tweetsWithTypes,
        ]);
    }

    public function comment($username)
    {
        $userId = Auth::id();

        $user = User::where('name', $username)
            ->with(['followers', 'following'])
            ->withExists([
                'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
            ])
            ->with([
                'comments' => fn($query) => $query->with('user')
                    ->withCount([
                        'comments as comments_count',
                        'likes as likes_count',
                        'retweets as retweets_count',
                    ])
                    ->withExists([
                        'likes as is_liked_by_user' => fn($q) => $q->where('user_id', $userId),
                        'retweets as is_retweeted_by_user' => fn($q) => $q->where('user_id', $userId),
                        'bookmarks as is_bookmarked_by_user' => fn($q) => $q->where('user_id', $userId),
                    ])
                    ->latest()
            ])
            ->firstOrFail();

        $commentsWithTypes  = $user->comments->map(function ($comment) {
            $comment->type = 'comment';
            return $comment;
        });

        return Inertia::render("account", [
            "user" => $user,
            "tweets" => $commentsWithTypes,
        ]);
    }

    public function retweet($username)
    {
        $userId = Auth::id();

        $user = User::where('name', $username)
            ->with(['followers', 'following'])
            ->withExists([
                'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
            ])
            ->with([
                'retweets' => fn($query) => $query->where('retweetable_type', Tweet::class)
                    ->with([
                        'retweetable.user',
                        'retweetable.comments' => fn($q) => $q->with('user')->withCount([
                            'likes as likes_count',
                            'retweets as retweets_count',
                            'comments as comments_count',
                        ])
                            ->withExists([
                                'likes as is_liked_by_user' => fn($likes) => $likes->where('user_id', $userId),
                                'retweets as is_retweeted_by_user' => fn($rts) => $rts->where('user_id', $userId),
                            ]),
                        'retweetable' => fn($q) => $q->withCount([
                            'likes as likes_count',
                            'comments as comments_count',
                            'retweets as retweets_count',
                        ])
                            ->withExists([
                                'likes as is_liked_by_user' => fn($likes) => $likes->where('user_id', $userId),
                                'retweets as is_retweeted_by_user' => fn($rts) => $rts->where('user_id', $userId),
                                'bookmarks as is_bookmarked_by_user' => fn($bms) => $bms->where('user_id', $userId),
                            ])
                    ])
                    ->latest()
            ])
            ->firstOrFail();

        $tweets = $user->retweets->map(function ($retweet) {
            $post = $retweet->retweetable;
            $post->type = $retweet->retweetable_type === Tweet::class ? 'tweet' : 'comment';
            return $post;
        });

        return Inertia::render("account", [
            "user" => $user,
            "tweets" => $tweets,
        ]);
    }

    public function likes($username)
    {
        $userId = Auth::id();

        $user = User::where('name', $username)
            ->with(['followers', 'following'])
            ->withExists([
                'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
            ])
            ->with([
                'likes' => fn($query) => $query->where('likeable_type', Tweet::class)
                    ->with([
                        'likeable.user',
                        'likeable.comments' => fn($q) => $q->with('user')->withCount([
                            'likes as likes_count',
                            'retweets as retweets_count',
                            'comments as comments_count',
                        ])
                            ->withExists([
                                'likes as is_liked_by_user' => fn($likes) => $likes->where('user_id', $userId),
                                'retweets as is_retweeted_by_user' => fn($rts) => $rts->where('user_id', $userId),
                            ]),
                        'likeable' => fn($q) => $q->withCount([
                            'likes as likes_count',
                            'comments as comments_count',
                            'retweets as retweets_count',
                        ])
                            ->withExists([
                                'likes as is_liked_by_user' => fn($likes) => $likes->where('user_id', $userId),
                                'retweets as is_retweeted_by_user' => fn($rts) => $rts->where('user_id', $userId),
                                'bookmarks as is_bookmarked_by_user' => fn($bms) => $bms->where('user_id', $userId),
                            ])
                    ])
                    ->latest()
            ])
            ->firstOrFail();

        $tweets = $user->likes->map(function ($like) {
            $post = $like->likeable;
            $post->type = $like->likeable_type === Tweet::class ? 'tweet' : 'comment';
            return $post;
        });

        return Inertia::render("account", [
            "user" => $user,
            "tweets" => $tweets,
        ]);
    }

    public function bookmark($username)
    {
        $userId = Auth::id();

        $user = User::where('name', $username)
            ->with(['followers', 'following'])
            ->withExists([
                'followers as is_followed' => fn($query) => $query->where('follower_id', $userId)
            ])
            ->with([
                'bookmarks' => fn($query) => $query->where('bookmarkable_type', Tweet::class)
                    ->with([
                        'bookmarkable.user',
                        'bookmarkable.comments' => fn($q) => $q->with('user')->withCount([
                            'likes as likes_count',
                            'retweets as retweets_count',
                            'comments as comments_count',
                        ])
                            ->withExists([
                                'likes as is_liked_by_user' => fn($likes) => $likes->where('user_id', $userId),
                                'retweets as is_retweeted_by_user' => fn($rts) => $rts->where('user_id', $userId),
                            ]),
                        'bookmarkable' => fn($q) => $q->withCount([
                            'likes as likes_count',
                            'comments as comments_count',
                            'retweets as retweets_count',
                        ])
                            ->withExists([
                                'likes as is_liked_by_user' => fn($likes) => $likes->where('user_id', $userId),
                                'retweets as is_retweeted_by_user' => fn($rts) => $rts->where('user_id', $userId),
                                'bookmarks as is_bookmarked_by_user' => fn($bms) => $bms->where('user_id', $userId),
                            ])
                    ])
                    ->latest()
            ])
            ->firstOrFail();

        $tweets = $user->bookmarks->map(function ($bookmark) {
            $post = $bookmark->bookmarkable;
            $post->type = $bookmark->bookmarkable_type === Tweet::class ? 'tweet' : 'comment';
            return $post;
        });

        return Inertia::render("account", [
            "user" => $user,
            "tweets" => $tweets,
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
