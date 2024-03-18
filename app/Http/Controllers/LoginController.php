<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/Login');
    }

    public function login()
    {
        if(! Auth::attempt(request(['email', 'password']))) {
            return redirect('/login')->withErrors([
                'email' => 'These credential does not match our records'
            ]);
        }

        return redirect('/backstage/concerts');
    }
}
