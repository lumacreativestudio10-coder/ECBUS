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
        
        $totalCommissions = \App\Models\BookingCommission::sum('commission_amount');
        $pendingSettlementsCount = \App\Models\CompanySettlement::where('status', 'pending')->count();
        
        $companiesData = [];
        $companies = \App\Models\BusCompany::all();
        foreach ($companies as $company) {
            $companyBookings = \App\Models\Booking::withoutGlobalScopes()->whereHas('schedule.bus', function($q) use ($company) {
                $q->where('bus_company_id', $company->id);
            })->whereIn('booking_status', ['confirmed', 'completed'])->get();

            $totalWebsite = $companyBookings->where('booking_source', 'website')->count();
            $totalCounter = $companyBookings->where('booking_source', 'counter')->count();
            $totalStaff = $companyBookings->where('booking_source', 'staff')->count();
            $revenue = $companyBookings->sum('total_amount');
            $commission = \App\Models\BookingCommission::withoutGlobalScopes()->where('company_id', $company->id)->sum('commission_amount');
            $pendingSettlement = \App\Models\CompanySettlement::withoutGlobalScopes()->where('company_id', $company->id)->where('status', 'pending')->sum('net_payable');

            $companiesData[] = (object)[
                'name' => $company->company_name,
                'total_website_bookings' => $totalWebsite,
                'total_counter_bookings' => $totalCounter,
                'total_staff_bookings' => $totalStaff,
                'total_revenue' => $revenue,
                'total_commission' => $commission,
                'company_balance' => $revenue - $commission,
                'settlement_due' => $pendingSettlement
            ];
        }

        return view('dashboards.super_admin', compact(
            'totalBookings', 'totalRevenue', 'activeSchedules', 'totalCommissions', 'pendingSettlementsCount', 'companiesData'
        ));
    }
}
