<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IdeaCommentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::resource('/idea', IdeaController::class);
Route::get('/ideas/{id}', [IdeaController::class, 'into']);

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');

Route::middleware(['auth:api'])->prefix('/v1/app')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::resource('/topics', TopicController::class);
    Route::post('/idea/{idea}/comment', [IdeaCommentController::class, 'store']);
    Route::get('/idea/{idea}/comment', [IdeaCommentController::class, 'index']);
    Route::post('/idea/{idea}/comment_rate', [IdeaCommentController::class, 'toggleLike']);
});
