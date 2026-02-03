<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Center everything */
        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card */
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
            width: 320px;
        }

        .login-card h2 {
            margin-bottom: 25px;
            color: #333;
        }

        /* Google button */
        .google-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px 18px;
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: #3c4043;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: all 0.2s ease-in-out;
        }

        .google-btn:hover {
            background: #f8f9fa;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .google-icon {
            width: 20px;
            height: 20px;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <h2>Sign in to Your Account</h2>

        <a href="{{ url('/auth/google/redirect') }}" class="google-btn">
            <svg class="google-icon" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.73 1.22 9.24 3.6l6.9-6.9C35.64 2.34 30.18 0 24 0 14.62 0 6.56 5.8 2.56 14.2l8.04 6.24C12.48 14.12 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.5 24.5c0-1.7-.14-3.34-.4-4.94H24v9.34h12.7c-.55 2.96-2.22 5.47-4.72 7.16l7.36 5.7C43.96 37.5 46.5 31.5 46.5 24.5z"/>
                <path fill="#FBBC05" d="M10.6 28.44c-1.02-3.02-1.02-6.28 0-9.3l-8.04-6.24C.92 16.28 0 20.04 0 24s.92 7.72 2.56 11.1l8.04-6.66z"/>
                <path fill="#34A853" d="M24 48c6.18 0 11.64-2.04 15.52-5.54l-7.36-5.7c-2.04 1.38-4.66 2.2-8.16 2.2-6.26 0-11.52-4.62-13.4-10.94l-8.04 6.66C6.56 42.2 14.62 48 24 48z"/>
            </svg>

            Continue with Google
        </a>

        <div class="footer-text">
            Secure login powered by Google
        </div>
    </div>
</div>

</body>
</html>
