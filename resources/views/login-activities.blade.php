<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Login Activities</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-clock-history"></i>
                    Login Activities
                </h2>

                <p class="text-muted mb-0">
                    Search and review your account activity.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="btn btn-outline-dark">
                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Filters -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <form method="GET"
                    action="{{ route('login.activities') }}">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Event, device, browser, IP...">

                        </div>

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Event
                            </label>

                            <select
                                name="event"
                                class="form-select">

                                <option value="">
                                    All Events
                                </option>

                                @foreach($events as $event)

                                <option
                                    value="{{ $event }}"
                                    @selected(request('event')===$event)>
                                    {{ ucwords(str_replace('_', ' ', $event)) }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Start Date
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                value="{{ request('start_date') }}"
                                class="form-control">

                        </div>

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                End Date
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                value="{{ request('end_date') }}"
                                class="form-control">

                        </div>

                        <div class="col-md-2 d-flex align-items-end">

                            <div class="d-flex gap-2 w-100">

                                <button
                                    type="submit"
                                    class="btn btn-primary flex-fill">

                                    <i class="bi bi-search"></i>

                                    Search

                                </button>

                                <a
                                    href="{{ route('login.activities') }}"
                                    class="btn btn-outline-secondary">

                                    <i class="bi bi-x-lg"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- Export -->

        <div class="d-flex justify-content-between mb-3">

            <div class="text-muted">

                Showing
                <strong>{{ $activities->total() }}</strong>
                matching activities.

            </div>

            <a
                href="{{ route('login.activities.export', request()->query()) }}"
                class="btn btn-success">

                <i class="bi bi-file-earmark-spreadsheet"></i>

                Export CSV

            </a>

        </div>

        <!-- Table -->

        <div class="card shadow-sm border-0">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>Event</th>

                                <th>Provider</th>

                                <th>Device</th>

                                <th>Browser</th>

                                <th>Platform</th>

                                <th>IP Address</th>

                                <th>Date / Time</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($activities as $activity)

                            <tr>

                                <td>

                                    @php
                                    $badge = match ($activity->event) {
                                    'login' => 'success',
                                    'logout' => 'secondary',
                                    'session_revoked' => 'warning',
                                    'all_other_sessions_revoked' => 'danger',
                                    default => 'primary',
                                    };
                                    @endphp

                                    <span class="badge text-bg-{{ $badge }}">

                                        {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $activity->event
                                        )
                                    ) }}

                                    </span>

                                </td>

                                <td>
                                    {{ $activity->provider ?? 'Local' }}
                                </td>

                                <td>
                                    {{ $activity->device_name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $activity->browser ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $activity->platform ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $activity->ip_address ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($activity->created_at)
                                    ->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5">

                                    <i class="bi bi-inbox fs-1 text-muted"></i>

                                    <div class="mt-2 text-muted">
                                        No login activity found.
                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="mt-4 d-flex justify-content-center">
            @if ($activities->hasPages())
            <nav aria-label="Login activity pagination">
                <ul class="pagination mb-0">

                    {{-- Page Numbers Only --}}
                    @foreach ($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $activities->currentPage() ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </li>
                    @endforeach

                </ul>
            </nav>
            @endif
        </div>


    </div>

</body>

</html>