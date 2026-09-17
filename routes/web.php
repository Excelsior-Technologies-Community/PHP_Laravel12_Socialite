<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\UserSessionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Google OAuth Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/login/google',
    [SocialiteController::class, 'redirect']
)->name('google.redirect');

Route::get(
    '/login/google/callback',
    [SocialiteController::class, 'callback']
)->name('google.callback');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.session'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Google Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'index']
    )->name('profile');

    /*
    |--------------------------------------------------------------------------
    | Login Activity
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/login-activities',
        [LoginActivityController::class, 'index']
    )->name('login.activities');

    /*
    |--------------------------------------------------------------------------
    | Active Sessions
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/sessions',
        [UserSessionController::class, 'index']
    )->name('sessions');

    Route::post(
        '/sessions/{session}/revoke',
        [UserSessionController::class, 'revoke']
    )->name('sessions.revoke');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [UserSessionController::class, 'logout']
    )->name('logout');
});