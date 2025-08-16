<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retweet extends Model
{
    protected $table = "retweets";

    protected $fillable = [
        'user_id',
        'retweetable_id',
        'retweetable_type',
        "comment"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function retweetable()
    {
        return $this->morphTo();
    }
}
