<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        return Inertia::render("home", ["tweets" => Tweet::with('user')->get()]);
    }

    public function show($id)
    {
        return Inertia::render("tweet", ["tweet" => Tweet::with('user')->findOrFail($id)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tweet_images', 'public');
        }

        $newTweet = Auth::user()->tweets()->create([
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        $newTweet->load('user');

        return response()->json([
            'tweet' => $newTweet,
            'success' => 'Tweet was made successfully.'
        ], 201);
    }

    public function edit($id)
    {
        $tweet = Tweet::findOrFail($id);

        if (Auth::id() !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tweets.edit', compact('tweet'));
    }

    public function update(Request $request, $id)
    {
        $tweet = Tweet::findOrFail($id);

        if (Auth::id() !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $tweet->update([
            'content' => $request->content,
        ]);

        return redirect()->route('tweets.show', $tweet)->with('success', 'Tweet updated successfully.');
    }

    public function destroy($id)
    {
        $tweet = Tweet::findOrFail($id);

        if (Auth::id() !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $tweet->delete();

        return redirect()->route('tweets.index')->with('success', 'Tweet deleted successfully.');
    }
}
