@extends('layouts.admin')

@section('title', 'Driver Dashboard')
@section('header', 'Driver Dashboard')

@section('content')

@if(!$currentTrip)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center mb-8">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
        <i data-lucide="calendar-x" class="w-10 h-10"></i>
    </div>
    <h3 class="text-xl font-bold text-dark-text mb-2">No trips assigned today.</h3>
    <p class="text-gray-500">Take a rest or check upcoming trips in "My Trips".</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Today's Trips</p>
            <h3 class="text-2xl font-bold text-dark-maroon">{{ $todayTrips->count() }}</h3>
        </div>
        <div class="w-12 h-12 bg-primary-gold/20 rounded-xl flex items-center justify-center text-primary-gold">
            <i data-lucide="calendar" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Passengers (Current Trip)</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $totalPassengers }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
            <i data-lucide="users" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Completed Trips (All Time)</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $completedTrips }}</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative overflow-hidden">
    <!-- Status Ribbon -->
    @if($currentTrip->status === 'in_progress')
        <div class="absolute top-0 right-0 bg-green-500 text-white font-bold text-xs px-6 py-2 rounded-bl-xl shadow-sm">
            <div class="flex items-center"><div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div> IN PROGRESS</div>
        </div>
    @else
        <div class="absolute top-0 right-0 bg-blue-500 text-white font-bold text-xs px-6 py-2 rounded-bl-xl shadow-sm">
            SCHEDULED
        </div>
    @endif

    <div class="flex justify-between items-start mb-6 pt-4">
        <div>
            <h2 class="text-xl font-bold text-dark-text mb-1">Current Active Trip</h2>
            <p class="text-gray-500 text-sm">Please follow the assigned route strictly.</p>
        </div>
        <div class="flex gap-2">
            @if($currentTrip->status === 'scheduled')
                <form action="{{ route('driver.start_trip', $currentTrip->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-green-600 transition flex items-center shadow-md">
                        <i data-lucide="play" class="w-4 h-4 mr-2"></i> Start Trip
                    </button>
                </form>
            @elseif($currentTrip->status === 'in_progress')
                <form action="{{ route('driver.complete_trip', $currentTrip->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gray-800 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-gray-900 transition flex items-center shadow-md">
                        <i data-lucide="flag" class="w-4 h-4 mr-2"></i> Complete Trip
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-500 mb-2 shadow-sm"><i data-lucide="map" class="w-4 h-4"></i></div>
            <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider font-bold">Route</p>
            <p class="font-bold text-dark-text">{{ $currentTrip->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-3 h-3 inline mx-1 text-gray-400"></i> {{ $currentTrip->route->toLocation->name }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-500 mb-2 shadow-sm"><i data-lucide="bus" class="w-4 h-4"></i></div>
            <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider font-bold">Assigned Bus</p>
            <p class="font-bold text-dark-text">{{ $currentTrip->bus->bus_number }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-500 mb-2 shadow-sm"><i data-lucide="clock" class="w-4 h-4"></i></div>
            <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider font-bold">Next Departure</p>
            <p class="font-bold text-dark-text">{{ \Carbon\Carbon::parse($currentTrip->departure_time)->format('h:i A') }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-500 mb-2 shadow-sm"><i data-lucide="user" class="w-4 h-4"></i></div>
            <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider font-bold">Conductor</p>
            <p class="font-bold text-dark-text">{{ $currentTrip->conductor->name ?? 'None Assigned' }}</p>
        </div>
    </div>
</div>
@endif

@endsection
