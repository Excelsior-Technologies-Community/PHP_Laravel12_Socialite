<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;

class LoginActivityController extends Controller
{
    /**
     * Display login activity.
     */
    public function index(Request $request)
    {
        $activities = LoginActivity::where('user_id', $request->user()->id)
            ->latest('created_at')
            ->paginate(10);

        return view('login-activities', compact('activities'));
    }
}