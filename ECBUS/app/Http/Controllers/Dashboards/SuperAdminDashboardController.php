<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookings = \App\Models\Booking::count();
        $totalRevenue = \App\Models\Booking::whereIn('booking_status', ['confirmed', 'completed'])->sum('total_amount');
        $activeSchedules = \App\Models\Schedule::where('date', '>=', now()->toDateString())->count();
        $recentBookings = \App\Models\Booking::with('schedule.bus.busCompany')->latest()->take(5)->get();

        return view('dashboards.super_admin', compact(
            'totalBookings',
            'totalRevenue',
            'activeSchedules',
            'recentBookings'
        ));
    }
}
