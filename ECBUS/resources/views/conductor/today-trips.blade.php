@extends('layouts.admin')

@section('title', "Today's Trips")
@section('header', "Today's Trips")

@section('content')

@if($trips->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
        <i data-lucide="bus" class="w-10 h-10"></i>
    </div>
    <h3 class="text-xl font-bold text-dark-text mb-2">No Trips Today</h3>
    <p class="text-gray-500">You don't have any trips assigned for today.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-medium">Route</th>
                <th class="p-4 border-b font-medium">Bus</th>
                <th class="p-4 border-b font-medium">Departure</th>
                <th class="p-4 border-b font-medium">Driver</th>
                <th class="p-4 border-b font-medium">Status</th>
                <th class="p-4 border-b font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @foreach($trips as $trip)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-medium text-dark-text">
                    {{ $trip->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-3 h-3 inline text-gray-400"></i> {{ $trip->route->toLocation->name }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ $trip->bus->bus_number }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('h:i A') }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ $trip->driver->name ?? 'N/A' }}
                </td>
                <td class="p-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $trip->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                </td>
                <td class="p-4 text-right">
                    <a href="{{ route('conductor.passenger_list', $trip->id) }}" class="inline-flex items-center justify-center bg-primary-gold text-dark-maroon px-4 py-2 rounded-lg font-bold hover:bg-yellow-400 transition">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i> Passengers
                    </a>
                    <a href="{{ route('conductor.seat_verification', $trip->id) }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition ml-2">
                        <i data-lucide="layout-grid" class="w-4 h-4 mr-2"></i> Seats
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
