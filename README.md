# PHP_Laravel12_Socialite


##  Project Overview

This project demonstrates how to integrate **Google Social Login** into a Laravel 12 application using **Laravel Socialite**.
It allows users to authenticate using their Google account without creating a traditional username and password.
Once logged in, users are redirected to a protected dashboard where their basic profile information is displayed.

---

##  Features

* Google OAuth Login using Laravel Socialite
* Automatic user registration on first login
* Secure session-based authentication
* Protected dashboard route with auth middleware
* Logout functionality
* Clean and simple UI for login and dashboard

---

##  Folder Structure

```
social-auth/
│
├── app/
│   └── Http/
│       └── Controllers/
│           └── Auth/
│               └── SocialiteController.php
│
├── config/
│   └── services.php
│
├── database/
│   └── migrations/
│       └── xxxx_xx_xx_add_google_id_to_users_table.php
│
├── resources/
│   └── views/
│       ├── login.blade.php
│       └── dashboard.blade.php
│
├── routes/
│   └── web.php
│
└── .env
```
---

## Step 1 — Install Laravel Project

### 1. Create a new Laravel project

```
composer create-project laravel/laravel social-auth
```

### 2. Start the development server

```
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

## Step 2 — Configure Database

Open `.env` and update:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=social_auth
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL, then run:

```
php artisan migrate
```

---

## Step 3 — Install Laravel Socialite

```
composer require laravel/socialite
```

---

## Step 4 — Create Google OAuth Credentials

1. Go to **Google Cloud Console**
2. Create a **New Project**
3. Go to **APIs & Services → OAuth consent screen**
   
   <img width="1232" height="807" alt="Screenshot 2026-02-03 142719" src="https://github.com/user-attachments/assets/a88c7deb-6bcb-4519-8661-aaeca68d9f18" />
---

   <img width="1919" height="904" alt="Screenshot 2026-02-03 142739" src="https://github.com/user-attachments/assets/01fe53c5-6746-4ba5-9ed0-c85fb162ef03" />


5. Choose **External**
6. Fill basic app details and save

<img width="377" height="448" alt="Screenshot 2026-02-03 142858" src="https://github.com/user-attachments/assets/924a75d9-ee8c-41d6-83d5-eeb22e4478b9" />


* Now:
6. Go to **APIs & Services → Credentials**
7. Click **Create Credentials → OAuth Client ID**
8. Choose **Web Application**

<img width="706" height="308" alt="Screenshot 2026-02-03 143053" src="https://github.com/user-attachments/assets/00bb9e7d-c7b7-4bcd-9bb3-843a0f2a9ddb" />

---
Add this **Authorized Redirect URI**:

```
http://127.0.0.1:8000/auth/google/callback
```
<img width="625" height="310" alt="Screenshot 2026-02-03 143124" src="https://github.com/user-attachments/assets/468efc42-de48-4f25-8309-408e3cfb8855" />


Copy:

* Client ID
* Client Secret

  <img width="866" height="575" alt="Screenshot 2026-02-03 143411" src="https://github.com/user-attachments/assets/d30ae97d-8583-42ad-a128-b75ec03690f6" />


---

## Step 5 — Add Google Credentials in Laravel

Update `.env`

```
APP_URL=http://127.0.0.1:8000

GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

Update `config/services.php`

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

Clear cache:

```
php artisan config:clear
php artisan cache:clear
```

---

## Step 6 — Add Google ID Column to Users Table

```
php artisan make:migration add_google_id_to_users_table
```

Migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
```

Run:

```
php artisan migrate
```

---

## Step 7 — Add Routes

`routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
```

---

## Step 8 — Create Socialite Controller

```
php artisan make:controller Auth/SocialiteController
```

`app/Http/Controllers/Auth/SocialiteController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            Auth::login($user);

            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed');
        }
    }
}
```

---

## Step 9 — Create Login Page

`resources/views/login.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;}
        .login-container {height:100vh;display:flex;justify-content:center;align-items:center;}
        .login-card {background:white;padding:40px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.08);text-align:center;width:320px;}
        .login-card h2 {margin-bottom:25px;color:#333;}
        .google-btn {display:inline-flex;align-items:center;justify-content:center;gap:12px;padding:12px 18px;background:#ffffff;border:1px solid #dadce0;border-radius:8px;text-decoration:none;font-size:14px;font-weight:500;color:#3c4043;box-shadow:0 1px 2px rgba(0,0,0,0.1);transition:all .2s;}
        .google-btn:hover {background:#f8f9fa;box-shadow:0 2px 6px rgba(0,0,0,0.15);}
        .google-icon {width:20px;height:20px;}
        .footer-text {margin-top:20px;font-size:13px;color:#777;}
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
        <div class="footer-text">Secure login powered by Google</div>
    </div>
</div>
</body>
</html>
```

---

## Step 10 — Create Dashboard Page

`resources/views/dashboard.blade.php`

```html
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {font-family:Arial;background:#f5f7fb;display:flex;justify-content:center;align-items:center;height:100vh;}
        .card {background:white;padding:35px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.08);text-align:center;width:320px;}
        .card h2 {margin-bottom:10px;}
        .email {color:#666;font-size:14px;margin-bottom:25px;}
        .logout {padding:10px 18px;background:#e53935;color:white;border-radius:6px;text-decoration:none;font-size:14px;}
        .logout:hover {background:#c62828;}
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
```

---

# OUTPUT

Below are the expected outputs you should see at each stage of the application.

---

### 1️ Home Page

* URL: [http://127.0.0.1:8000](http://127.0.0.1:8000)

<img width="1135" height="533" alt="Screenshot 2026-02-03 152142" src="https://github.com/user-attachments/assets/42b89a3b-3ae9-40ba-b327-54a5b7ffcc95" />

---

### 2️ Login Page

* URL: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)

* Output: A centered login card appears with a **“Continue with Google”** button.

<img width="730" height="503" alt="Screenshot 2026-02-03 144826" src="https://github.com/user-attachments/assets/5f0e9589-cd62-4d26-a8d7-230c4fb26fd5" />

---

### 3️ Google Account Selection Screen

* Action:Click **Continue with Google

* Output: Google Sign-In page opens asking the user to choose a Google account.

<img width="1081" height="331" alt="Screenshot 2026-02-03 145106" src="https://github.com/user-attachments/assets/c958dcd9-1ab6-4ada-8ae8-f214bee609de" />

---

### 4️ Google Permission Screen

**Action:** Select a Google account

**Output:** Google asks permission to share:

* Name
* Email
  User clicks **Allow** to continue.

---

### 5️ Redirect Back to Laravel

* After Permission Granted

* Output: User is automatically redirected back to the Laravel application.

---

### 6️ Dashboard Page (After Login)

* URL: [http://127.0.0.1:8000/dashboard](http://127.0.0.1:8000/dashboard)

* Output Example:

<img width="643" height="354" alt="Screenshot 2026-02-03 145516" src="https://github.com/user-attachments/assets/ec4294f0-5291-4ea2-b192-2185bda868ad" />

The dashboard displays:

* Logged-in user's name (from Google)
* Logged-in user's email
* Logout button

---

### 7️ Logout

* Action: Click **Logout
  
* Output: User is redirected back to the login page:
[http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)

<img width="730" height="503" alt="Screenshot 2026-02-03 144826" src="https://github.com/user-attachments/assets/b3d2818f-61a5-4914-8604-8e0af4ebf8ba" />

Session is destroyed and dashboard is no longer accessible without logging in again.

---

## Final Authentication Flow

Home → Login → Google Account → Permission → Dashboard → Logout → Login Again
