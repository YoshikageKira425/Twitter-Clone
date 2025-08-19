<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashtagPost extends Model
{
    protected $table = 'hashtag_tweet';

    protected $fillable = ['hashtag_id', 'tweet_id'];
}
