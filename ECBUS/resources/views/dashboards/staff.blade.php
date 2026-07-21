@extends('layouts.admin')

@section('title', 'Staff Dashboard')
@section('header', 'Staff Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Sales</p>
            <h3 class="text-2xl font-bold text-dark-maroon">LKR {{ number_format($todaySales, 2) }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
            <i data-lucide="banknote" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Sales</p>
            <h3 class="text-2xl font-bold text-dark-maroon">LKR {{ number_format($totalSales, 2) }}</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
            <i data-lucide="wallet" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Booking Stats</p>
            <h3 class="text-xl font-bold text-dark-maroon">Staff: {{ $staffCount }} | Ctr: {{ $counterCount }}</h3>
            <p class="text-xs text-gray-400 mt-1">Website (Read Only): {{ $websiteCount }}</p>
        </div>
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
            <i data-lucide="ticket" class="w-6 h-6"></i>
        </div>
    </div>
</div>
@endsection
