<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\IdeaController;
use App\Http\Controllers\v1\TopicController;
use App\Http\Controllers\v1\IdeaRatingController;
use App\Http\Controllers\v1\IdeaCommentController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('/updateUser', [TopicController::class, 'updateUser']);
Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/idea', IdeaController::class);
    Route::resource('/topics', TopicController::class);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('/topics/{topic}/ideas', [IdeaController::class, 'index'])->name('api.topics.ideas.index');
    Route::post('/idea/{idea}/rate', [IdeaRatingController::class, 'rate']);

    Route::controller(IdeaCommentController::class)->group(function () {
        Route::post('/idea/{idea}/comment', 'store');
        Route::get('idea/{idea}/comment', 'index');
        Route::post('/idea/{idea}/comment_rate', 'toggleLike');
    });
});
