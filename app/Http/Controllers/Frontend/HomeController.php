<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the home page.
     */
    public function index()
    {
        return view('frontend.home');
    }
}
