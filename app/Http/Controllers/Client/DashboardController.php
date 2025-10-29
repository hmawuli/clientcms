<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Displays the dashboard for authenticated users.
 *
 * @mixin \Illuminate\Routing\ControllerDispatcher
 * @method static void middleware(string|array $middleware, array $options = [])
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Only authenticated users can access dashboard
        $this->middleware('auth');
    }

    /**
     * Display the dashboard view.
     */
    public function index()
    {
        return view('dashboard');
    }
}
