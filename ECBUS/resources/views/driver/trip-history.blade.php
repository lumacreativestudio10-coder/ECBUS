@extends('layouts.admin')

@section('title', 'Trip History')
@section('header', 'Trip History')

@section('content')

<h2 class="text-xl font-bold text-dark-text mb-6">Completed Trips</h2>

@if($pastTrips->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
        <i data-lucide="history" class="w-10 h-10"></i>
    </div>
    <h3 class="text-xl font-bold text-dark-text mb-2">No past trips</h3>
    <p class="text-gray-500">Your completed trips will appear here.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-medium">Date</th>
                <th class="p-4 border-b font-medium">Route</th>
                <th class="p-4 border-b font-medium">Bus</th>
                <th class="p-4 border-b font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @foreach($pastTrips as $trip)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-bold text-dark-text">
                    {{ \Carbon\Carbon::parse($trip->date)->format('M d, Y') }}
                </td>
                <td class="p-4 font-bold text-dark-text">
                    {{ $trip->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-3 h-3 inline text-gray-400 mx-1"></i> {{ $trip->route->toLocation->name }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ $trip->bus->bus_number }}
                </td>
                <td class="p-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">Completed</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
