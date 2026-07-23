@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-primary-gold/20 flex items-center justify-center text-dark-maroon mr-4">
            <i data-lucide="ticket" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Total Bookings</p>
            <h3 class="text-3xl font-extrabold text-dark-text">{{ $totalBookings }}</h3>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center text-green-700 mr-4">
            <i data-lucide="banknote" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Total Revenue</p>
            <h3 class="text-3xl font-extrabold text-dark-text">LKR {{ number_format($totalRevenue, 2) }}</h3>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 mr-4">
            <i data-lucide="calendar-check" class="w-7 h-7"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Active Schedules</p>
            <h3 class="text-3xl font-extrabold text-dark-text">{{ $activeSchedules }}</h3>
        </div>
    </div>
</div>

<!-- My Assigned Schedules (Drivers & Conductors) -->
@if(isset($mySchedules) && $mySchedules->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-primary-maroon/5 to-primary-gold/5">
        <h3 class="font-extrabold text-lg text-dark-text flex items-center">
            <i data-lucide="bus-front" class="w-5 h-5 mr-2 text-primary-maroon"></i>
            My Assigned Schedules
        </h3>
        <span class="bg-primary-maroon text-white px-3 py-1 rounded-full text-xs font-bold">{{ $mySchedules->count() }} Trips</span>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($mySchedules as $schedule)
        <div class="px-6 py-4 hover:bg-gray-50/50 transition flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Route & Date -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-gold/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="map-pin" class="w-6 h-6 text-dark-maroon"></i>
                </div>
                <div>
                    <p class="font-extrabold text-dark-text text-base">
                        {{ $schedule->route?->fromLocation?->name ?? '?' }} &rarr; {{ $schedule->route?->toLocation?->name ?? '?' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <i data-lucide="calendar" class="w-3 h-3 inline"></i>
                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                        &bull;
                        <i data-lucide="clock" class="w-3 h-3 inline"></i>
                        {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}
                    </p>
                </div>
            </div>
            <!-- Bus Info -->
            <div class="flex items-center gap-3">
                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-bold">
                    <i data-lucide="bus" class="w-3 h-3 inline mr-1"></i>
                    {{ $schedule->bus->name ?? 'N/A' }} ({{ $schedule->bus->bus_number }})
                </span>
                <span class="bg-green-50 text-green-700 px-3 py-1 rounded-lg text-xs font-bold">
                    {{ $schedule->available_seats ?? $schedule->bus->total_seats }} Seats
                </span>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route(auth()->user()->getRolePrefix().'.schedules.seats', $schedule) }}" class="bg-primary-maroon text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-dark-maroon transition flex items-center">
                    <i data-lucide="armchair" class="w-4 h-4 mr-1"></i> Manage Seats
                </a>
                <a href="{{ route(auth()->user()->getRolePrefix().'.schedules.manifest', $schedule) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-200 transition flex items-center">
                    <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Manifest
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Recent Bookings Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">Recent Bookings</h3>
        <a href="{{ route(auth()->user()->getRolePrefix().'.bookings') }}" class="text-sm font-bold text-primary-maroon hover:text-dark-maroon transition">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                    <th class="px-6 py-4">Booking ID</th>
                    <th class="px-6 py-4">Passenger Name</th>
                    <th class="px-6 py-4">Bus</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($recentBookings as $booking)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-dark-text">{{ $booking->booking_reference ?? '#'.$booking->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-dark-text">{{ $booking->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->phone }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-700">{{ $booking->schedule?->bus?->busCompany?->company_name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 font-extrabold text-dark-maroon">LKR {{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($booking->booking_status == 'confirmed')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Confirmed</span>
                        @elseif($booking->booking_status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                        @elseif($booking->booking_status == 'completed')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Completed</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Cancelled</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No recent bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

