<?php

namespace App\Http\Controllers;

use App\Services\AvatarCacheService;
use App\Services\OAuthTokenService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected AvatarCacheService $avatarCacheService;
    protected OAuthTokenService $tokenService;

    public function __construct(
        AvatarCacheService $avatarCacheService,
        OAuthTokenService $tokenService
    ) {
        $this->avatarCacheService = $avatarCacheService;
        $this->tokenService = $tokenService;
    }

    /**
     * Display profile and connected OAuth tokens.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tokens = $user->oauthTokens;

        return view('profile', compact('user', 'tokens'));
    }

    /**
     * 1-Tap Sync Social Profile and Avatar Cache
     */
    public function syncSocialProfile(Request $request)
    {
        $user = $request->user();
        $provider = $request->input('provider', 'google');

        if ($user->avatar) {
            $cachedAvatar = $this->avatarCacheService->cacheAvatar($user, $user->avatar, $provider);
        }

        // Refresh OAuth token if expired
        $token = $this->tokenService->getToken($user, $provider);
        if ($token && $token->isExpired()) {
            $this->tokenService->refreshToken($token);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Social profile and avatar synced successfully!',
                'local_avatar' => $user->local_avatar ?: $user->avatar,
            ]);
        }

        return redirect()->back()->with('success', 'Social profile synced successfully!');
    }
}