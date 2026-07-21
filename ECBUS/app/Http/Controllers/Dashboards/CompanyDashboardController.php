<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        $companyBookings = \App\Models\Booking::whereIn('booking_status', ['confirmed', 'completed'])->get();
        $todayBookings = $companyBookings->where('created_at', '>=', today());

        $todayRevenue = $todayBookings->sum('total_amount');
        $todayWebsite = $todayBookings->where('booking_source', 'website')->count();
        $todayCounter = $todayBookings->where('booking_source', 'counter')->count();
        $todayStaff = $todayBookings->where('booking_source', 'staff')->count();

        $commissionEarned = \App\Models\BookingCommission::sum('commission_amount');
        $pendingSettlement = \App\Models\CompanySettlement::where('status', 'pending')->sum('net_payable');
        $paidSettlement = \App\Models\CompanySettlement::where('status', 'paid')->sum('net_payable');

        $totalRevenue = $companyBookings->sum('total_amount');
        $companyBalance = $totalRevenue - $commissionEarned;

        return view('dashboards.company_admin', compact(
            'todayRevenue', 'todayWebsite', 'todayCounter', 'todayStaff',
            'pendingSettlement', 'paidSettlement', 'commissionEarned', 'companyBalance'
        ));
    }
}
