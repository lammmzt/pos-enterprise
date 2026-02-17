<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('LandingPage/HomeView');
    });
    
Route::get('/login', function () {
    return view('LandingPage/LoginView');
});