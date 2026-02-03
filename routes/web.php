<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;

//Welcome
Route::get('/', function () {
    return view('welcome');
});

//Login
Route::get('/login', function () {
    return view('login');
})->name('login');

//Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

//Logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

//Google
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
