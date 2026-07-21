<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $companyBookings = \App\Models\Booking::whereIn('booking_status', ['confirmed', 'completed'])->get();
        $todayBookings = $companyBookings->where('created_at', '>=', today());

        $totalSales = $companyBookings->whereIn('booking_source', ['staff', 'counter'])->sum('total_amount');
        $todaySales = $todayBookings->whereIn('booking_source', ['staff', 'counter'])->sum('total_amount');
        
        $counterCount = $companyBookings->where('booking_source', 'counter')->count();
        $staffCount = $companyBookings->where('booking_source', 'staff')->count();
        $websiteCount = $companyBookings->where('booking_source', 'website')->count();

        return view('dashboards.staff', compact(
            'totalSales', 'todaySales', 'counterCount', 'staffCount', 'websiteCount'
        ));
    }
}
