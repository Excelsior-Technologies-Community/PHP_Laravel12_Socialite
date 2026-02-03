<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            text-align: center;
            width: 320px;
        }

        .card h2 {
            margin-bottom: 10px;
        }

        .email {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .logout {
            padding: 10px 18px;
            background: #e53935;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .logout:hover {
            background: #c62828;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Welcome {{ auth()->user()->name }}</h2>
        <div class="email">{{ auth()->user()->email }}</div>

        <a href="{{ route('logout') }}" class="logout">Logout</a>
    </div>
</body>
</html>
