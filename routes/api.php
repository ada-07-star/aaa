<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1/app')->group(function () {
    Route::resource('/topics', TopicController::class);
    Route::post('/greeting', function () { return 'Hello World'; });
    Route::resource('/idea', IdeaController::class);
});
