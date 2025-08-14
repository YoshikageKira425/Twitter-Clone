<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with('user')->latest()->get();
        return view('tweets.index', compact('tweets'));
    }

    public function create()
    {
        return view('tweets.create');
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

        Auth::user()->tweets()->create([
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Tweet created successfully.');
    }

    public function show($id)
    {
        $tweet = Tweet::findOrFail($id);
        return view('tweets.show', compact('tweet'));
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
