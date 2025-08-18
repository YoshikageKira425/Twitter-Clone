<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->likes()->with('likeable')->latest()->get());
    }

    public function store(string $type, int $id)
    {
        $user = Auth::user();

        $model = $this->getModel($type, $id);

        if (!$model) {
            return back()->with('error', 'Invalid liked item.');
        }

        Like::firstOrCreate([
            'likeable_id' => $model->id,
            'likeable_type' => $model->getMorphClass(),
            'user_id' => $user->id,
        ]);

        Notification::create([
            'to_user_id' => $model->user_id,
            'user_id' => $user->id,
            'type' => 'like',
            'data' => "You have a new like on your {$type} by {$user->name}",
        ]);

        return back()->with('success', 'Item liked successfully.');
    }

    public function destroy(string $type, int $id)
    {
        $user = Auth::user();

        $model = $this->getModel($type, $id);

        if (!$model) {
            return back()->with('error', 'Invalid item to unliked.');
        }

        $user->likes()->where('likeable_id', $model->id)
            ->where('likeable_type', $model->getMorphClass())
            ->delete();

        return back()->with('success', 'Item unliked successfully.');
    }

    private function getModel(string $type, int $id)
    {
        switch ($type) {
            case 'tweets':
                return Tweet::find($id);
            case 'comment':
                return Comment::find($id);
            default:
                return null;
        }
    }
}
