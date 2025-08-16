<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $table = "likes";

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likeble()
    {
        return $this->morphTo();
    }
}
