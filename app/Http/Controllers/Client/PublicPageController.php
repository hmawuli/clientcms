<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Public Page Controller works']);
    }
}
