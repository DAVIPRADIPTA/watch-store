<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ProductController;


Route::apiResource('/categories',CategoryController::class)->only('index', 'show');
Route::apiResource('/products',ProductController::class)->only('index','show');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
