<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConductorDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.conductor');
    }
}
