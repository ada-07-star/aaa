<?php

use App\Http\Controllers\IdeaCommentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1/app')->group(function () {
    Route::resource('/topics', TopicController::class);
    Route::resource('/idea', IdeaController::class);
    Route::post('/idea/{idea}/comment', [IdeaCommentController::class, 'store'])
    ->name('api.v1.app.idea.comment.store');

});
