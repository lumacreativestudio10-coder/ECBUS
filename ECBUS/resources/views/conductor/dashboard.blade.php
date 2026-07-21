@extends('layouts.admin')

@section('title', 'Conductor Dashboard')
@section('header', 'Conductor Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-12 h-12 bg-primary-gold/10 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="bus" class="w-6 h-6 text-primary-gold"></i>
        </div>
        <p class="text-sm text-gray-500 font-bold mb-1">Today's Trips</p>
        <h3 class="text-3xl font-extrabold text-dark-text">{{ $todayTrips->count() }}</h3>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
        </div>
        <p class="text-sm text-gray-500 font-bold mb-1">Total Passengers</p>
        <h3 class="text-3xl font-extrabold text-dark-text">{{ $totalPassengers }}</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="user-check" class="w-6 h-6 text-green-600"></i>
        </div>
        <p class="text-sm text-gray-500 font-bold mb-1">Boarded</p>
        <h3 class="text-3xl font-extrabold text-dark-text">{{ $boardedPassengers }}</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
        </div>
        <p class="text-sm text-gray-500 font-bold mb-1">Pending Boarding</p>
        <h3 class="text-3xl font-extrabold text-dark-text">{{ $pendingPassengers }}</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="ticket-check" class="w-6 h-6 text-indigo-600"></i>
        </div>
        <p class="text-sm text-gray-500 font-bold mb-1">Verified Tickets</p>
        <h3 class="text-3xl font-extrabold text-dark-text">{{ $verifiedTickets ?? 0 }}</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="user-x" class="w-6 h-6 text-red-600"></i>
        </div>
        <p class="text-sm text-gray-500 font-bold mb-1">No Show</p>
        <h3 class="text-3xl font-extrabold text-dark-text">{{ $noShowPassengers ?? 0 }}</h3>
    </div>
</div>

@if($currentTrip)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold text-dark-text mb-2">Current Active Trip</h2>
            <p class="text-gray-500">Scheduled for today</p>
        </div>
        <a href="{{ route('conductor.passenger_list', $currentTrip->id) }}" class="bg-primary-maroon text-white px-4 py-2 rounded-lg font-medium hover:bg-dark-maroon transition">
            Start Boarding
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Route</p>
            <p class="font-bold text-dark-text">{{ $currentTrip->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-4 h-4 inline mx-1"></i> {{ $currentTrip->route->toLocation->name }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Bus Details</p>
            <p class="font-bold text-dark-text">{{ $currentTrip->bus->bus_number }}</p>
            <p class="text-xs text-gray-500">{{ $currentTrip->bus->busType->name ?? 'Standard' }} ({{ $currentTrip->bus->total_seats }} Seats)</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Departure</p>
            <p class="font-bold text-dark-text">{{ \Carbon\Carbon::parse($currentTrip->departure_time)->format('h:i A') }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Driver</p>
            <p class="font-bold text-dark-text">{{ $currentTrip->driver->name ?? 'N/A' }}</p>
        </div>
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
        <i data-lucide="calendar-x" class="w-10 h-10"></i>
    </div>
    <h3 class="text-xl font-bold text-dark-text mb-2">No Trip Assigned for Today</h3>
    <p class="text-gray-500">You do not have any schedules assigned for today.</p>
</div>
@endif

@endsection
