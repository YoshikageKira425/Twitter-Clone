<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RetweetController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RestrictUserAccess;
use App\Models\User;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [TweetController::class, 'index'])->name('home');

    Route::get('/explore', function () {
        return Inertia::render('explore');
    })->name('explore');

    Route::get('/tweet/{id}', [TweetController::class, "show"]);
    Route::get('/comment/{commentId}', [CommentController::class, "show"])->name('comment.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get("/account/{username}", [UserController::class, 'index'])->name("user.index");
    Route::get("/account/{username}/comments", [UserController::class, 'comment'])->name("user.comment");
    Route::get("/account/{username}/retweet", [UserController::class, 'retweet'])->name("user.retweet");
    Route::get("/account/{username}/like", [UserController::class, 'like'])->name("user.like");
    Route::get("/account/{username}/bookmark", [UserController::class, 'bookmark'])->name("user.bookmark");

    Route::get("/hashtag/{hashtag}", [HashtagController::class, 'show'])->name('hashtag.show');

    Route::prefix('api')->group(function () {
        Route::get('/hashtags', [HashtagController::class, 'index'])->name('hashtags.index');

        Route::get('/notifications/count', [NotificationController::class, 'getNotificationsCount']);

        Route::get('/users', [UserController::class, "getUser"]);

        Route::resource('posts', TweetController::class)->only(['update', 'store', 'destroy']);

        Route::resource('comments', CommentController::class)->only(['store', 'destroy']);

        Route::post('/{type}/{id}/like', [LikeController::class, 'store']);
        Route::delete('/{type}/{id}/like', [LikeController::class, 'destroy']);

        Route::post('/{type}/{id}/retweet', [RetweetController::class, 'store']);
        Route::delete('/{type}/{id}/retweet', [RetweetController::class, 'destroy']);

        Route::post('/{type}/{id}/bookmark', [BookmarkController::class, 'store']);
        Route::delete('/{type}/{id}/bookmark', [BookmarkController::class, 'destroy']);

        Route::post('/users/{user}/follow', [FollowController::class, 'store']);
        Route::delete('/users/{user}/follow', [FollowController::class, 'destroy']);

        Route::get('/notifications', [NotificationController::class, 'index']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/selected-user', [SessionController::class, 'storeSelectedUser']);
            Route::get('/selected-user', [SessionController::class, 'getSelectedUser']);
            Route::delete('/selected-user', [SessionController::class, 'clearSelectedSearch']);
        });
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
