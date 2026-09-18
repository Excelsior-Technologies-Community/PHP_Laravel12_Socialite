<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Active Sessions</title>

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

            <h2 class="fw-bold">

                <i class="bi bi-pc-display"></i>

                Active Sessions

            </h2>

            <p class="text-muted mb-0">

                Manage devices currently signed in to your account.

            </p>

        </div>

        <a
            href="{{ route('dashboard') }}"
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

    <!-- Search / Filters -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('sessions') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">

                            Search Sessions

                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Device, browser, platform, IP...">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Platform

                        </label>

                        <select
                            name="platform"
                            class="form-select">

                            <option value="">
                                All Platforms
                            </option>

                            @foreach($platforms as $platform)

                                <option
                                    value="{{ $platform }}"
                                    @selected(request('platform') === $platform)>

                                    {{ $platform }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Browser

                        </label>

                        <select
                            name="browser"
                            class="form-select">

                            <option value="">
                                All Browsers
                            </option>

                            @foreach($browsers as $browser)

                                <option
                                    value="{{ $browser }}"
                                    @selected(request('browser') === $browser)>

                                    {{ $browser }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-1 d-flex align-items-end">

                        <button
                            class="btn btn-primary w-100"
                            type="submit">

                            <i class="bi bi-search"></i>

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- Revoke all -->

    <div class="card border-danger shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-1">

                    <i class="bi bi-shield-exclamation text-danger"></i>

                    Revoke all other devices

                </h5>

                <p class="text-muted mb-0">

                    Your current browser will remain active.

                </p>

            </div>

            <form
                method="POST"
                action="{{ route('sessions.revoke.all.others') }}"
                onsubmit="return confirm(
                    'Are you sure you want to revoke all other devices?'
                );">

                @csrf

                <button
                    type="submit"
                    class="btn btn-danger">

                    <i class="bi bi-shield-x"></i>

                    Revoke All Other Devices

                </button>

            </form>

        </div>

    </div>

    <!-- Sessions -->

    <div class="row g-4">

        @forelse($sessions as $session)

            <div class="col-md-6 col-lg-4">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <div>

                                <i class="bi bi-display fs-1"></i>

                            </div>

                            @if(
                                $session->session_id ===
                                $currentSessionId
                            )

                                <span class="badge text-bg-success">

                                    Current Session

                                </span>

                            @else

                                <span class="badge text-bg-secondary">

                                    Active

                                </span>

                            @endif

                        </div>

                        <h5 class="fw-bold">

                            {{ $session->device_name ?? 'Unknown Device' }}

                        </h5>

                        <div class="small text-muted mb-1">

                            <i class="bi bi-globe2"></i>

                            {{ $session->browser ?? 'Unknown Browser' }}

                        </div>

                        <div class="small text-muted mb-1">

                            <i class="bi bi-laptop"></i>

                            {{ $session->platform ?? 'Unknown Platform' }}

                        </div>

                        <div class="small text-muted mb-1">

                            <i class="bi bi-geo-alt"></i>

                            {{ $session->ip_address ?? '-' }}

                        </div>

                        <div class="small text-muted">

                            <i class="bi bi-clock"></i>

                            Last activity:

                            {{ optional(
                                $session->last_activity
                            )->format(
                                'd M Y, h:i A'
                            ) }}

                        </div>

                        @if(
                            $session->session_id !==
                            $currentSessionId
                        )

                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'sessions.revoke',
                                        $session
                                    )
                                }}"
                                class="mt-3"
                                onsubmit="return confirm(
                                    'Revoke this session?'
                                );">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger w-100">

                                    <i class="bi bi-x-circle"></i>

                                    Revoke Session

                                </button>

                            </form>

                        @else

                            <div class="alert alert-success mt-3 mb-0">

                                <i class="bi bi-check-circle"></i>

                                This is your current session.

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="bi bi-pc-display-horizontal fs-1 text-muted"></i>

                        <h5 class="mt-3">

                            No active sessions found.

                        </h5>

                    </div>

                </div>

            </div>

        @endforelse

    </div>

</div>

</body>

</html>