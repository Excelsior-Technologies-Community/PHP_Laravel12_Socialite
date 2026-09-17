<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSessionController extends Controller
{
    /**
     * Display active sessions.
     */
    public function index(Request $request)
    {
        $currentSessionId = $request->session()->getId();

        $sessions = UserSession::where('user_id', $request->user()->id)
            ->whereNull('revoked_at')
            ->latest('last_activity')
            ->get();

        return view('sessions', compact(
            'sessions',
            'currentSessionId'
        ));
    }

    /**
     * Revoke a specific session.
     */
    public function revoke(Request $request, UserSession $session)
    {
        abort_if(
            $session->user_id !== $request->user()->id,
            403
        );

        if ($session->session_id === $request->session()->getId()) {
            return back()->with(
                'error',
                'You cannot revoke your current session. Use Logout instead.'
            );
        }

        $session->update([
            'revoked_at' => now(),
        ]);

        LoginActivity::create([
            'user_id' => $request->user()->id,
            'event' => 'session_revoked',
            'provider' => 'google',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => $this->getDeviceName($request->userAgent()),
            'browser' => $this->getBrowser($request->userAgent()),
            'platform' => $this->getPlatform($request->userAgent()),
            'created_at' => now(),
        ]);

        return back()->with(
            'success',
            'The selected device session has been revoked.'
        );
    }

    /**
     * Logout current user.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        LoginActivity::create([
            'user_id' => $user->id,
            'event' => 'logout',
            'provider' => 'google',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => $this->getDeviceName($request->userAgent()),
            'browser' => $this->getBrowser($request->userAgent()),
            'platform' => $this->getPlatform($request->userAgent()),
            'created_at' => now(),
        ]);

        UserSession::where('session_id', $request->session()->getId())
            ->update([
                'revoked_at' => now(),
            ]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'You have been logged out successfully.');
    }

    private function getDeviceName(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        return stripos($userAgent, 'Mobile') !== false
            ? 'Mobile Device'
            : 'Desktop';
    }

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