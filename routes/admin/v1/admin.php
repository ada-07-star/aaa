<?php

use App\Http\Controllers\Admin\v1\AdminCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\v1\AdminController; // مثال کنترلر
use App\Http\Controllers\Admin\v1\AdminDepartmentController;
use App\Http\Controllers\admin\v1\AdminTopicController;

// Route::get('/', function () {
//     return response()->json(['message' => 'Admin API V1']);
// });

// مثال روت با کنترلر
Route::get('dashboard', [AdminController::class, 'dashboard']);
Route::resource('/topics', AdminTopicController::class);
    Route::resource('/categories', AdminCategoryController::class);
    Route::resource('/department', AdminDepartmentController::class);