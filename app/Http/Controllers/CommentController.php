<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\get;

class CommentController extends Controller
{
    public function index()
    {
        return Tweet::firstWhere("id", request()->postId)->comments()->get();
    }

    // public function show($commentId, $postId)
    // {
    //     $userId = Auth::id();

    //     return inertia()->render('comment', [
    //         'tweet' => Tweet::select(
    //             'tweets.id',
    //             'tweets.content',
    //             'tweets.image',
    //             'tweets.user_id',
    //             'tweets.created_at'
    //         )
    //             ->withExists([
    //                 'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
    //                 'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
    //                 'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
    //             ])
    //             ->withCount([
    //                 'likes as likes_count',
    //                 'comments as comments_count',
    //                 'retweets as retweets_count',
    //             ])
    //             ->with('user')
    //             ->findOrFail($postId),

    //         'current_comment' => Comment::select(
    //             'comments.id',
    //             'comments.content',
    //             'comments.user_id',
    //             'comments.commentable_id',
    //             'comments.created_at'
    //         )
    //             ->withExists([
    //                 'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
    //                 'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
    //                 'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
    //             ])
    //             ->withCount([
    //                 'likes as likes_count',
    //                 'comments as comments_count',
    //                 'retweets as retweets_count',
    //             ])
    //             ->with('user')
    //             ->findOrFail($commentId),

    //         'comments' => Comment::select(
    //             'comments.id',
    //             'comments.content',
    //             'comments.user_id',
    //             'comments.commentable_id',
    //             'comments.created_at'
    //         )
    //             ->where('comments.commentable_id', $commentId)
    //             ->where('comments.commentable_type', 'App\Models\Comment')
    //             ->withExists([
    //                 'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
    //                 'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
    //                 'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
    //             ])
    //             ->withCount([
    //                 'likes as likes_count',
    //                 'comments as comments_count',
    //                 'retweets as retweets_count',
    //             ])
    //             ->with('user')
    //             ->get(),
    //     ]);
    // }

    public function show($commentId)
    {
        $userId = Auth::id(); 

        $currentComment = Comment::withExists([
            'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
            'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
            'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
        ])
            ->withCount([
                'likes as likes_count',
                'comments as comments_count',
                'retweets as retweets_count',
            ])
            ->with('user')
            ->with('commentable') 
            ->findOrFail($commentId);

        $parent = $currentComment->commentable;

        if ($parent instanceof \App\Models\Tweet) {
            $tweet = $parent;
        } elseif ($parent instanceof \App\Models\Comment) {
            $tweet = $parent->commentable; 
        } else {
            abort(404, 'Parent of comment not found.');
        }

        return inertia()->render('comment', [
            'tweet' => $tweet->select(
                'tweets.id',
                'tweets.content',
                'tweets.image',
                'tweets.user_id',
                'tweets.created_at'
            )
                ->withExists([
                    'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
                    'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
                    'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
                ])
                ->withCount([
                    'likes as likes_count',
                    'comments as comments_count',
                    'retweets as retweets_count',
                ])
                ->with('user')
                ->findOrFail($tweet->id),
            'current_comment' => $currentComment,
            'comments' => Comment::select(
                'comments.id',
                'comments.content',
                'comments.user_id',
                'comments.commentable_id',
                'comments.created_at'
            )
                ->where('comments.commentable_id', $commentId)
                ->where('comments.commentable_type', 'App\Models\Comment')
                ->withExists([
                    'likes as is_liked_by_user' => fn($query) => $query->where('user_id', $userId),
                    'retweets as is_retweeted_by_user' => fn($query) => $query->where('user_id', $userId),
                    'bookmarks as is_bookmarked_by_user' => fn($query) => $query->where('user_id', $userId),
                ])
                ->withCount([
                    'likes as likes_count',
                    'comments as comments_count',
                    'retweets as retweets_count',
                ])
                ->with('user')
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
