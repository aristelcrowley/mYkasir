<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() { 
    return redirect('/products');
});

Route::get('/transaction', function () {
    return view('transaction');
});

Route::get('/product', function () {
    return view('product ');
});
