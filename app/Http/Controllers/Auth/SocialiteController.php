<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use App\Models\UserSession;
use App\Services\AvatarCacheService;
use App\Services\OAuthTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    protected OAuthTokenService $tokenService;
    protected AvatarCacheService $avatarCacheService;

    public function __construct(
        OAuthTokenService $tokenService,
        AvatarCacheService $avatarCacheService
    ) {
        $this->tokenService = $tokenService;
        $this->avatarCacheService = $avatarCacheService;
    }

    /**
     * Redirect user to Google OAuth consent page with offline access for refresh tokens.
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'select_account consent',
            ])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback cleanly.
     */
    public function callback(Request $request)
    {
        try {
            try {
                $googleUser = Socialite::driver('google')->user();
            } catch (\Exception $e) {
                $googleUser = Socialite::driver('google')->stateless()->user();
            }

            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $name = $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User';
            $avatar = $googleUser->getAvatar();

            if (Auth::check()) {
                $user = Auth::user();
                $user->google_id = $googleId;
                if (empty($user->avatar)) {
                    $user->avatar = $avatar;
                }
                $user->last_login_at = now();
                $user->save();
            } else {
                $user = User::where('google_id', $googleId)->first();

                if (!$user && $email) {
                    $user = User::where('email', $email)->first();
                }

                if ($user) {
                    $user->google_id = $googleId;
                    $user->avatar = $avatar ?: $user->avatar;
                    $user->last_login_at = now();
                    $user->save();
                } else {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                        'password' => Hash::make(Str::random(32)),
                        'email_verified_at' => now(),
                        'last_login_at' => now(),
                    ]);
                }
            }

            // Store encrypted OAuth token in vault
            $this->tokenService->storeToken($user, 'google', $googleUser);

            // Cache avatar locally
            $this->avatarCacheService->cacheAvatar($user, $avatar, 'google');

            Auth::login($user);
            $request->session()->regenerate();

            $this->recordLoginActivity($user, $request, 'google');
            $this->createUserSession($user, $request);

            return redirect('/dashboard')->with('success', 'Successfully logged in with Google!');

        } catch (\Exception $e) {
            Log::error('Google Login Error', [
                'message' => $e->getMessage(),
            ]);

            return redirect('/login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Redirect user to Twitter (X).
     */
    public function redirectTwitter()
    {
        try {
            return Socialite::driver('twitter')->redirect();
        } catch (\Throwable $e) {
            return Socialite::driver('twitter-oauth-2')
                ->scopes(['users.read', 'tweet.read', 'offline.access'])
                ->redirect();
        }
    }

    /**
     * Handle Twitter (X) callback.
     */
    public function callbackTwitter(Request $request)
    {
        try {
            $twitterUser = null;

            try {
                $twitterUser = Socialite::driver('twitter')->user();
            } catch (\Throwable $e1) {
                try {
                    $twitterUser = Socialite::driver('twitter')->stateless()->user();
                } catch (\Throwable $e2) {
                    try {
                        $twitterUser = Socialite::driver('twitter-oauth-2')->user();
                    } catch (\Throwable $e3) {
                        $twitterUser = Socialite::driver('twitter-oauth-2')->stateless()->user();
                    }
                }
            }

            if (!$twitterUser) {
                throw new \RuntimeException("Unable to fetch Twitter user details.");
            }

            $twitterId = (string) $twitterUser->getId();
            $nickname = $twitterUser->getNickname() ?: 'twitter_user';
            $email = $twitterUser->getEmail() ?: ($nickname ? "{$nickname}@twitter.oauth" : "user_{$twitterId}@twitter.oauth");
            $name = $twitterUser->getName() ?: $nickname;
            $avatar = $twitterUser->getAvatar();

            if (Auth::check()) {
                $user = Auth::user();
                $user->twitter_id = $twitterId;
                $user->nickname = $nickname;
                if (empty($user->avatar)) {
                    $user->avatar = $avatar;
                }
                $user->last_login_at = now();
                $user->save();
            } else {
                $user = User::where('twitter_id', $twitterId)->first();

                if (!$user && $twitterUser->getEmail()) {
                    $user = User::where('email', $twitterUser->getEmail())->first();
                }

                if ($user) {
                    $user->twitter_id = $twitterId;
                    $user->nickname = $nickname ?: $user->nickname;
                    $user->avatar = $avatar ?: $user->avatar;
                    $user->last_login_at = now();
                    $user->save();
                } else {
                    $user = User::create([
                        'name' => $name,
                        'nickname' => $nickname,
                        'email' => $email,
                        'twitter_id' => $twitterId,
                        'avatar' => $avatar,
                        'password' => Hash::make(Str::random(32)),
                        'last_login_at' => now(),
                    ]);
                }
            }

            // Store encrypted OAuth token in vault
            $this->tokenService->storeToken($user, 'twitter', $twitterUser);

            // Cache avatar locally
            $this->avatarCacheService->cacheAvatar($user, $avatar, 'twitter');

            Auth::login($user);
            $request->session()->regenerate();

            $this->recordLoginActivity($user, $request, 'twitter');
            $this->createUserSession($user, $request);

            return redirect('/dashboard')->with('success', 'Successfully logged in with Twitter (X)!');

        } catch (\Exception $e) {
            Log::error('Twitter Login Error', [
                'message' => $e->getMessage(),
            ]);
            return redirect('/login')->with('error', 'Twitter authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Record login activity.
     */
    private function recordLoginActivity(User $user, Request $request, string $provider = 'google'): void
    {
        LoginActivity::create([
            'user_id' => $user->id,
            'event' => 'login',
            'provider' => $provider,
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

    private function getDeviceName(?string $userAgent): string
    {
        if (!$userAgent) return 'Unknown Device';
        if (stripos($userAgent, 'Mobile') !== false) return 'Mobile Device';
        if (stripos($userAgent, 'Tablet') !== false) return 'Tablet';
        return 'Desktop';
    }

    private function getBrowser(?string $userAgent): string
    {
        if (!$userAgent) return 'Unknown Browser';
        if (stripos($userAgent, 'Edg') !== false) return 'Microsoft Edge';
        if (stripos($userAgent, 'Chrome') !== false) return 'Google Chrome';
        if (stripos($userAgent, 'Firefox') !== false) return 'Mozilla Firefox';
        if (stripos($userAgent, 'Safari') !== false) return 'Safari';
        return 'Unknown Browser';
    }

    private function getPlatform(?string $userAgent): string
    {
        if (!$userAgent) return 'Unknown Platform';
        if (stripos($userAgent, 'Windows') !== false) return 'Windows';
        if (stripos($userAgent, 'Mac') !== false) return 'macOS';
        if (stripos($userAgent, 'Android') !== false) return 'Android';
        if (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) return 'iOS';
        if (stripos($userAgent, 'Linux') !== false) return 'Linux';
        return 'Unknown Platform';
    }
}