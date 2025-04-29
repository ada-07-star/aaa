<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\IdeaCommentController;
use App\Http\Controllers\v1\IdeaController;
use App\Http\Controllers\v1\TopicController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');

Route::middleware(['auth:api'])->prefix('/v1/app')->group(function () {
    Route::resource('/idea', IdeaController::class);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::resource('/topics', TopicController::class);
    Route::get('/topics/{topic}/ideas', [IdeaController::class, 'index'])->name('api.topics.ideas.index');
    Route::post('/idea/{idea}/comment', [IdeaCommentController::class, 'store']);
    Route::get('/idea/{idea}/comment', [IdeaCommentController::class, 'index']);
    Route::post('/idea/{idea}/comment_rate', [IdeaCommentController::class, 'toggleLike']);
});
