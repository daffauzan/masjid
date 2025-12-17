<?php

use Illuminate\Support\Facades\Route;

// User
Route::get('/', function () {
    return view('user.index');
});

// admin
Route::get('/admin', function () {
    return view('admin.index');
});
