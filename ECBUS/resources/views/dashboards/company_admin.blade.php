@extends('layouts.admin')

@section('title', 'Company Dashboard')
@section('header', 'Company Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Revenue</p>
            <h3 class="text-2xl font-bold text-dark-maroon">LKR {{ number_format($todayRevenue, 2) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Web: {{ $todayWebsite }} | Ctr: {{ $todayCounter }} | Stf: {{ $todayStaff }}</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
            <i data-lucide="banknote" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Company Balance</p>
            <h3 class="text-2xl font-bold text-dark-maroon">LKR {{ number_format($companyBalance, 2) }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
            <i data-lucide="wallet" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Commission Earned</p>
            <h3 class="text-2xl font-bold text-red-600">LKR {{ number_format($commissionEarned, 2) }}</h3>
        </div>
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
            <i data-lucide="pie-chart" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Pending Settlement</p>
            <h3 class="text-2xl font-bold text-yellow-600">LKR {{ number_format($pendingSettlement, 2) }}</h3>
        </div>
        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
    </div>
</div>
@endsection
