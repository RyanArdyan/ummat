<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // method index untuk menampilkan halaman dashboard
    public function index()
    {
        // kembalikkan ke tampilan dashboard.index
        return view('dashboard.index');
    }
}
