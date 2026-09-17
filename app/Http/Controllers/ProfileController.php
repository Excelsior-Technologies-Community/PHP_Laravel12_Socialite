<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display Google profile.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('profile', compact('user'));
    }
}