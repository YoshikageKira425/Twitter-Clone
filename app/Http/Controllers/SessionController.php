<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function getSelectedUser()
    {
        $users_id = request()->session()->get('user_search', []);

        if (empty($users_id)) {
            return response()->json([]);
        }

        $users = User::whereIn('id', $users_id)
            ->get();

        return response()->json($users);
    }
    public function storeSelectedUser()
    {
        request()->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $users_id = request()->session()->get('user_search', []);

        array_push($users_id, request('id'));

        $users_id = array_unique($users_id);

        request()->session()->put('user_search', $users_id);
    }
    public function clearSelectedSearch()
    {
        request()->session()->forget('user_search');
    }
}
