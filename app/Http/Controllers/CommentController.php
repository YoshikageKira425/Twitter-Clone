<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        return Tweet::firstWhere("id", request()->postId)->comments()->get();
    }

    public function show($commentId, $postId)
    {
        $userId = Auth::id();

        return inertia()->render('comment', [
            'tweet' => Tweet::with('user')
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
                    }
                ])
                ->findOrFail($postId),
            'current_comment' => Comment::with('user')->withCount([
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
                ])->findOrFail($commentId),
            'comments' => Comment::with("user")
                ->where('commentable_id', $commentId)
                ->where('commentable_type', Comment::class)
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
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'content' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $model = $this->getCommentableModel($request->commentable_type, $request->commentable_id);

        if (!$model) {
            return back()->with('error', 'Invalid item to comment on.');
        }

        $comment = $model->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ])->load("user");

        return response()->json([
            'message' => 'Comment added successfully.',
            'comment' => $comment,
        ]);
    }

    private function getCommentableModel(string $type, int $id)
    {
        switch ($type) {
            case 'tweets':
                return Tweet::findOrFail($id);
            case 'comments':
                return Comment::findOrFail($id);
            default:
                return null;
        }
    }

    public function destroy()
    {
        $post = Tweet::firstWhere("id", request()->postId);

        $post->comments()->where('id', request()->commentId)->delete();

        return back()->with('success', 'Comment is deleted successfully.');
    }
}
