<!DOCTYPE html>

<html lang="en">

<head>


    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login Activity</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
        }

        h1 {
            margin-top: 0;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .login {
            background: #d1e7dd;
            color: #0f5132;
        }

        .logout {
            background: #f8d7da;
            color: #842029;
        }

        .revoked {
            background: #fff3cd;
            color: #664d03;
        }

        .button {
            display: inline-block;
            padding: 10px 16px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .pagination {
            margin-top: 25px;
        }
    </style>


</head>

<body>

    <div class="container">


        <div class="card">

            <h1>🔐 Login Activity</h1>

            <p>
                Your Google authentication activity history.
            </p>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>Event</th>
                            <th>Provider</th>
                            <th>Device</th>
                            <th>Browser</th>
                            <th>Platform</th>
                            <th>IP Address</th>
                            <th>Date & Time</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($activities as $activity)

                        <tr>

                            <td>

                                @if($activity->event === 'login')

                                <span class="badge login">
                                    LOGIN
                                </span>

                                @elseif($activity->event === 'logout')

                                <span class="badge logout">
                                    LOGOUT
                                </span>

                                @else

                                <span class="badge revoked">
                                    SESSION REVOKED
                                </span>

                                @endif

                            </td>

                            <td>
                                {{ ucfirst($activity->provider) }}
                            </td>

                            <td>
                                {{ $activity->device_name }}
                            </td>

                            <td>
                                {{ $activity->browser }}
                            </td>

                            <td>
                                {{ $activity->platform }}
                            </td>

                            <td>
                                {{ $activity->ip_address }}
                            </td>

                            <td>
                                {{ $activity->created_at->format('d M Y, h:i A') }}
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7">
                                No login activity found.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="pagination">

                {{ $activities->links() }}

            </div>

            <br>

            <a
                href="{{ route('dashboard') }}"
                class="button">
                ← Back to Dashboard
            </a>

        </div>


    </div>

</body>

</html>