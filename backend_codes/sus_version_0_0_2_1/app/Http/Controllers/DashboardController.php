<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // This tells Laravel to look for resources/views/dashboard.blade.php
        return view('dashboard', ['title' => 'Dashboard - Smart University System']);
    }
}