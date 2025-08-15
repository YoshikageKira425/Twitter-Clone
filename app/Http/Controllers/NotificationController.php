<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        return Inertia::render("notifications", ["notificastions" => Notification::whereAll("user_id", Auth::user()->id)]);
    }

    public function markAsRead() {}
}
