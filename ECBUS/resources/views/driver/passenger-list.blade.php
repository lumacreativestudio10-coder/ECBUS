@extends('layouts.admin')

@section('title', 'Passenger List')
@section('header', 'Passenger List')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-dark-text mb-1">{{ $schedule->route->fromLocation->name }} to {{ $schedule->route->toLocation->name }}</h2>
        <p class="text-gray-500 font-medium">Departure: <span class="text-dark-text font-bold">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span></p>
    </div>
    <a href="{{ route('driver.route_details', $schedule->id) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition flex items-center shadow-sm">
        <i data-lucide="map" class="w-4 h-4 mr-2"></i> View Route
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-dark-text">Passengers Manifest</h3>
        <p class="text-sm font-medium text-gray-500">
            Total Bookings: <span class="font-bold text-dark-text">{{ $schedule->bookings->where('booking_status', '!=', 'cancelled')->count() }}</span>
        </p>
    </div>
    
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-medium">Seat(s)</th>
                <th class="p-4 border-b font-medium">Passenger Name</th>
                <th class="p-4 border-b font-medium">Phone</th>
                <th class="p-4 border-b font-medium">Boarding Point</th>
                <th class="p-4 border-b font-medium">Dropping Point</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse($schedule->bookings->where('booking_status', '!=', 'cancelled') as $booking)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-bold text-dark-maroon">
                    @if($booking->seat_numbers)
                        {{ implode(', ', $booking->seat_numbers) }}
                    @else
                        {{ $booking->passenger_count }} Seats
                    @endif
                </td>
                <td class="p-4 font-bold text-dark-text">
                    {{ $booking->customer_name }}
                </td>
                <td class="p-4 text-gray-600">
                    {{ $booking->phone }}
                </td>
                <td class="p-4 text-gray-600 font-medium">
                    {{ $booking->boarding_point ?? $schedule->route->fromLocation->name }}
                </td>
                <td class="p-4 text-gray-600 font-medium">
                    {{ $booking->dropping_point ?? $schedule->route->toLocation->name }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-10 text-center text-gray-500">
                    <i data-lucide="users" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                    No passengers booked for this trip yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
