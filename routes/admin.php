<?php

// Laravel admin routes
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
