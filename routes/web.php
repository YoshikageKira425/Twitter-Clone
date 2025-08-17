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
    
    Route::get('/tweet/{id}', [TweetController::class, "show"]);
    Route::get('/tweet/{postId}/comment/{commentId}', [CommentController::class, "show"])->name('comment.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get("/account/{username}", [UserController::class, 'index'])->name("user.index");

    Route::prefix('api')->group(function () {
        Route::get('/feed', [TweetController::class, 'feed']);
        Route::resource('posts', TweetController::class)->only(['update', 'store', 'destroy']);

        Route::resource('comments', CommentController::class)->only(['index', 'store', 'destroy']);

        Route::post('/{type}/{id}/like', [LikeController::class, 'store']);
        Route::delete('/{type}/{id}/like', [LikeController::class, 'destroy']);

        Route::post('/{type}/{id}/retweet', [RetweetController::class, 'store']);
        Route::delete('/{type}/{id}/retweet', [RetweetController::class, 'destroy']);

        Route::post('/{type}/{id}/bookmark', [BookmarkController::class, 'store']);
        Route::delete('/{type}/{id}/bookmark', [BookmarkController::class, 'destroy']);

        Route::post('/users/{user}/follow', [FollowController::class, 'store']);
        Route::delete('/users/{user}/follow', [FollowController::class, 'destroy']);

        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
