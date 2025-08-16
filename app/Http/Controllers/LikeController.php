<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        return Auth::user()->bookmarks()->with('post')->latest()->get();
    }

    public function store(string $type, int $id)
    {
        $user = Auth::user();

        $model = $this->getModel($type, $id);

        if (!$model) {
            return back()->with('error', 'Invalid likeable item.');
        }

        if ($user->likes()->where('bookmarkable_id', $model->id)
            ->where('bookmarkable_type', get_class($model))
            ->exists()
        ) {
            return back()->with('error', 'You have already liked this item.');
        }

        $user->likes()->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model),
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

        $user->bookmarks()->where('likeable_id', $model->id)
            ->where('likeable_type', get_class($model))
            ->delete();

        return back()->with('success', 'Item unliked successfully.');
    }

    private function getModel(string $type, int $id)
    {
        switch ($type) {
            case 'tweets':
                return Tweet::findOrFail($id);
            case 'comments':
                return Comment::findOrFail($id);
            default:
                return null;
        }
    }
}
