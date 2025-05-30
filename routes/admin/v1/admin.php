<?php

use App\Http\Controllers\Admin\v1\AdminCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\v1\AdminController;
use App\Http\Controllers\Admin\v1\AdminDepartmentController;
use App\Http\Controllers\Admin\v1\AdminTagController;
use App\Http\Controllers\Admin\v1\AdminTopicCategoryController;
use App\Http\Controllers\Admin\v1\AdminTopicTagController;
use App\Http\Controllers\admin\v1\AdminTopicController;
use App\Http\Controllers\admin\v1\AdminIdeaController;
// Route::get('/', function () {
//     return response()->json(['message' => 'Admin API V1']);
// });

// مثال روت با کنترلر
Route::get('dashboard', [AdminController::class, 'dashboard']);
Route::resource('/topics', AdminTopicController::class);
Route::resource('/categories', AdminCategoryController::class);
Route::resource('/department', AdminDepartmentController::class);
Route::resource('/topic-categories', AdminTopicCategoryController::class);
Route::resource('/topic-tags', AdminTopicTagController::class);
Route::resource('/tags', AdminTagController::class);
Route::resource('/ideas', AdminIdeaController::class);