<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect user to Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback.
     */
    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(32)),
                    'last_login_at' => now(),
                ]
            );

            Auth::login($user);

            $request->session()->regenerate();

            $this->recordLoginActivity($user, $request);

            $this->createUserSession($user, $request);

            return redirect('/dashboard');

        } catch (\Exception $e) {

            Log::error('Google Login Failed', [
                'message' => $e->getMessage(),
            ]);

            return redirect('/login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }

    /**
     * Record login activity.
     */
    private function recordLoginActivity(User $user, Request $request): void
    {
        LoginActivity::create([
            'user_id' => $user->id,
            'event' => 'login',
            'provider' => 'google',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => $this->getDeviceName($request->userAgent()),
            'browser' => $this->getBrowser($request->userAgent()),
            'platform' => $this->getPlatform($request->userAgent()),
            'created_at' => now(),
        ]);
    }

    /**
     * Create active user session.
     */
    private function createUserSession(User $user, Request $request): void
    {
        UserSession::updateOrCreate(
            [
                'session_id' => $request->session()->getId(),
            ],
            [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_name' => $this->getDeviceName($request->userAgent()),
                'browser' => $this->getBrowser($request->userAgent()),
                'platform' => $this->getPlatform($request->userAgent()),
                'last_activity' => now(),
                'revoked_at' => null,
            ]
        );
    }

    /**
     * Detect device.
     */
    private function getDeviceName(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        if (stripos($userAgent, 'Mobile') !== false) {
            return 'Mobile Device';
        }

        if (stripos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        }

        return 'Desktop';
    }

    /**
     * Detect browser.
     */
    private function getBrowser(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Browser';
        }

        if (stripos($userAgent, 'Edg') !== false) {
            return 'Microsoft Edge';
        }

        if (stripos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        }

        if (stripos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        }

        if (stripos($userAgent, 'Safari') !== false) {
            return 'Safari';
        }

        return 'Unknown Browser';
    }

    /**
     * Detect operating system.
     */
    private function getPlatform(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Platform';
        }

        if (stripos($userAgent, 'Windows') !== false) {
            return 'Windows';
        }

        if (stripos($userAgent, 'Mac') !== false) {
            return 'macOS';
        }

        if (stripos($userAgent, 'Android') !== false) {
            return 'Android';
        }

        if (
            stripos($userAgent, 'iPhone') !== false ||
            stripos($userAgent, 'iPad') !== false
        ) {
            return 'iOS';
        }

        if (stripos($userAgent, 'Linux') !== false) {
            return 'Linux';
        }

        return 'Unknown Platform';
    }
}