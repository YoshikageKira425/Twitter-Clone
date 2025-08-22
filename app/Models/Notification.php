<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notifications";

    protected $fillable = [
        'to_user_id',
        'user_id',
        'type',
        'data',
        'read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
