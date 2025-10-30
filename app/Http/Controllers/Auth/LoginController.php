<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Handles user authentication and login functionality.
 * This class uses the AuthenticatesUsers trait which contains the core
 * logic for handling login and logout requests.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after successful login.
     *
     * @var string
     */
    protected string $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Guests can access login and register views; authenticated users can only access logout.
        //$this->middleware('guest')->except('logout');
        //$this->middleware('auth')->only('logout');
    }

    /**
     * If you are logging in using a field other than 'email', uncomment this method
     * and change the return value to your login field (e.g., 'username').
     *
     * public function username(): string
     * {
     * return 'username';
     * }
     */
}
