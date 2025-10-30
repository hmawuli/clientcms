<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    /**
     * Show the services page.
     */
    public function index()
    {
        return view('frontend.services');
    }
}
