@extends('layouts.admin')

@section('title', 'View Booking')
@section('header', 'View Booking: ' . ($booking->booking_reference ?? '#'.$booking->id))

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.bookings') }}" class="text-gray-500 hover:text-primary-maroon font-bold text-sm transition flex items-center">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to Bookings
    </a>
    <div>
        <a href="{{ route('admin.bookings.edit', $booking) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-sm mr-2 text-sm inline-flex items-center">
            <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Booking
        </a>
        <a href="{{ route('booking.ticket', $booking->id) }}" target="_blank" class="bg-primary-maroon text-white px-4 py-2 rounded-lg font-bold hover:bg-dark-maroon transition shadow-sm text-sm inline-flex items-center">
            <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print Ticket
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Passenger Info -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-extrabold text-lg text-dark-text border-b border-gray-100 pb-4 mb-4">Passenger Details</h3>
        
        <div class="space-y-4">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Customer Name</span>
                <span class="font-bold text-gray-800 text-lg">{{ $booking->customer_name }}</span>
            </div>
            
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</span>
                <span class="font-bold text-gray-800">{{ $booking->phone }}</span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Boarding Point</span>
                    <span class="font-bold text-gray-800">{{ $booking->boarding_point ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Dropping Point</span>
                    <span class="font-bold text-gray-800">{{ $booking->dropping_point ?: 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Info -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-extrabold text-lg text-dark-text border-b border-gray-100 pb-4 mb-4">Booking & Payment Details</h3>
        
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status</span>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $booking->booking_status == 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->booking_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($booking->booking_status) }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Amount</span>
                    <span class="font-extrabold text-primary-maroon text-lg">LKR {{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>

            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Payment Method</span>
                <span class="font-bold text-gray-800">{{ ucfirst($booking->payment_method ?? 'Unknown') }}</span>
            </div>

            @if($booking->payment_receipt_path)
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Payment Receipt</span>
                <a href="{{ Storage::url($booking->payment_receipt_path) }}" target="_blank" class="text-blue-600 hover:underline font-bold inline-flex items-center text-sm">
                    <i data-lucide="external-link" class="w-4 h-4 mr-1"></i> View Receipt File
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Trip Info -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 md:col-span-2">
        <h3 class="font-extrabold text-lg text-dark-text border-b border-gray-100 pb-4 mb-4">Trip Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Route</span>
                <span class="font-bold text-gray-800">{{ $booking->schedule?->route?->fromLocation?->name }} &rarr; {{ $booking->schedule?->route?->toLocation?->name }}</span>
            </div>
            
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date & Time</span>
                <span class="font-bold text-gray-800">
                    {{ $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->date)->format('M d, Y') : 'N/A' }} | 
                    {{ $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') : '' }}
                </span>
            </div>

            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bus Company</span>
                <span class="font-bold text-gray-800">{{ $booking->schedule?->bus?->busCompany?->company_name ?? 'N/A' }}</span>
                <div class="text-xs text-gray-500 mt-1">Bus: {{ $booking->schedule?->bus?->bus_number ?? 'N/A' }}</div>
            </div>
            
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Passengers</span>
                <span class="font-bold text-gray-800">{{ $booking->passenger_count }} Person(s)</span>
            </div>

            <div class="md:col-span-2">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Assigned Seats</span>
                @if(empty($booking->seat_numbers))
                    <span class="text-yellow-600 font-bold bg-yellow-50 px-3 py-1 rounded text-sm">No seats assigned yet</span>
                @else
                    <div class="flex flex-wrap gap-2">
                        @php
                            $seats = is_array($booking->seat_numbers) ? $booking->seat_numbers : explode(',', $booking->seat_numbers);
                        @endphp
                        @foreach($seats as $seat)
                            <span class="bg-gray-100 border border-gray-200 text-dark-maroon font-extrabold px-3 py-1.5 rounded-lg text-sm shadow-sm">
                                <i data-lucide="armchair" class="w-4 h-4 inline mr-1 text-gray-400"></i>{{ trim($seat) }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
