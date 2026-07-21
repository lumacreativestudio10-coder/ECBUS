@extends('layouts.admin')

@section('title', 'Ticket Verification')
@section('header', 'Ticket Verification')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Search Section -->
    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xl font-bold text-dark-text mb-6 flex items-center">
                <i data-lucide="search" class="w-5 h-5 mr-3 text-primary-gold"></i> Find Ticket
            </h3>

            <form action="{{ route('conductor.search_ticket') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Booking Reference or Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="ticket" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="query" value="{{ $query ?? '' }}" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-primary-maroon focus:border-primary-maroon bg-gray-50"
                            placeholder="e.g. ECB000123 or 0771234567">
                    </div>
                    @error('query')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-dark-maroon hover:bg-red-900 text-white font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center">
                    Search Ticket
                </button>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div>
        @if(isset($query))
            @if(isset($booking) && $booking)
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -z-10"></div>
                    
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold mb-2 inline-block">Valid Ticket Found</span>
                            <h3 class="text-2xl font-bold text-dark-text">{{ $booking->customer_name }}</h3>
                            <p class="text-gray-500 font-medium">{{ $booking->phone }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Ref No</p>
                            <p class="text-lg font-bold text-dark-maroon">{{ $booking->booking_reference }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 font-bold mb-1">Seats</p>
                            <p class="font-extrabold text-dark-text">{{ implode(', ', $booking->seat_numbers ?? []) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 font-bold mb-1">Payment</p>
                            <p class="font-bold text-green-600">Paid - LKR {{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl col-span-2">
                            <p class="text-xs text-gray-500 font-bold mb-1">Trip Details</p>
                            <p class="font-bold text-dark-text text-sm">
                                {{ $booking->schedule->route->fromLocation->name }} <i data-lucide="arrow-right" class="w-3 h-3 inline mx-1"></i> {{ $booking->schedule->route->toLocation->name }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Bus: {{ $booking->schedule->bus->bus_number }} | Departs: {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</p>
                        </div>
                    </div>

                    @if($booking->is_verified)
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-center text-indigo-700 font-bold">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> Ticket Already Verified
                        </div>
                    @else
                        <form action="{{ route('conductor.verify_ticket', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-4 rounded-xl transition shadow-md flex items-center justify-center text-lg">
                                <i data-lucide="shield-check" class="w-6 h-6 mr-2"></i> Verify Ticket Now
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-12 text-center h-full flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-4 text-red-500">
                        <i data-lucide="x-circle" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark-text mb-2">Ticket Not Found</h3>
                    <p class="text-gray-500">No booking found for today's trips matching "{{ $query }}".</p>
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center h-full flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                    <i data-lucide="search" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-400 mb-2">Search for a Ticket</h3>
                <p class="text-gray-400 text-sm">Enter a booking reference or phone number to verify.</p>
            </div>
        @endif
    </div>
</div>

@endsection
