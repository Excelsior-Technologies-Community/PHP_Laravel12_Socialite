<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Google Profile</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
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
        }

        .profile h1 {
            margin: 5px 0;
        }

        .info {
            margin-top: 30px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .label {
            font-weight: bold;
        }

        .value {
            color: #666;
            text-align: right;
        }

        .button {
            display: inline-block;
            margin-top: 25px;
            padding: 11px 18px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>


</head>

<body>

    <div class="container">

        <div class="card">

            <div class="profile">

                @if($user->avatar)
                <img
                    src="{{ $user->avatar }}"
                    alt="Google Profile">
                @endif

                <h1>{{ $user->name }}</h1>

                <p>{{ $user->email }}</p>

            </div>

            <div class="info">

                <div class="row">
                    <span class="label">Name</span>
                    <span class="value">
                        {{ $user->name }}
                    </span>
                </div>

                <div class="row">
                    <span class="label">Email</span>
                    <span class="value">
                        {{ $user->email }}
                    </span>
                </div>

                <div class="row">
                    <span class="label">Google ID</span>
                    <span class="value">
                        {{ $user->google_id ?? 'Not available' }}
                    </span>
                </div>

                <div class="row">
                    <span class="label">Last Login</span>
                    <span class="value">
                        {{ $user->last_login_at
                    ? $user->last_login_at->format('d M Y, h:i A')
                    : 'Not available' }}
                    </span>
                </div>

            </div>

            <a href="{{ route('dashboard') }}" class="button">
                ← Back to Dashboard
            </a>

        </div>


    </div>

</body>

</html>