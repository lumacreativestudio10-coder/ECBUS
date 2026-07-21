@extends('layouts.admin')

@section('title', 'Conductor Dashboard')
@section('header', 'Conductor Dashboard')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold text-dark-maroon mb-4">Passenger Manifest (Today)</h3>
    @if(isset($todayTrips) && $todayTrips->count() > 0)
        @foreach($todayTrips as $trip)
            <div class="mb-6 border-b pb-4">
                <div class="font-bold text-xl mb-2">{{ $trip->route->name ?? 'Route' }} - {{ $trip->departure_time }}</div>
                @if($trip->bookings->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-2">Passenger</th>
                                <th class="p-2">Phone</th>
                                <th class="p-2">Seats</th>
                                <th class="p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trip->bookings as $booking)
                                <tr class="border-b">
                                    <td class="p-2">{{ $booking->customer_name }}</td>
                                    <td class="p-2">{{ $booking->phone }}</td>
                                    <td class="p-2">{{ is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : $booking->seat_numbers }}</td>
                                    <td class="p-2">{{ ucfirst($booking->booking_status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No bookings for this trip yet.</p>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-gray-500">You have no active trips right now.</p>
    @endif
</div>
@endsection
