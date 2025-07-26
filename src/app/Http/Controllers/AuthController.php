<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $password = $request->input('password');

        if ($password === config('auth.password')) {
            $cookie = cookie('auth_token', csrf_token(), 60 * 24 * 90); // 90 days
            return redirect()->route('quotes.index')->withCookie($cookie);
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $cookie = Cookie::forget('auth_token');
        return redirect()->route('login')->withCookie($cookie);
    }
}
