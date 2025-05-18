<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\AuthToken;

Route::post('/login', [LoginController::class, 'login'])->withoutMiddleware(AuthToken::class);
Route::post('/signup', [RegisterController::class, 'register'])->withoutMiddleware(AuthToken::class);

Route::middleware(AuthToken::class)->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);

    Route::get('/products/{userId}', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/product/{productId}', [ProductController::class, 'show']);
    Route::put('/products/{productId}', [ProductController::class, 'update']);
    Route::delete('/products/{productId}', [ProductController::class, 'destroy']);

    Route::get('/transactions/{userId}', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transaction/{transactionId}', [TransactionController::class, 'show']);
    Route::put('/transactions/{transactionId}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{transactionId}', [TransactionController::class, 'destroy']);

    Route::get('/products-list', [TransactionController::class, 'getProductList']);
});