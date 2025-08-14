<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function index()
    {
        return Auth::user()->following()->latest()->get();
    }

    public function store(User $user)
    {
        $follower = Auth::user();

        if ($follower->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($follower->isFollowing($user)) {
            return back()->with('error', 'You are already following this user.');
        }

        $follower->following()->attach($user->id);

        return back()->with('success', 'User followed successfully.');
    }

    public function destroy(User $user)
    {
        $follower = Auth::user();

        if (!$follower->isFollowing($user)) {
            return back()->with('error', 'You are not following this user.');
        }

        $follower->following()->detach($user->id);

        return back()->with('success', 'User unfollowed successfully.');
    }
}
