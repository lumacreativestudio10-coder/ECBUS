@extends('layouts.admin')

@section('title', 'My Trips')
@section('header', 'My Trips')

@section('content')

<h2 class="text-lg font-bold text-dark-text mb-4">Today's Assigned Trips</h2>

@if($todayTrips->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center mb-10">
    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
        <i data-lucide="bus" class="w-8 h-8"></i>
    </div>
    <h3 class="text-lg font-bold text-dark-text mb-1">No trips today</h3>
    <p class="text-gray-500 text-sm">You are free for today.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-medium">Route</th>
                <th class="p-4 border-b font-medium">Bus</th>
                <th class="p-4 border-b font-medium">Departure</th>
                <th class="p-4 border-b font-medium">Status</th>
                <th class="p-4 border-b font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @foreach($todayTrips as $trip)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-bold text-dark-text">
                    {{ $trip->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-3 h-3 inline text-gray-400 mx-1"></i> {{ $trip->route->toLocation->name }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ $trip->bus->bus_number }}
                </td>
                <td class="p-4 text-gray-600 font-medium">
                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('h:i A') }}
                </td>
                <td class="p-4">
                    @if($trip->status == 'scheduled')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Scheduled</span>
                    @elseif($trip->status == 'in_progress')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 animate-pulse">In Progress</span>
                    @elseif($trip->status == 'completed')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">Completed</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ ucfirst($trip->status) }}</span>
                    @endif
                </td>
                <td class="p-4 text-right">
                    <a href="{{ route('driver.route_details', $trip->id) }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition">
                        <i data-lucide="map" class="w-4 h-4 mr-2"></i> Route
                    </a>
                    <a href="{{ route('driver.passenger_list', $trip->id) }}" class="inline-flex items-center justify-center bg-primary-gold text-dark-maroon px-4 py-2 rounded-lg font-bold hover:bg-yellow-400 transition ml-2">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i> Passengers
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


<h2 class="text-lg font-bold text-dark-text mb-4">Upcoming Trips</h2>

@if($upcomingTrips->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
    <p class="text-gray-500">No upcoming trips scheduled.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-medium">Date</th>
                <th class="p-4 border-b font-medium">Route</th>
                <th class="p-4 border-b font-medium">Bus</th>
                <th class="p-4 border-b font-medium">Departure</th>
                <th class="p-4 border-b font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @foreach($upcomingTrips as $trip)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-bold text-dark-text">
                    {{ \Carbon\Carbon::parse($trip->date)->format('M d, Y') }}
                </td>
                <td class="p-4 text-gray-600 font-medium">
                    {{ $trip->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-3 h-3 inline text-gray-400 mx-1"></i> {{ $trip->route->toLocation->name }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ $trip->bus->bus_number }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('h:i A') }}
                </td>
                <td class="p-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Scheduled</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
