<?php

use App\Http\Controllers\v1\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user/v1', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/app/v1')->group(function () {
        Route::get('/topics', [TopicController::class, 'index']);
        Route::post('/topics', [TopicController::class, 'store']);
        Route::get('/topics/{id}', [TopicController::class, 'show']);
    });