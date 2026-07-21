@extends('layouts.admin')

@section('title', 'Passenger List')
@section('header', 'Passenger List - ' . $schedule->route->fromLocation->name . ' to ' . $schedule->route->toLocation->name)

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-gray-500 font-medium">Bus: <span class="text-dark-text font-bold">{{ $schedule->bus->bus_number }}</span></p>
        <p class="text-gray-500 font-medium">Departure: <span class="text-dark-text font-bold">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span></p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-dark-text">Boarding Passengers</h3>
        
        <div class="flex gap-4">
            <span class="flex items-center text-sm font-medium text-gray-500"><div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div> Boarded</span>
            <span class="flex items-center text-sm font-medium text-gray-500"><div class="w-3 h-3 rounded-full bg-orange-400 mr-2"></div> Pending</span>
            <span class="flex items-center text-sm font-medium text-gray-500"><div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div> No Show</span>
        </div>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-[800px]">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-medium">Seat</th>
                <th class="p-4 border-b font-medium">Passenger</th>
                <th class="p-4 border-b font-medium">Contact</th>
                <th class="p-4 border-b font-medium">Boarding Point</th>
                <th class="p-4 border-b font-medium">Status</th>
                <th class="p-4 border-b font-medium text-right">Action</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @php
                $passengers = [];
                foreach($schedule->bookings as $booking) {
                    if($booking->booking_status !== 'cancelled') {
                        $seats = $booking->seat_numbers ?? [];
                        $statuses = $booking->boarding_statuses ?? [];
                        foreach($seats as $seat) {
                            $passengers[] = [
                                'seat' => $seat,
                                'booking' => $booking,
                                'status' => $statuses[$seat] ?? 'pending'
                            ];
                        }
                    }
                }
                
                // Sort by seat number for easier reading
                usort($passengers, function($a, $b) {
                    return strcmp($a['seat'], $b['seat']);
                });
            @endphp

            @forelse($passengers as $p)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-bold text-dark-text">{{ $p['seat'] }}</td>
                <td class="p-4">
                    <p class="font-bold text-dark-text">{{ $p['booking']->customer_name }}</p>
                    <p class="text-xs text-gray-500 flex items-center">
                        Ref: {{ $p['booking']->booking_reference }}
                        @if($p['booking']->is_verified)
                        <i data-lucide="check-circle" class="w-3 h-3 text-indigo-500 ml-1" title="Verified"></i>
                        @endif
                    </p>
                </td>
                <td class="p-4 text-gray-600">{{ $p['booking']->phone }}</td>
                <td class="p-4 text-gray-600">{{ $p['booking']->boarding_point ?? $schedule->route->fromLocation->name }}</td>
                <td class="p-4">
                    @if($p['status'] == 'boarded')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Boarded</span>
                    @elseif($p['status'] == 'no_show')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">No Show</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">Pending</span>
                    @endif
                </td>
                <td class="p-4 text-right whitespace-nowrap">
                    @if(!$p['booking']->is_verified)
                    <form action="{{ route('conductor.verify_ticket', $p['booking']->id) }}" method="POST" class="inline mr-1">
                        @csrf
                        <button type="submit" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-1.5 rounded text-xs font-bold transition">
                            <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i> Verify
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('conductor.mark_boarded', $p['booking']->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="seat_number" value="{{ $p['seat'] }}">
                        @if($p['status'] !== 'boarded')
                            <input type="hidden" name="status" value="boarded">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-xs font-bold transition">
                                <i data-lucide="check" class="w-3 h-3 inline mr-1"></i> Boarded
                            </button>
                        @endif
                    </form>
                    
                    <form action="{{ route('conductor.mark_boarded', $p['booking']->id) }}" method="POST" class="inline ml-1">
                        @csrf
                        <input type="hidden" name="seat_number" value="{{ $p['seat'] }}">
                        @if($p['status'] !== 'no_show')
                            <input type="hidden" name="status" value="no_show">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-bold transition">
                                <i data-lucide="x" class="w-3 h-3 inline mr-1"></i> No Show
                            </button>
                        @endif
                    </form>

                    @if($p['status'] !== 'pending')
                    <form action="{{ route('conductor.mark_boarded', $p['booking']->id) }}" method="POST" class="inline ml-1">
                        @csrf
                        <input type="hidden" name="seat_number" value="{{ $p['seat'] }}">
                        <input type="hidden" name="status" value="pending">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded text-xs font-bold transition" title="Undo">
                            <i data-lucide="undo" class="w-3 h-3 inline"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-gray-500">
                    No passengers booked for this trip yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection
