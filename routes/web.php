<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\UserSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get(
    '/login/google',
    [SocialiteController::class, 'redirect']
)->name('google.redirect');

Route::get(
    '/login/google/callback',
    [SocialiteController::class, 'callback']
)->name('google.callback');

Route::get('/auth/callback/google', [SocialiteController::class, 'callback']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
Route::get('/google/callback', [SocialiteController::class, 'callback']);

Route::get(
    '/login/twitter',
    [SocialiteController::class, 'redirectTwitter']
)->name('twitter.redirect');

Route::get(
    '/login/twitter/callback',
    [SocialiteController::class, 'callbackTwitter']
)->name('twitter.callback');

Route::get('/auth/twitter/callback', [SocialiteController::class, 'callbackTwitter']);
Route::get('/auth/callback/twitter', [SocialiteController::class, 'callbackTwitter']);
Route::get('/twitter/callback', [SocialiteController::class, 'callbackTwitter']);


Route::middleware([
    'auth',
    'check.session'
])->group(function () {

    Route::get('/dashboard', function () {
        $user = request()->user();

        /*
        |--------------------------------------------------------------------------
        | Security Statistics
        |--------------------------------------------------------------------------
        */

        $totalLogins = $user->loginActivities()
            ->where('event', 'login')
            ->count();

        $totalLogouts = $user->loginActivities()
            ->where('event', 'logout')
            ->count();

        $sessionRevokes = $user->loginActivities()
            ->whereIn('event', [
                'session_revoked',
                'all_other_sessions_revoked',
            ])
            ->count();

        $activeSessions = $user->sessions()
            ->whereNull('revoked_at')
            ->count();

        $currentSessionId = request()
            ->session()
            ->getId();

        $currentSession = $user->sessions()
            ->where('session_id', $currentSessionId)
            ->whereNull('revoked_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        $recentActivities = $user->loginActivities()
            ->latest('created_at')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Account Security Score
        |--------------------------------------------------------------------------
        |
        | This is a simple local completeness indicator based on
        | account/session configuration. It does not call Google APIs.
        |
        */

        $score = 0;

        // Email exists
        if (!empty($user->email)) {
            $score += 20;
        }

        // Email verified
        if (!empty($user->email_verified_at)) {
            $score += 20;
        }

        // Name exists
        if (!empty($user->name)) {
            $score += 15;
        }

        // Google account linked
        if (!empty($user->google_id)) {
            $score += 20;
        }

        // Avatar exists
        if (!empty($user->avatar)) {
            $score += 10;
        }

        // Active current session
        if ($currentSession) {
            $score += 15;
        }

        return view('dashboard', compact(
            'totalLogins',
            'totalLogouts',
            'sessionRevokes',
            'activeSessions',
            'recentActivities',
            'currentSession',
            'score'
        ));
    })->name('dashboard');

    Route::get(
        '/profile',
        [ProfileController::class, 'index']
    )->name('profile');

    Route::post(
        '/profile/sync-social',
        [ProfileController::class, 'syncSocialProfile']
    )->name('profile.sync-social');

    /*
    |--------------------------------------------------------------------------
    | Login Activities
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/login-activities',
        [LoginActivityController::class, 'index']
    )->name('login.activities');

    Route::get(
        '/login-activities/export',
        [LoginActivityController::class, 'export']
    )->name('login.activities.export');

    /*
    |--------------------------------------------------------------------------
    | Sessions
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

    Route::post(
        '/sessions/revoke-all-others',
        [UserSessionController::class, 'revokeAllOthers']
    )->name('sessions.revoke.all.others');

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