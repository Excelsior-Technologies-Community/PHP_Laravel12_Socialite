<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social OAuth Profile & Encrypted Vault</title>
    <style>
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #0f172a;
            color: #f8fafc;
        }

        .container {
            max-width: 750px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: #1e293b;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
            border: 1px solid #334155;
        }

        .profile {
            text-align: center;
        }

        .profile img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #38bdf8;
        }

        .profile h1 {
            margin: 5px 0;
            font-size: 24px;
            color: #f1f5f9;
        }

        .profile p {
            color: #94a3b8;
            margin: 0 0 15px 0;
        }

        .badge-list {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-google { background: #fee2e2; color: #991b1b; }
        .badge-twitter { background: #e0f2fe; color: #075985; }
        .badge-cache { background: #d1fae5; color: #065f46; }

        .info {
            margin-top: 25px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #334155;
            font-size: 14px;
        }

        .label {
            font-weight: 600;
            color: #cbd5e1;
        }

        .value {
            color: #94a3b8;
            text-align: right;
            word-break: break-all;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 11px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .button-secondary {
            background: #475569;
        }

        .button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">

            @if(session('success'))
                <div style="background:#064e3b; color:#a7f3d0; padding:12px; border-radius:8px; margin-bottom:20px; font-size:13px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile">
                @if($user->local_avatar || $user->avatar)
                    <img src="{{ $user->local_avatar ?: $user->avatar }}" alt="Profile Avatar">
                @endif

                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>

                <div class="badge-list">
                    @if($user->google_id)
                        <span class="badge badge-google">🔴 Google Linked</span>
                    @endif
                    @if($user->twitter_id)
                        <span class="badge badge-twitter">🔵 Twitter (X) Linked</span>
                    @endif
                    @if($user->local_avatar)
                        <span class="badge badge-cache">🟢 Local Avatar Cached</span>
                    @endif
                </div>
            </div>

            <div class="info">
                <div class="row">
                    <span class="label">Full Name</span>
                    <span class="value">{{ $user->name }}</span>
                </div>

                @if($user->nickname)
                    <div class="row">
                        <span class="label">Twitter Handle</span>
                        <span class="value">@ {{ $user->nickname }}</span>
                    </div>
                @endif

                <div class="row">
                    <span class="label">Email Address</span>
                    <span class="value">{{ $user->email }}</span>
                </div>

                <div class="row">
                    <span class="label">Google OAuth ID</span>
                    <span class="value">{{ $user->google_id ?? 'Not linked' }}</span>
                </div>

                <div class="row">
                    <span class="label">Twitter OAuth ID</span>
                    <span class="value">{{ $user->twitter_id ?? 'Not linked' }}</span>
                </div>

                <div class="row">
                    <span class="label">Avatar Cache Path</span>
                    <span class="value">{{ $user->local_avatar ?? 'Remote CDN fallback' }}</span>
                </div>

                <div class="row">
                    <span class="label">Encrypted OAuth Vault Tokens</span>
                    <span class="value">{{ $user->oauthTokens->count() }} active tokens stored</span>
                </div>

                <div class="row">
                    <span class="label">Last OAuth Login</span>
                    <span class="value">
                        {{ $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('dashboard') }}" class="button button-secondary">
                    ← Back to Dashboard
                </a>

                <form method="POST" action="{{ route('profile.sync-social') }}">
                    @csrf
                    <button type="submit" class="button">
                        🔄 1-Tap Sync Social Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>