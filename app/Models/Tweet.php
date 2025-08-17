<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $table = "tweets";

    protected $fillable = [
        'user_id',
        'content',
        "image"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function retweets()
    {
        return $this->morphMany(Retweet::class, 'retweetable');
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
