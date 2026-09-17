<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Active Devices</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
            margin-bottom: 20px;
        }

        .session {
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .session-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .device {
            font-size: 18px;
            font-weight: bold;
        }

        .current {
            display: inline-block;
            background: #d1e7dd;
            color: #0f5132;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .details {
            margin-top: 15px;
            color: #666;
            line-height: 1.8;
        }

        .revoke {
            background: #dc3545;
            color: white;
            border: 0;
            padding: 9px 15px;
            border-radius: 6px;
            cursor: pointer;
        }

        .button {
            display: inline-block;
            padding: 10px 16px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .alert {
            padding: 14px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .error {
            background: #f8d7da;
            color: #842029;
        }
    </style>


</head>

<body>

    <div class="container">


        <div class="card">

            <h1>📱 Active Devices</h1>

            <p>
                Manage the devices currently authenticated to your account.
            </p>

            @if(session('success'))

            <div class="alert success">
                {{ session('success') }}
            </div>

            @endif

            @if(session('error'))

            <div class="alert error">
                {{ session('error') }}
            </div>

            @endif

        </div>

        @forelse($sessions as $session)

        <div class="session">

            <div class="session-header">

                <div>

                    <div class="device">
                        {{ $session->device_name }}
                    </div>

                    @if($session->session_id === $currentSessionId)

                    <span class="current">
                        CURRENT SESSION
                    </span>

                    @endif

                </div>

                @if($session->session_id !== $currentSessionId)

                <form
                    action="{{ route('sessions.revoke', $session) }}"
                    method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="revoke"
                        onclick="return confirm('Are you sure you want to revoke this session?')">
                        Revoke
                    </button>

                </form>

                @endif

            </div>

            <div class="details">

                <strong>Browser:</strong>
                {{ $session->browser }}

                <br>

                <strong>Platform:</strong>
                {{ $session->platform }}

                <br>

                <strong>IP Address:</strong>
                {{ $session->ip_address }}

                <br>

                <strong>Last Activity:</strong>
                {{ $session->last_activity
                ? $session->last_activity->format('d M Y, h:i A')
                : 'Not available' }}

            </div>

        </div>

        @empty

        <div class="card">

            <p>
                No active sessions found.
            </p>

        </div>

        @endforelse

        <a
            href="{{ route('dashboard') }}"
            class="button">
            ← Back to Dashboard
        </a>


    </div>

</body>

</html>