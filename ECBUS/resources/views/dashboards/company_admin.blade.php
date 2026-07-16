@extends('layouts.admin')

@section('title', 'Company Dashboard')
@section('header', 'Company Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Staff</p>
            <h3 class="text-2xl font-bold text-dark-maroon">0</h3>
        </div>
        <div class="w-12 h-12 bg-primary-gold/20 rounded-xl flex items-center justify-center text-primary-gold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Bookings</p>
            <h3 class="text-2xl font-bold text-dark-maroon">0</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
        </div>
    </div>
    
    <!-- Stat Card 3 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
            <h3 class="text-2xl font-bold text-dark-maroon">$0</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-bold text-dark-maroon mb-4">Recent Bookings</h3>
    <p class="text-gray-500">Analytics and charts for your company will be placed here.</p>
</div>
@endsection
