<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LoginActivityController extends Controller
{
    /**
     * Display login activity with search and filters.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = LoginActivity::where('user_id', $user->id);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('provider', 'like', "%{$search}%")
                    ->orWhere('device_name', 'like', "%{$search}%")
                    ->orWhere('browser', 'like', "%{$search}%")
                    ->orWhere('platform', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Event filter
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Start date
        if ($request->filled('start_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->start_date
            );
        }

        // End date
        if ($request->filled('end_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->end_date
            );
        }

        $activities = $query
            ->oldest('created_at')
            ->paginate(5)
            ->withQueryString();

        $events = LoginActivity::where('user_id', $user->id)
            ->whereNotNull('event')
            ->select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event');

        return view('login-activities', compact(
            'activities',
            'events'
        ));
    }

    /**
     * Export login activities as CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $user = $request->user();

        $query = LoginActivity::where('user_id', $user->id);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('provider', 'like', "%{$search}%")
                    ->orWhere('device_name', 'like', "%{$search}%")
                    ->orWhere('browser', 'like', "%{$search}%")
                    ->orWhere('platform', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->end_date
            );
        }

        $activities = $query
            ->latest('created_at')
            ->get();

        $filename = 'login_activities_' . now()->format(
            'Y_m_d_H_i_s'
        ) . '.csv';

        return response()->streamDownload(function () use ($activities) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Event',
                'Provider',
                'Device',
                'Browser',
                'Platform',
                'IP Address',
                'Date/Time',
            ]);

            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->event,
                    $activity->provider,
                    $activity->device_name,
                    $activity->browser,
                    $activity->platform,
                    $activity->ip_address,
                    optional($activity->created_at)
                        ->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}