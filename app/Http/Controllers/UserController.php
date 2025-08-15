<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index($username)
    {
        return Inertia::render("account", [
            "user" => User::with('tweets')->firstWhere('name', $username),
            "tweets" => Tweet::whereHas('user', function ($query) use ($username) {
                $query->where('name', $username);
            })->with('user')->latest()->get()
        ]);
    }
}
