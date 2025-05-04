<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\v1\AdminController; // مثال کنترلر
use App\Http\Controllers\admin\v1\AdminTopicController;

// Route::get('/', function () {
//     return response()->json(['message' => 'Admin API V1']);
// });

// مثال روت با کنترلر
Route::get('dashboard', [AdminController::class, 'dashboard']);
Route::get('/topics', [AdminTopicController::class, 'index'])->name('list');