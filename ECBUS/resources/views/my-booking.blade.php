@extends('layouts.app')

@section('title', 'My Bookings - ECBUS')

@section('content')
<div x-data="{ activeTab: 'upcoming' }" class="bg-cream min-h-screen pb-20 pt-24 lg:pt-32">
    
    <div class="max-w-5xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-2 flex items-center justify-center md:justify-start">
                <i data-lucide="ticket" class="w-8 h-8 mr-3 text-primary-maroon"></i>
                My Tickets
            </h1>
            <p class="text-gray-500">Manage your upcoming journeys and view past booking history.</p>
        </div>

        <!-- Search Form if no phone provided -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10 max-w-lg mx-auto md:mx-0">
            <form action="{{ route('booking') }}" method="GET" class="flex flex-col space-y-4">
                <div>
                    <label class="block text-sm font-bold text-dark-text mb-2">Enter Phone Number</label>
                    <input type="text" name="phone" value="{{ $phone ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon outline-none" placeholder="e.g. 0771234567" required>
                </div>
                <button type="submit" class="bg-primary-maroon text-white font-bold py-3 rounded-xl hover:bg-dark-maroon transition shadow-md flex justify-center items-center">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i> Find My Bookings
                </button>
            </form>
        </div>

        @if(isset($phone))
            @if($bookings->isEmpty())
                <div class="bg-white p-10 rounded-2xl text-center border border-gray-100">
                    <i data-lucide="ghost" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-500">No bookings found for {{ $phone }}.</h3>
                </div>
            @else
                @php
                    $today = \Carbon\Carbon::today();
                    $upcoming = $bookings->filter(fn($b) => \Carbon\Carbon::parse($b->schedule->date) >= $today);
                    $past = $bookings->filter(fn($b) => \Carbon\Carbon::parse($b->schedule->date) < $today);
                @endphp

                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-8 overflow-x-auto whitespace-nowrap">
                    <button @click="activeTab = 'upcoming'" 
                            :class="activeTab === 'upcoming' ? 'border-primary-maroon text-primary-maroon font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="pb-4 px-6 border-b-4 transition duration-300 outline-none flex items-center">
                        <i data-lucide="calendar-clock" class="w-5 h-5 mr-2" :class="activeTab === 'upcoming' ? 'opacity-100' : 'opacity-50'"></i>
                        Upcoming Journeys ({{ $upcoming->count() }})
                    </button>
                    <button @click="activeTab = 'past'" 
                            :class="activeTab === 'past' ? 'border-primary-maroon text-primary-maroon font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="pb-4 px-6 border-b-4 transition duration-300 outline-none flex items-center">
                        <i data-lucide="history" class="w-5 h-5 mr-2" :class="activeTab === 'past' ? 'opacity-100' : 'opacity-50'"></i>
                        Past Bookings ({{ $past->count() }})
                    </button>
                </div>

                <!-- Upcoming Tab Content -->
                <div x-show="activeTab === 'upcoming'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                    @forelse($upcoming as $booking)
                        <x-booking-card :booking="$booking" />
                    @empty
                        <div class="text-center py-10 text-gray-400">No upcoming journeys.</div>
                    @endforelse
                </div>

                <!-- Past Bookings Tab Content -->
                <div x-show="activeTab === 'past'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
                    @forelse($past as $booking)
                        <x-booking-card :booking="$booking" />
                    @empty
                        <div class="text-center py-10 text-gray-400">No past journeys.</div>
                    @endforelse
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
