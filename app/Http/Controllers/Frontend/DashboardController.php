<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard for authenticated users.
     */
    public function index()
    {
        $user = Auth::user(); // Optional: pass user data to view
        return view('frontend.dashboard', compact('user'));
    }
}
