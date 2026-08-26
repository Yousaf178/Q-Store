<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return 'Laravel is working!';
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);