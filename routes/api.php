<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\AuthToken;

Route::post('/login', [LoginController::class, 'login'])->withoutMiddleware(AuthToken::class);
Route::post('/signup', [RegisterController::class, 'register'])->withoutMiddleware(AuthToken::class);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware(AuthToken::class)->group(function () {
    Route::get('/products/{userId}', [ProductController::class, 'index']);
    Route::post('/products/{userId}', [ProductController::class, 'store']);
    Route::get('/products/{userId}/{id}', [ProductController::class, 'show']);
    Route::put('/products/{userId}/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{userId}/{id}', [ProductController::class, 'destroy']);

    Route::get('/transactions/{userId}', [TransactionController::class, 'index']);
    Route::post('/transactions/{userId}', [TransactionController::class, 'store']);
    Route::get('/transactions/{userId}/{id}', [TransactionController::class, 'show']);
    Route::put('/transactions/{userId}/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{userId}/{id}', [TransactionController::class, 'destroy']);
});