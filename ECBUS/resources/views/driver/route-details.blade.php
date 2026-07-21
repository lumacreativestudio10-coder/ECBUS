@extends('layouts.admin')

@section('title', 'Route Details')
@section('header', 'Route Details')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-dark-text mb-1">Route Overview</h2>
        <p class="text-gray-500 font-medium">Bus: <span class="text-dark-text font-bold">{{ $schedule->bus->bus_number }}</span></p>
    </div>
    <a href="{{ route('driver.passenger_list', $schedule->id) }}" class="bg-primary-gold text-dark-maroon px-4 py-2 rounded-lg font-bold hover:bg-yellow-400 transition flex items-center shadow-sm">
        <i data-lucide="users" class="w-4 h-4 mr-2"></i> Passenger List
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
            <h3 class="font-bold text-lg text-dark-text mb-6">Trip Timeline</h3>
            
            <div class="relative border-l-2 border-gray-200 ml-3 space-y-8">
                <!-- Origin -->
                <div class="relative pl-6">
                    <div class="absolute w-4 h-4 bg-green-500 rounded-full -left-[9px] top-1 border-2 border-white"></div>
                    <p class="text-xs text-green-600 font-bold uppercase tracking-wider mb-1">Start Origin</p>
                    <h4 class="font-bold text-dark-text">{{ $schedule->route->fromLocation->name }}</h4>
                    <p class="text-sm text-gray-500">Departure: {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</p>
                </div>
                
                @if($schedule->route->stops && $schedule->route->stops->count() > 0)
                    @foreach($schedule->route->stops as $stop)
                    <div class="relative pl-6">
                        <div class="absolute w-4 h-4 bg-gray-300 rounded-full -left-[9px] top-1 border-2 border-white"></div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Intermediate Stop</p>
                        <h4 class="font-bold text-dark-text">{{ $stop->stop_name }}</h4>
                        @if($stop->time_offset_minutes)
                            <p class="text-sm text-gray-500">+{{ $stop->time_offset_minutes }} mins from origin</p>
                        @endif
                    </div>
                    @endforeach
                @endif
                
                <!-- Destination -->
                <div class="relative pl-6">
                    <div class="absolute w-4 h-4 bg-red-500 rounded-full -left-[9px] top-1 border-2 border-white"></div>
                    <p class="text-xs text-red-600 font-bold uppercase tracking-wider mb-1">Final Destination</p>
                    <h4 class="font-bold text-dark-text">{{ $schedule->route->toLocation->name }}</h4>
                    <p class="text-sm text-gray-500">Arrival: {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}</p>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Total Distance:</span>
                    <span class="font-bold text-dark-text">{{ $schedule->route->distance ?? '--' }} km</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-2">
                    <span class="text-gray-500 font-medium">Est. Duration:</span>
                    <span class="font-bold text-dark-text">
                        @if($schedule->route->estimated_duration_minutes)
                            {{ intdiv($schedule->route->estimated_duration_minutes, 60) }}h {{ $schedule->route->estimated_duration_minutes % 60 }}m
                        @else
                            --
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="md:col-span-2 space-y-6">
        <div class="bg-gray-900 rounded-2xl shadow-sm p-8 text-center text-white h-64 flex flex-col justify-center items-center relative overflow-hidden">
            <!-- Decorative elements for map placeholder -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
            
            <i data-lucide="map-pinned" class="w-16 h-16 text-primary-gold mb-4 relative z-10"></i>
            <h3 class="text-xl font-bold mb-2 relative z-10">Map Visualization</h3>
            <p class="text-gray-400 max-w-sm relative z-10">Map integration for turn-by-turn navigation will be available in future updates.</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-dark-text mb-4">Boarding Instructions</h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"></i>
                    Ensure you arrive at the origin point at least 30 minutes before departure.
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"></i>
                    Verify the bus status and route layout with your conductor.
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"></i>
                    Make sure to halt safely at all designated boarding points.
                </li>
            </ul>
        </div>
    </div>
</div>

@endsection
