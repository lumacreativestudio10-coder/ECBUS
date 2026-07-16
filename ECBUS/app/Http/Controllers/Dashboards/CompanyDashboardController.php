<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.company_admin');
    }
}
