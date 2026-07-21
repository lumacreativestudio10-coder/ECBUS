@extends('layouts.admin')

@section('title', 'Driver Dashboard')
@section('header', 'Driver Dashboard')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-dark-maroon mb-4">Today's Trips</h3>
        @if(isset($todayTrips) && $todayTrips->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($todayTrips as $trip)
                    <div class="border rounded-lg p-4">
                        <div class="font-bold">{{ $trip->route->name ?? 'Route' }}</div>
                        <div class="text-sm text-gray-500">Departure: {{ $trip->departure_time }}</div>
                        <div class="text-sm text-gray-500">Bus: {{ $trip->bus->bus_number ?? 'N/A' }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">You have no trips assigned for today.</p>
        @endif
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-dark-maroon mb-4">Upcoming Trips</h3>
        @if(isset($upcomingTrips) && $upcomingTrips->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($upcomingTrips as $trip)
                    <div class="border rounded-lg p-4">
                        <div class="font-bold">{{ $trip->route->name ?? 'Route' }}</div>
                        <div class="text-sm text-gray-500">Date: {{ $trip->date->format('Y-m-d') }}</div>
                        <div class="text-sm text-gray-500">Departure: {{ $trip->departure_time }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">You have no upcoming trips.</p>
        @endif
    </div>
</div>
@endsection
