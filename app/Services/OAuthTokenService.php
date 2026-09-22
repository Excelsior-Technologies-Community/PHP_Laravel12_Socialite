<?php

namespace App\Services;

use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OAuthTokenService
{
    /**
     * Store or update encrypted OAuth tokens for a user
     */
    public function storeToken(User $user, string $provider, object $socialiteUser): OAuthToken
    {
        $accessToken = $socialiteUser->token ?? null;
        $refreshToken = $socialiteUser->refreshToken ?? null;
        $tokenSecret = $socialiteUser->tokenSecret ?? null;
        $expiresIn = $socialiteUser->expiresIn ?? 3600;

        $expiresAt = $expiresIn ? Carbon::now()->addSeconds((int)$expiresIn) : Carbon::now()->addDays(30);

        return OAuthToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => $provider,
            ],
            [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_secret' => $tokenSecret,
                'scopes' => property_exists($socialiteUser, 'approvedScopes') ? $socialiteUser->approvedScopes : ['profile', 'email'],
                'expires_at' => $expiresAt,
            ]
        );
    }

    /**
     * Get OAuth token for a user and provider, auto-refreshing if expired
     */
    public function getToken(User $user, string $provider): ?OAuthToken
    {
        $token = OAuthToken::where('user_id', $user->id)
            ->where('provider', $provider)
            ->first();

        if (!$token) {
            return null;
        }

        if ($token->isExpired() && $token->refresh_token) {
            $this->refreshToken($token);
        }

        return $token;
    }

    /**
     * Refresh an expired OAuth token using its refresh_token
     */
    public function refreshToken(OAuthToken $token): bool
    {
        if (!$token->refresh_token) {
            return false;
        }

        try {
            if ($token->provider === 'google') {
                $response = Http::post('https://oauth2.googleapis.com/token', [
                    'client_id' => config('services.google.client_id'),
                    'client_secret' => config('services.google.client_secret'),
                    'refresh_token' => $token->refresh_token,
                    'grant_type' => 'refresh_token',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $token->access_token = $data['access_token'];
                    $token->expires_at = Carbon::now()->addSeconds($data['expires_in'] ?? 3600);
                    $token->save();
                    return true;
                }
            } elseif ($token->provider === 'twitter') {
                $response = Http::asForm()->withBasicAuth(
                    config('services.twitter.client_id'),
                    config('services.twitter.client_secret')
                )->post('https://api.twitter.com/2/oauth2/token', [
                    'refresh_token' => $token->refresh_token,
                    'grant_type' => 'refresh_token',
                    'client_id' => config('services.twitter.client_id'),
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $token->access_token = $data['access_token'];
                    if (isset($data['refresh_token'])) {
                        $token->refresh_token = $data['refresh_token'];
                    }
                    $token->expires_at = Carbon::now()->addSeconds($data['expires_in'] ?? 7200);
                    $token->save();
                    return true;
                }
            }
        } catch (\Throwable $e) {
            Log::error("Failed to refresh OAuth token for provider {$token->provider}", [
                'user_id' => $token->user_id,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
