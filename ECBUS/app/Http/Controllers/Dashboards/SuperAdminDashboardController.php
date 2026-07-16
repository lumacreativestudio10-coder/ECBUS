<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.super_admin');
    }
}
