@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-primary-gold/20 flex items-center justify-center text-dark-maroon mr-4">
            <i data-lucide="ticket" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Total Bookings</p>
            <h3 class="text-3xl font-extrabold text-dark-text">{{ $totalBookings }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center text-green-700 mr-4">
            <i data-lucide="banknote" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Total Revenue</p>
            <h3 class="text-2xl font-extrabold text-dark-text">LKR {{ number_format($totalRevenue, 2) }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 mr-4">
            <i data-lucide="pie-chart" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Commissions</p>
            <h3 class="text-2xl font-extrabold text-dark-text">LKR {{ number_format($totalCommissions, 2) }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-red-100 flex items-center justify-center text-red-700 mr-4">
            <i data-lucide="clock" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Pending Settles</p>
            <h3 class="text-3xl font-extrabold text-dark-text">{{ $pendingSettlementsCount }}</h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">Company Settlements Overview</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                    <th class="px-6 py-4">Company Name</th>
                    <th class="px-6 py-4 text-center">Web / Counter / Staff</th>
                    <th class="px-6 py-4 text-right">Total Revenue</th>
                    <th class="px-6 py-4 text-right text-red-600">Total Comm.</th>
                    <th class="px-6 py-4 text-right text-green-600">Company Balance</th>
                    <th class="px-6 py-4 text-right text-yellow-600">Settlement Due</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($companiesData as $company)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-dark-text">{{ $company->name }}</td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-600">
                        {{ $company->total_website_bookings }} / {{ $company->total_counter_bookings }} / {{ $company->total_staff_bookings }}
                    </td>
                    <td class="px-6 py-4 text-right font-extrabold text-dark-text">LKR {{ number_format($company->total_revenue, 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-red-600">LKR {{ number_format($company->total_commission, 2) }}</td>
                    <td class="px-6 py-4 text-right font-extrabold text-green-600">LKR {{ number_format($company->company_balance, 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-yellow-600">LKR {{ number_format($company->settlement_due, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No company data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
