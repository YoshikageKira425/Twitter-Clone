<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = "bookmarks";

    protected $fillable = [
        'user_id',
        'bookmarkable_id',
        'bookmarkable_type',
        'post_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarkble()
    {
        return $this->morphTo();
    }
}
