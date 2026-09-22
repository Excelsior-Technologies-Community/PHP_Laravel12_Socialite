<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Security Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>

        body {
            background:
                linear-gradient(
                    135deg,
                    #f8f9fa 0%,
                    #eef2ff 50%,
                    #f8f9fa 100%
                );
            min-height: 100vh;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
        }

        .hero-card {
            background:
                linear-gradient(
                    135deg,
                    #212529,
                    #343a40
                );
            color: white;
            border-radius: 24px;
            overflow: hidden;
            position: relative;
        }

        .hero-card::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -70px;
            top: -80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .hero-card::before {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            right: 120px;
            bottom: -100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .profile-avatar {
            width: 86px;
            height: 86px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.8);
        }

        .default-avatar {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            border: 4px solid rgba(255,255,255,0.6);
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .feature-card {
            border: 0;
            border-radius: 20px;
            height: 100%;
            transition: all 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .security-card {
            border: 0;
            border-radius: 20px;
        }

        .activity-row {
            transition: background 0.15s ease;
        }

        .activity-row:hover {
            background: #f8f9fa;
        }

        .activity-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress {
            background: #e9ecef;
        }

        .footer {
            color: #6c757d;
            font-size: 13px;
        }

    </style>

</head>

<body>

@php
    $user = auth()->user();
@endphp

<!-- ========================================================= -->
<!-- NAVBAR -->
<!-- ========================================================= -->

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="{{ route('dashboard') }}">

            <i class="bi bi-shield-lock-fill me-2"></i>

            Security Dashboard

        </a>

        <div class="d-flex align-items-center gap-2">

            <a
                href="{{ route('profile') }}"
                class="btn btn-outline-dark btn-sm">

                <i class="bi bi-person-circle me-1"></i>

                Profile

            </a>

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="d-inline">

                @csrf

                <button
                    type="submit"
                    class="btn btn-dark btn-sm">

                    <i class="bi bi-box-arrow-right me-1"></i>

                    Logout

                </button>

            </form>

        </div>

    </div>

</nav>


<!-- ========================================================= -->
<!-- MAIN -->
<!-- ========================================================= -->

<div class="container py-5">

    <!-- Flash Messages -->

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <!-- ===================================================== -->
    <!-- HERO -->
    <!-- ===================================================== -->

    <div class="hero-card shadow mb-4">

        <div class="card-body p-4 p-lg-5 position-relative">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-center gap-4">

                            @if($user->local_avatar || $user->avatar)
                                <img src="{{ $user->local_avatar ?: $user->avatar }}" alt="Profile" class="profile-avatar">
                            @else
                                <div class="default-avatar">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif

                            <div>
                                <div class="small text-white-50 mb-1">
                                    Welcome back
                                </div>
                                <h1 class="fw-bold mb-1">
                                    {{ $user->name }}
                                </h1>
                                <div class="text-white-50 small mb-2">
                                    {{ $user->email }}
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @if($user->google_id)
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-google me-1 text-danger"></i>
                                            Google Connected
                                        </span>
                                    @endif

                                    @if($user->twitter_id)
                                        <span class="badge bg-dark text-white border border-secondary">
                                            <i class="bi bi-twitter-x me-1"></i>
                                            Twitter (X) Connected
                                        </span>
                                    @endif

                                    @if($user->local_avatar)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Avatar Cached Locally
                                        </span>
                                    @endif
                                </div>
                            </div>

                    </div>

                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <div class="small text-white-50">
                        Account Security
                    </div>

                    <div class="display-4 fw-bold">

                        {{ $score }}%

                    </div>

                    <div class="small text-white-50">

                        Security configuration indicator

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- SECURITY STATISTICS -->
    <!-- ===================================================== -->

    <div class="row g-4 mb-4">

        <!-- Total Logins -->

        <div class="col-md-6 col-xl-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Logins
                            </div>

                            <div class="display-6 fw-bold mt-1">

                                {{ $totalLogins }}

                            </div>

                        </div>

                        <div class="stat-icon bg-primary-subtle text-primary">

                            <i class="bi bi-box-arrow-in-right"></i>

                        </div>

                    </div>

                    <div class="small text-muted mt-3">

                        Recorded login events

                    </div>

                </div>

            </div>

        </div>


        <!-- Total Logouts -->

        <div class="col-md-6 col-xl-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Logouts
                            </div>

                            <div class="display-6 fw-bold mt-1">

                                {{ $totalLogouts }}

                            </div>

                        </div>

                        <div class="stat-icon bg-secondary-subtle text-secondary">

                            <i class="bi bi-box-arrow-right"></i>

                        </div>

                    </div>

                    <div class="small text-muted mt-3">

                        Recorded logout events

                    </div>

                </div>

            </div>

        </div>


        <!-- Active Devices -->

        <div class="col-md-6 col-xl-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Active Devices
                            </div>

                            <div class="display-6 fw-bold mt-1">

                                {{ $activeSessions }}

                            </div>

                        </div>

                        <div class="stat-icon bg-success-subtle text-success">

                            <i class="bi bi-pc-display"></i>

                        </div>

                    </div>

                    <div class="small text-muted mt-3">

                        Active account sessions

                    </div>

                </div>

            </div>

        </div>


        <!-- Session Revokes -->

        <div class="col-md-6 col-xl-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Session Revokes
                            </div>

                            <div class="display-6 fw-bold mt-1">

                                {{ $sessionRevokes }}

                            </div>

                        </div>

                        <div class="stat-icon bg-danger-subtle text-danger">

                            <i class="bi bi-shield-x"></i>

                        </div>

                    </div>

                    <div class="small text-muted mt-3">

                        Recorded session revocations

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- OAUTH TOKEN VAULT & 1-TAP PROFILE SYNC -->
    <!-- ===================================================== -->

    <div class="card security-card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-key-fill text-primary me-2"></i>
                        OAuth Access Token Vault & Auto-Refresh Engine
                    </h5>
                    <small class="text-muted">
                        Encrypted token storage with background auto-refresh & 1-tap avatar caching
                    </small>
                </div>
                <form method="POST" action="{{ route('profile.sync-social') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        1-Tap Sync Profile & Avatar
                    </button>
                </form>
            </div>

            <div class="row g-3">
                @forelse($user->oauthTokens as $token)
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-uppercase">
                                    @if($token->provider === 'google')
                                        <i class="bi bi-google text-danger me-1"></i> Google OAuth
                                    @elseif($token->provider === 'twitter')
                                        <i class="bi bi-twitter-x text-dark me-1"></i> Twitter (X) OAuth
                                    @else
                                        <i class="bi bi-shield-lock me-1"></i> {{ $token->provider }}
                                    @endif
                                </span>
                                <span class="badge {{ $token->isExpired() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                    {{ $token->isExpired() ? '🔴 Expired (Auto-Refreshing)' : '🟢 Encrypted & Active' }}
                                </span>
                            </div>
                            <div class="small text-muted mb-1 font-monospace">
                                Token Status: <strong class="text-dark">256-bit AES Encrypted Vault</strong>
                            </div>
                            <div class="small text-muted">
                                Expires: {{ $token->expires_at ? $token->expires_at->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            No active OAuth tokens in vault. Login via Google or Twitter (X) to store encrypted OAuth tokens.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===================================================== -->
    <!-- ACCOUNT SECURITY -->
    <!-- ===================================================== -->

    <div class="card security-card shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h4 class="fw-bold mb-2">

                        <i class="bi bi-shield-check me-2"></i>

                        Account Security

                    </h4>

                    <p class="text-muted mb-4">

                        This indicator is calculated from the account
                        information and current session configuration
                        available in this application.

                    </p>

                    <div class="progress"
                         style="height: 13px;">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $score }}%"
                            aria-valuenow="{{ $score }}"
                            aria-valuemin="0"
                            aria-valuemax="100">

                        </div>

                    </div>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="row g-2">

                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="small text-muted">
                                    Email
                                </div>

                                <div class="fw-semibold">

                                    @if($user->email)

                                        <span class="text-success">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Available

                                        </span>

                                    @else

                                        <span class="text-danger">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Missing

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="small text-muted">
                                    Verification
                                </div>

                                <div class="fw-semibold">

                                    @if($user->email_verified_at)

                                        <span class="text-success">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Verified

                                        </span>

                                    @else

                                        <span class="text-warning">

                                            <i class="bi bi-exclamation-circle-fill"></i>

                                            Pending

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="small text-muted">
                                    Google
                                </div>

                                <div class="fw-semibold">

                                    @if($user->google_id)

                                        <span class="text-success">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Connected

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            Not connected

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="small text-muted">
                                    Current Session
                                </div>

                                <div class="fw-semibold">

                                    @if($currentSession)

                                        <span class="text-success">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="text-danger">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Not Found

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- FEATURE CARDS -->
    <!-- ===================================================== -->

    <div class="row g-4 mb-4">

        <!-- Google Profile -->

        <div class="col-md-4">

            <div class="card feature-card shadow-sm">

                <div class="card-body p-4">

                    <div class="feature-icon bg-primary-subtle text-primary mb-4">

                        <i class="bi bi-google"></i>

                    </div>

                    <h5 class="fw-bold">

                        Google Profile

                    </h5>

                    <p class="text-muted">

                        View your connected Google account
                        information and profile details.

                    </p>

                    <a
                        href="{{ route('profile') }}"
                        class="btn btn-outline-primary">

                        View Profile

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>


        <!-- Login Activity -->

        <div class="col-md-4">

            <div class="card feature-card shadow-sm">

                <div class="card-body p-4">

                    <div class="feature-icon bg-success-subtle text-success mb-4">

                        <i class="bi bi-clock-history"></i>

                    </div>

                    <h5 class="fw-bold">

                        Login Activity

                    </h5>

                    <p class="text-muted">

                        Search login events, filter activity by
                        event/date and export records to CSV.

                    </p>

                    <a
                        href="{{ route('login.activities') }}"
                        class="btn btn-outline-success">

                        View Activity

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>


        <!-- Active Devices -->

        <div class="col-md-4">

            <div class="card feature-card shadow-sm">

                <div class="card-body p-4">

                    <div class="feature-icon bg-danger-subtle text-danger mb-4">

                        <i class="bi bi-pc-display"></i>

                    </div>

                    <h5 class="fw-bold">

                        Active Devices

                    </h5>

                    <p class="text-muted">

                        Search active sessions and revoke
                        individual or all other devices.

                    </p>

                    <a
                        href="{{ route('sessions') }}"
                        class="btn btn-outline-danger">

                        Manage Devices

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- CURRENT SESSION -->
    <!-- ===================================================== -->

    <div class="card security-card shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-laptop me-2"></i>

                        Current Session

                    </h5>

                    <small class="text-muted">

                        Information about the browser currently being used.

                    </small>

                </div>

                <span class="badge text-bg-success">

                    <i class="bi bi-circle-fill me-1"
                       style="font-size: 7px;"></i>

                    Active

                </span>

            </div>

            @if($currentSession)

                <div class="row g-3">

                    <div class="col-md-3">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted">
                                Device
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $currentSession->device_name
                                    ?? 'Unknown Device' }}

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted">
                                Browser
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $currentSession->browser
                                    ?? 'Unknown Browser' }}

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted">
                                Platform
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $currentSession->platform
                                    ?? 'Unknown Platform' }}

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted">
                                IP Address
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $currentSession->ip_address ?? '-' }}

                            </div>

                        </div>

                    </div>

                </div>

                <div class="small text-muted mt-3">

                    <i class="bi bi-clock me-1"></i>

                    Last activity:

                    {{ optional(
                        $currentSession->last_activity
                    )->format(
                        'd M Y, h:i A'
                    ) }}

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    Current session information is not available.

                </div>

            @endif

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- RECENT ACTIVITY -->
    <!-- ===================================================== -->

    <div class="card security-card shadow-sm mb-4">

        <div class="card-header bg-white border-0 p-4 pb-2">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-activity me-2"></i>

                        Recent Activity

                    </h5>

                    <small class="text-muted">

                        Your latest account security events.

                    </small>

                </div>

                <a
                    href="{{ route('login.activities') }}"
                    class="btn btn-sm btn-outline-primary">

                    View All

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>

        </div>

        <div class="card-body p-0">

            @forelse($recentActivities as $activity)

                @php

                    $event = $activity->event;

                    $eventLabel = ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $event
                        )
                    );

                    $eventIcon = match ($event) {

                        'login' =>
                            'bi-box-arrow-in-right',

                        'logout' =>
                            'bi-box-arrow-right',

                        'session_revoked' =>
                            'bi-shield-x',

                        'all_other_sessions_revoked' =>
                            'bi-shield-exclamation',

                        default =>
                            'bi-activity',

                    };

                    $eventClass = match ($event) {

                        'login' =>
                            'bg-success-subtle text-success',

                        'logout' =>
                            'bg-secondary-subtle text-secondary',

                        'session_revoked',
                        'all_other_sessions_revoked' =>
                            'bg-danger-subtle text-danger',

                        default =>
                            'bg-primary-subtle text-primary',

                    };

                @endphp

                <div class="activity-row d-flex align-items-center p-4 border-top">

                    <div class="activity-icon {{ $eventClass }} me-3">

                        <i class="bi {{ $eventIcon }}"></i>

                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold">

                            {{ $eventLabel }}

                        </div>

                        <div class="small text-muted">

                            {{ $activity->device_name
                                ?? 'Unknown Device' }}

                            @if($activity->browser)

                                · {{ $activity->browser }}

                            @endif

                            @if($activity->platform)

                                · {{ $activity->platform }}

                            @endif

                        </div>

                    </div>

                    <div class="text-end">

                        <div class="small fw-semibold">

                            {{ optional(
                                $activity->created_at
                            )->format(
                                'd M Y'
                            ) }}

                        </div>

                        <div class="small text-muted">

                            {{ optional(
                                $activity->created_at
                            )->format(
                                'h:i A'
                            ) }}

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5 px-3">

                    <i class="bi bi-clock-history fs-1 text-muted"></i>

                    <h6 class="fw-bold mt-3">

                        No Recent Activity

                    </h6>

                    <p class="text-muted mb-0">

                        Your account activity will appear here.

                    </p>

                </div>

            @endforelse

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- SECURITY ACTIONS -->
    <!-- ===================================================== -->

    <div class="card security-card shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h5 class="fw-bold">

                        <i class="bi bi-shield-lock me-2"></i>

                        Security Management

                    </h5>

                    <p class="text-muted mb-0">

                        Review account activity and manage active
                        devices from the security management pages.

                    </p>

                </div>

                <div class="col-lg-4 mt-3 mt-lg-0">

                    <div class="d-flex justify-content-lg-end gap-2 flex-wrap">

                        <a
                            href="{{ route('login.activities') }}"
                            class="btn btn-outline-dark">

                            <i class="bi bi-clock-history me-1"></i>

                            Activity

                        </a>

                        <a
                            href="{{ route('sessions') }}"
                            class="btn btn-dark">

                            <i class="bi bi-pc-display me-1"></i>

                            Devices

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- ACCOUNT INFORMATION -->
    <!-- ===================================================== -->

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card security-card shadow-sm h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">

                        <i class="bi bi-person-vcard me-2"></i>

                        Account Information

                    </h5>

                    <div class="mb-3">

                        <div class="small text-muted">
                            Name
                        </div>

                        <div class="fw-semibold">
                            {{ $user->name }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <div class="small text-muted">
                            Email
                        </div>

                        <div class="fw-semibold">
                            {{ $user->email }}
                        </div>

                    </div>

                    <div>

                        <div class="small text-muted">
                            Last Login
                        </div>

                        <div class="fw-semibold">

                            @if($user->last_login_at)

                                {{ $user->last_login_at->format(
                                    'd M Y, h:i A'
                                ) }}

                            @else

                                Not available

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card security-card shadow-sm h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">

                        <i class="bi bi-info-circle me-2"></i>

                        Security Status

                    </h5>

                    <div class="d-flex justify-content-between
                                align-items-center mb-3">

                        <span>
                            Email
                        </span>

                        @if($user->email)

                            <span class="badge text-bg-success">
                                Available
                            </span>

                        @else

                            <span class="badge text-bg-danger">
                                Missing
                            </span>

                        @endif

                    </div>

                    <div class="d-flex justify-content-between
                                align-items-center mb-3">

                        <span>
                            Email Verification
                        </span>

                        @if($user->email_verified_at)

                            <span class="badge text-bg-success">
                                Verified
                            </span>

                        @else

                            <span class="badge text-bg-warning">
                                Pending
                            </span>

                        @endif

                    </div>

                    <div class="d-flex justify-content-between
                                align-items-center mb-3">

                        <span>
                            Google Account
                        </span>

                        @if($user->google_id)

                            <span class="badge text-bg-success">
                                Connected
                            </span>

                        @else

                            <span class="badge text-bg-secondary">
                                Not Connected
                            </span>

                        @endif

                    </div>

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <span>
                            Active Sessions
                        </span>

                        <span class="badge text-bg-primary">

                            {{ $activeSessions }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- FOOTER -->
    <!-- ===================================================== -->

    <div class="text-center footer py-3">

        <i class="bi bi-shield-check me-1"></i>

        Your security activity is managed locally by this application.

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
