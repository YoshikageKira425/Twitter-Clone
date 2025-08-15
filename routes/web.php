<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RetweetController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [TweetController::class, 'index'])->name('home');
    Route::resource('posts', TweetController::class)->except(['create', 'edit']);
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get("/account/{user_id}", [UserController::class, 'index'])->name("user.index");

    Route::prefix('api')->group(function () {
        Route::get('/feed', [TweetController::class, 'feed']);
        Route::apiResource('posts', TweetController::class);

        Route::resource('comments', CommentController::class)->only(['index', 'store', 'destroy']);

        Route::get('/posts/{post}/like', [LikeController::class, 'index']);
        Route::post('/posts/{post}/like', [LikeController::class, 'store']);
        Route::delete('/posts/{post}/like', [LikeController::class, 'destroy']);

        Route::get('/posts/{post}/retweet', [RetweetController::class, 'index']);
        Route::post('/posts/{post}/retweet', [RetweetController::class, 'store']);
        Route::delete('/posts/{post}/retweet', [RetweetController::class, 'destroy']);

        Route::get('/posts/{post}/bookmark', [BookmarkController::class, 'index']);
        Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'store']);
        Route::delete('/posts/{post}/bookmark', [BookmarkController::class, 'destroy']);

        Route::post('/users/{user}/follow', [FollowController::class, 'store']);
        Route::delete('/users/{user}/follow', [FollowController::class, 'destroy']);

        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
