<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSession
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if ($request->user()) {

            $sessionId = $request->session()->getId();

            $session = UserSession::where('session_id', $sessionId)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$session || $session->revoked_at !== null) {

                auth()->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect('/login')
                    ->with(
                        'error',
                        'Your session has been revoked. Please login again.'
                    );
            }

            $session->update([
                'last_activity' => now(),
            ]);
        }

        return $next($request);
    }
}