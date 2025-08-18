<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(int $user)
    {
        $follower = Auth::user()->id;

        if ($follower === $user) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($this->isFollowing($user)) {
            return back()->with('error', 'You are already following this user.');
        }

        Follow::create([
            'follower_id' => $follower,
            'following_id' => $user,
        ]);

        return back()->with('success', 'User followed successfully.');
    }

    public function destroy(int $user)
    {
        $follower = Auth::user()->id;

        if (!$this->isFollowing($user)) {
            return back()->with('error', 'You are not following this user.');
        }

        Follow::where('follower_id', $follower)
            ->where('following_id', $user)
            ->delete();

        return back()->with('success', 'User unfollowed successfully.');
    }

    public function isFollowing(int $id): bool
    {
        return Auth::user()
            ->following()
            ->where('following_id', $id)
            ->exists();
    }
}
