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
}
