<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/signup', function () {
    return view('signup');
});
Route::get('/products/{user_id}', function ($user_id) {
    return view('products');
});
Route::get('/transactions/{user_id}', function ($user_id) {
    return view('transactions');
});