<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retweet extends Model
{
    protected $table = "retweets";

    protected $fillable = [
        'user_id',
        'post_id',
        "comment"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
