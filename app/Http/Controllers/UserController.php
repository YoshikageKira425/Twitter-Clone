<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index($user)
    {
        return Inertia::render("account", ["user" => User::firstWhere("name", $user)]);
    }
}
