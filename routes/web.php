<?php

use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::prefix('v1/app')->group(function () {
//     Route::get('/topics', [TopicController::class, 'index']);
//     Route::post('/topics', [TopicController::class, 'store']);
// });