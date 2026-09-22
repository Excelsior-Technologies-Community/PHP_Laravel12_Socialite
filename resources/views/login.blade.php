<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OAuth Social Login - Secure Access</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #0f172a;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #f8fafc;
        }

        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #1e293b;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border: 1px solid #334155;
            text-align: center;
            width: 340px;
        }

        .login-card h2 {
            margin-bottom: 8px;
            color: #f1f5f9;
            font-size: 22px;
        }

        .login-card p {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .btn-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .google-btn {
            background: #ffffff;
            color: #1f2937;
            border: 1px solid #e5e7eb;
        }

        .google-btn:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        .twitter-btn {
            background: #000000;
            color: #ffffff;
            border: 1px solid #334155;
        }

        .twitter-btn:hover {
            background: #18181b;
            transform: translateY(-1px);
        }

        .social-icon {
            width: 20px;
            height: 20px;
        }

        .footer-text {
            margin-top: 25px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">

        @if(session('error'))
            <div style="background:#451a03; color:#fde68a; padding:12px; border-radius:8px; margin-bottom:15px; font-size:13px; border:1px solid #78350f;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div style="background:#064e3b; color:#a7f3d0; padding:12px; border-radius:8px; margin-bottom:15px; font-size:13px; border:1px solid #065f46;">
                {{ session('success') }}
            </div>
        @endif

        <h2>Sign in to Account</h2>
        <p>Choose an OAuth 2.0 Provider</p>

        <div class="btn-stack">
            <!-- Google Login -->
            <a href="{{ route('google.redirect') }}" class="social-btn google-btn">
                <svg class="social-icon" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.73 1.22 9.24 3.6l6.9-6.9C35.64 2.34 30.18 0 24 0 14.62 0 6.56 5.8 2.56 14.2l8.04 6.24C12.48 14.12 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.5 24.5c0-1.7-.14-3.34-.4-4.94H24v9.34h12.7c-.55 2.96-2.22 5.47-4.72 7.16l7.36 5.7C43.96 37.5 46.5 31.5 46.5 24.5z"/>
                    <path fill="#FBBC05" d="M10.6 28.44c-1.02-3.02-1.02-6.28 0-9.3l-8.04-6.24C.92 16.28 0 20.04 0 24s.92 7.72 2.56 11.1l8.04-6.66z"/>
                    <path fill="#34A853" d="M24 48c6.18 0 11.64-2.04 15.52-5.54l-7.36-5.7c-2.04 1.38-4.66 2.2-8.16 2.2-6.26 0-11.52-4.62-13.4-10.94l-8.04 6.66C6.56 42.2 14.62 48 24 48z"/>
                </svg>
                Continue with Google
            </a>

            <!-- Twitter / X Login -->
            <a href="{{ route('twitter.redirect') }}" class="social-btn twitter-btn">
                <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
                Continue with Twitter (X)
            </a>
        </div>

        <div class="footer-text">
            🔐 Encrypted OAuth Token Vault Active
        </div>
    </div>
</div>

</body>
</html>
