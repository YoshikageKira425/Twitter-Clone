<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;

class RetweetController extends Controller
{
    public function index()
    {
        return Auth::user()->retweets()->with('post')->latest()->get();
    }
    
    public function store(string $type, int $id)
    {
        $user = Auth::user();

        $model = $this->getModel($type, $id);

        if (!$model) {
            return back()->with('error', 'Invalid retweeted item.');
        }

        if ($user->retweets()->where('retweetable_id', $id)
            ->where('retweetable_type', get_class($model))
            ->exists()
        ) {
            return back()->with('error', 'You have already retweeted this item.');
        }

        Notification::create([
            'to_user_id' => $model->user_id,
            'user_id' => $user->id,
            'type' => 'retweet',
            'data' => "You have a new retweeted on your {$type} by {$user->name}",
        ]);

        $user->retweets()->create([
            'retweetable_id' => $id,
            'retweetable_type' => get_class($model),
        ]);

        return back()->with('success', 'Item retweeted successfully.');
    }

    public function destroy(string $type, int $id)
    {
        $user = Auth::user();

        $model = $this->getModel($type, $id);

        if (!$model) {
            return back()->with('error', 'Invalid item to unretweeted.');
        }

        $user->retweets()->where('retweetable_id', $id)
            ->where('retweetable_type', get_class($model))
            ->delete();

        return back()->with('success', 'Item unretweeted successfully.');
    }

    private function getModel(string $type, int $id)
    {
        switch ($type) {
            case 'tweets':
                return Tweet::findOrFail($id);
            case 'comment':
                return Comment::findOrFail($id);
            default:
                return null;
        }
    }
}
