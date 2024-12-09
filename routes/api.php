<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
     // Product Routes
     Route::get('/products', [ProductController::class, 'index']);
     Route::get('/products/{product}', [ProductController::class, 'show']);
     Route::post('/products', [ProductController::class, 'store'])->middleware('can:create,App\Models\Product');
 
     // Category Routes
     Route::get('/categories', [CategoryController::class, 'index']);
     Route::get('/categories/{category}', [CategoryController::class, 'show']);
 
     // Cart Routes
     Route::get('/cart', [CartController::class, 'getCart']);
     Route::post('/cart/add', [CartController::class, 'addToCart']);
     Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeFromCart']);
 
     // Order Routes
     Route::get('/orders', [OrderController::class, 'index']);
     Route::post('/orders', [OrderController::class, 'store']);
})->middleware('auth:sanctum');
// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');