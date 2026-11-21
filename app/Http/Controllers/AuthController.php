<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display the Auth & Role Selection View.
     */
    public function login()
    {
        return view('auth.login', [
            'pageTitle' => 'User Authentication & Roles'
        ]);
    }
}
