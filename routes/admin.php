<?php

// Laravel admin routes
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', function () {
        return view('web.backend.dashboard');
    })->name('admin.dashboard');
});
