<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Google Social Login</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            font-family: Inter, Arial, Helvetica, sans-serif;
            color: #1f2937;
        }

        /* =========================
           NAVBAR
        ========================== */

        .navbar-custom {
            height: 72px;
            background: #ffffff;
            border-bottom: 1px solid #e9edf3;
            display: flex;
            align-items: center;
            padding: 0 35px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 20px;
            color: #111827;
            text-decoration: none;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111827;
            color: white;
            font-size: 20px;
        }

        .user-area {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-mini {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-mini img,
        .user-mini-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .user-mini img {
            object-fit: cover;
        }

        .user-mini-placeholder {
            background: #111827;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
        }

        .user-info {
            line-height: 1.2;
        }

        .user-info strong {
            display: block;
            font-size: 14px;
        }

        .user-info span {
            color: #6b7280;
            font-size: 12px;
        }

        .logout-btn {
            border: 0;
            background: #fff0f0;
            color: #dc3545;
            padding: 9px 15px;
            border-radius: 9px;
            font-weight: 600;
            transition: .2s;
        }

        .logout-btn:hover {
            background: #dc3545;
            color: white;
        }

        /* =========================
           MAIN
        ========================== */

        .main-container {
            max-width: 1250px;
            margin: auto;
            padding: 40px 22px 60px;
        }

        .welcome-section {
            margin-bottom: 30px;
        }

        .welcome-section h1 {
            font-size: 32px;
            font-weight: 750;
            margin-bottom: 7px;
            color: #111827;
        }

        .welcome-section p {
            margin: 0;
            color: #6b7280;
            font-size: 15px;
        }

        .google-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            border: 1px solid #e6eaf0;
            border-radius: 30px;
            padding: 7px 13px;
            font-size: 12px;
            color: #4b5563;
            margin-top: 15px;
        }

        .google-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #34a853;
        }

        /* =========================
           PROFILE HERO
        ========================== */

        .profile-hero {
            background: #111827;
            border-radius: 20px;
            padding: 28px;
            color: white;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }

        .profile-hero::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            right: -70px;
            top: -90px;
        }

        .profile-hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .profile-main {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .profile-avatar {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, .2);
        }

        .profile-placeholder {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: #374151;
            border: 4px solid rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
        }

        .profile-main h3 {
            margin: 0 0 5px;
            font-size: 23px;
        }

        .profile-main p {
            margin: 0 0 10px;
            color: #cbd5e1;
            font-size: 14px;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(52, 168, 83, .15);
            color: #8ef0a5;
            border: 1px solid rgba(52, 168, 83, .3);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .profile-action {
            position: relative;
            z-index: 3;
        }

        .profile-action a {
            background: white;
            color: #111827;
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: .2s;
        }

        .profile-action a:hover {
            transform: translateY(-1px);
        }

        /* =========================
           SECTION TITLE
        ========================== */

        .section-heading {
            margin-bottom: 16px;
        }

        .section-heading h2 {
            font-size: 19px;
            font-weight: 700;
            margin: 0;
        }

        .section-heading p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        /* =========================
           FEATURE CARDS
        ========================== */

        .feature-card {
            height: 100%;
            background: white;
            border: 1px solid #e9edf3;
            border-radius: 16px;
            padding: 24px;
            transition: all .25s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(17, 24, 39, .08);
            border-color: #dce2ea;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            margin-bottom: 18px;
        }

        .icon-profile {
            background: #eef2ff;
            color: #4f46e5;
        }

        .icon-security {
            background: #ecfdf5;
            color: #059669;
        }

        .icon-devices {
            background: #fff7ed;
            color: #ea580c;
        }

        .icon-logout {
            background: #fef2f2;
            color: #dc2626;
        }

        .feature-card h3 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .feature-card p {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
            min-height: 42px;
            margin-bottom: 20px;
        }

        .feature-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .feature-link:hover {
            text-decoration: underline;
        }

        /* =========================
           SECURITY STATUS
        ========================== */

        .security-box {
            margin-top: 30px;
            background: white;
            border: 1px solid #e9edf3;
            border-radius: 16px;
            padding: 22px 25px;
        }

        .security-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .security-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .security-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #ecfdf5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .security-title h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
        }

        .security-title p {
            margin: 3px 0 0;
            color: #6b7280;
            font-size: 12px;
        }

        .secure-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ecfdf5;
            color: #047857;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .secure-status span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
        }

        /* =========================
           ALERTS
        ========================== */

        .alert {
            border-radius: 11px;
            border: 0;
            font-size: 14px;
        }

        /* =========================
           FOOTER
        ========================== */

        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            margin-top: 35px;
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 768px) {

            .navbar-custom {
                padding: 0 18px;
            }

            .brand span {
                display: none;
            }

            .user-info {
                display: none;
            }

            .main-container {
                padding: 28px 15px 45px;
            }

            .welcome-section h1 {
                font-size: 26px;
            }

            .profile-hero {
                padding: 22px;
            }

            .profile-hero-content {
                align-items: flex-start;
                flex-direction: column;
            }

            .profile-action {
                width: 100%;
            }

            .profile-action a {
                width: 100%;
                justify-content: center;
            }

            .profile-avatar,
            .profile-placeholder {
                width: 65px;
                height: 65px;
            }

            .profile-main h3 {
                font-size: 19px;
            }

            .logout-btn {
                padding: 8px 11px;
            }
        }
    </style>

</head>

<body>

    <!-- =========================
         NAVBAR
    ========================== -->

    <nav class="navbar-custom">

        <a href="{{ route('dashboard') }}" class="brand">

            <div class="brand-icon">
                <i class="bi bi-shield-lock"></i>
            </div>

            <span>Socialite Security</span>

        </a>

        <div class="user-area">

            <div class="user-mini">

                @if(auth()->user()->avatar)

                    <img
                        src="{{ auth()->user()->avatar }}"
                        alt="Profile">

                @else

                    <div class="user-mini-placeholder">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                @endif

                <div class="user-info">

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>

                    <span>
                        Google Account
                    </span>

                </div>

            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">

                    <i class="bi bi-box-arrow-right me-1"></i>

                    Logout

                </button>

            </form>

        </div>

    </nav>


    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main class="main-container">


        <!-- Welcome -->

        <div class="welcome-section">

            <h1>
                Welcome back, {{ auth()->user()->name }} 👋
            </h1>

            <p>
                Manage your Google account, security activity and active devices from one place.
            </p>

            <div class="google-badge">

                <span class="google-dot"></span>

                Google Social Login Connected

            </div>

        </div>


        <!-- =========================
             PROFILE HERO
        ========================== -->

        <div class="profile-hero">

            <div class="profile-hero-content">

                <div class="profile-main">

@if(auth()->user()->avatar)

    <img
        src="{{ auth()->user()->avatar }}"
        alt="{{ auth()->user()->name }}"
        class="profile-avatar"
        onerror="this.style.display='none'; document.getElementById('avatarFallback').style.display='flex';">

    <div
        id="avatarFallback"
        class="profile-placeholder"
        style="display:none;">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>

@else

    <div class="profile-placeholder">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>

@endif


                    <div>

                        <h3>
                            {{ auth()->user()->name }}
                        </h3>

                        <p>
                            {{ auth()->user()->email }}
                        </p>

                        <span class="verified-badge">

                            <i class="bi bi-check-circle-fill"></i>

                            Google Verified Account

                        </span>

                    </div>

                </div>


                <div class="profile-action">

                    <a href="{{ route('profile') }}">

                        <i class="bi bi-person"></i>

                        View Profile

                    </a>

                </div>

            </div>

        </div>


        <!-- =========================
             ALERTS
        ========================== -->

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show" role="alert">

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

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- =========================
             SECURITY FEATURES
        ========================== -->

        <div class="section-heading">

            <h2>
                Account & Security
            </h2>

            <p>
                Manage your profile and monitor your account security.
            </p>

        </div>


        <div class="row g-4">


            <!-- Profile -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon icon-profile">

                        <i class="bi bi-person-badge"></i>

                    </div>

                    <h3>
                        Google Profile
                    </h3>

                    <p>
                        View your Google account details, profile picture,
                        email address and stored Socialite information.
                    </p>

                    <a
                        href="{{ route('profile') }}"
                        class="feature-link">

                        View Profile

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


            <!-- Login Activity -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon icon-security">

                        <i class="bi bi-clock-history"></i>

                    </div>

                    <h3>
                        Login Activity
                    </h3>

                    <p>
                        Monitor your Google login and logout history,
                        including browser, device, IP address and time.
                    </p>

                    <a
                        href="{{ route('login.activities') }}"
                        class="feature-link">

                        View Activity

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


            <!-- Active Devices -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon icon-devices">

                        <i class="bi bi-phone"></i>

                    </div>

                    <h3>
                        Active Devices
                    </h3>

                    <p>
                        View your active sessions and revoke access
                        from devices you no longer use.
                    </p>

                    <a
                        href="{{ route('sessions') }}"
                        class="feature-link">

                        Manage Devices

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


        </div>


        <!-- =========================
             SECURITY STATUS
        ========================== -->

        <div class="security-box">

            <div class="security-header">

                <div class="security-title">

                    <div class="security-title-icon">

                        <i class="bi bi-shield-check"></i>

                    </div>

                    <div>

                        <h4>
                            Account Security
                        </h4>

                        <p>
                            Your Google authentication session is active.
                        </p>

                    </div>

                </div>

                <div class="secure-status">

                    <span></span>

                    Secure

                </div>

            </div>

        </div>


        <div class="footer">

            Google Social Login · Laravel {{ app()->version() }}

        </div>

    </main>


    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>