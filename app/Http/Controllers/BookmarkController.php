<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
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
            return back()->with('error', 'Invalid bookmarkable item.');
        }

        if ($user->bookmarks()->where('bookmarkable_id', $id)
            ->where('bookmarkable_type', get_class($model))
            ->exists()
        ) {
            return back()->with('error', 'You have already bookmarked this item.');
        }

        $user->bookmarks()->create([
            'bookmarkable_id' => $id,
            'bookmarkable_type' => get_class($model),
        ]);

        return back()->with('success', 'Item bookmarked successfully.');
    }

    public function destroy(string $type, int $id)
    {
        $user = Auth::user();

        $model = $this->getModel($type, $id);

        if (!$model) {
            return back()->with('error', 'Invalid item to unbookmark.');
        }

        $user->bookmarks()->where('bookmarkable_id', $id)
            ->where('bookmarkable_type', get_class($model))
            ->delete();

        return back()->with('success', 'Item unbookmarked successfully.');
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
