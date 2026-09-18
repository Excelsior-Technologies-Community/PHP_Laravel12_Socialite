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
        $user = $request->user();

        $currentSessionId = $request->session()->getId();

        $query = UserSession::where('user_id', $user->id)
            ->whereNull('revoked_at');

        // Active-session search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                    ->orWhere('browser', 'like', "%{$search}%")
                    ->orWhere('platform', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        // Platform filter
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        // Browser filter
        if ($request->filled('browser')) {
            $query->where('browser', $request->browser);
        }

        $sessions = $query
            ->latest('last_activity')
            ->get();

        $platforms = UserSession::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->whereNotNull('platform')
            ->select('platform')
            ->distinct()
            ->orderBy('platform')
            ->pluck('platform');

        $browsers = UserSession::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->whereNotNull('browser')
            ->select('browser')
            ->distinct()
            ->orderBy('browser')
            ->pluck('browser');

        return view('sessions', compact(
            'sessions',
            'currentSessionId',
            'platforms',
            'browsers'
        ));
    }

    /**
     * Revoke a specific session.
     */
    public function revoke(
        Request $request,
        UserSession $session
    ) {
        abort_if(
            $session->user_id !== $request->user()->id,
            403
        );

        if (
            $session->session_id ===
            $request->session()->getId()
        ) {
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
            'device_name' => $this->getDeviceName(
                $request->userAgent()
            ),
            'browser' => $this->getBrowser(
                $request->userAgent()
            ),
            'platform' => $this->getPlatform(
                $request->userAgent()
            ),
            'created_at' => now(),
        ]);

        return back()->with(
            'success',
            'The selected device session has been revoked.'
        );
    }

    /**
     * Revoke all other devices.
     */
    public function revokeAllOthers(Request $request)
    {
        $user = $request->user();

        $currentSessionId = $request->session()->getId();

        $otherSessions = UserSession::where(
            'user_id',
            $user->id
        )
            ->whereNull('revoked_at')
            ->where(
                'session_id',
                '!=',
                $currentSessionId
            )
            ->get();

        $count = $otherSessions->count();

        if ($count > 0) {
            UserSession::where(
                'user_id',
                $user->id
            )
                ->whereNull('revoked_at')
                ->where(
                    'session_id',
                    '!=',
                    $currentSessionId
                )
                ->update([
                    'revoked_at' => now(),
                ]);

            LoginActivity::create([
                'user_id' => $user->id,
                'event' => 'all_other_sessions_revoked',
                'provider' => 'google',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_name' => $this->getDeviceName(
                    $request->userAgent()
                ),
                'browser' => $this->getBrowser(
                    $request->userAgent()
                ),
                'platform' => $this->getPlatform(
                    $request->userAgent()
                ),
                'created_at' => now(),
            ]);
        }

        return back()->with(
            'success',
            $count > 0
                ? "{$count} other device session(s) have been revoked."
                : 'There are no other active device sessions.'
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
            'device_name' => $this->getDeviceName(
                $request->userAgent()
            ),
            'browser' => $this->getBrowser(
                $request->userAgent()
            ),
            'platform' => $this->getPlatform(
                $request->userAgent()
            ),
            'created_at' => now(),
        ]);

        UserSession::where(
            'session_id',
            $request->session()->getId()
        )->update([
            'revoked_at' => now(),
        ]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')
            ->with(
                'success',
                'You have been logged out successfully.'
            );
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