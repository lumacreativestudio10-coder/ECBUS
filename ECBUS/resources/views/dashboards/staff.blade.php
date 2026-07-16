@extends('layouts.admin')

@section('title', 'Staff Dashboard')
@section('header', 'Staff Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Bookings</p>
            <h3 class="text-2xl font-bold text-dark-maroon">0</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Pending Payments</p>
            <h3 class="text-2xl font-bold text-dark-maroon">0</h3>
        </div>
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>
@endsection
