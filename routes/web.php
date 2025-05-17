<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() { 
    return redirect('/products');
});

Route::get('/transactions', function () {
    return view('transactions.index');
});

Route::get('/products', function () {
    return view('products.index');
});
