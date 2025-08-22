<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use function Pest\Laravel\get;

class NotificationController extends Controller
{
    public function index()
    {
        Notification::where("to_user_id", Auth::user()->id)
            ->update(['read' => true]);

        return Inertia::render("notifications", ["notifications" => Notification::where("to_user_id", Auth::user()->id)->with('user')->get()]);
    }

    public function getNotificationsCount()
    {
        $count = Notification::where('to_user_id', Auth::id())
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
