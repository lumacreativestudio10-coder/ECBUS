@extends('layouts.admin')

@section('title', 'Manage Seats')
@section('header', 'Manage Seats: ' . $schedule->bus->busCompany->company_name)

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <ul class="list-disc list-inside font-bold text-sm">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
    <div class="flex flex-wrap items-center text-gray-500 text-sm font-bold">
        <a href="{{ route(auth()->user()->getRolePrefix().'.schedules') }}" class="hover:text-primary-maroon transition flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to Schedules
        </a>
        <span class="mx-3">|</span>
        <span class="text-dark-text">{{ $schedule->route->fromLocation->name ?? '?' }} &rarr; {{ $schedule->route->toLocation->name ?? '?' }}</span>
        <span class="mx-3">|</span>
        <span>{{ \Carbon\Carbon::parse($schedule->date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</span>
    </div>

    <div class="flex flex-wrap gap-4">
        <div class="bg-white rounded-lg border border-gray-200 px-4 py-2 shadow-sm text-right">
            <p class="text-[10px] text-gray-500 uppercase">Gross Revenue</p>
            <p class="text-base font-bold text-dark-text">LKR {{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-red-50 rounded-lg border border-red-100 px-4 py-2 shadow-sm text-right">
            <p class="text-[10px] text-red-500 uppercase">Commission</p>
            <p class="text-base font-bold text-red-700">LKR {{ number_format($commissionAmount, 2) }}</p>
        </div>
        <div class="bg-green-50 rounded-lg border border-green-100 px-4 py-2 shadow-sm text-right">
            <p class="text-[10px] text-green-500 uppercase">Net Revenue</p>
            <p class="text-base font-extrabold text-green-700">LKR {{ number_format($netRevenue, 2) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Seat Map -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col items-center">
            
            <div class="w-full max-w-sm flex justify-between mb-8 text-xs font-bold text-gray-500 uppercase">
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-gray-200 mr-2 border border-gray-300"></div> Available</div>
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-red-500 mr-2 shadow-sm shadow-red-500/50"></div> Booked</div>
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-primary-gold mr-2 shadow-sm shadow-primary-gold/50"></div> Selected</div>
            </div>

            <!-- Front of Bus -->
            <div class="w-full max-w-sm border-2 border-gray-300 rounded-[2rem] p-4 bg-gray-50 mb-8 relative overflow-hidden" x-data="seatMap()">
                <div class="absolute top-0 inset-x-0 h-12 bg-gray-200 border-b-2 border-gray-300 rounded-t-[1.8rem] flex justify-center items-center">
                    <div class="w-20 h-2 bg-gray-300 rounded-full"></div>
                </div>
                
                <div class="mt-16 relative">
                    <!-- Steering Wheel -->
                    <div class="absolute -top-8 right-4">
                        <i data-lucide="circle-dashed" class="w-8 h-8 text-gray-400"></i>
                    </div>

                    <div>
                        <div class="space-y-4">
                            @if($schedule->bus->seat_layout && is_array($schedule->bus->seat_layout) && isset($schedule->bus->seat_layout['map']))
                                @php
                                    $layout = $schedule->bus->seat_layout;
                                    $cols = $layout['cols'];
                                @endphp
                                @foreach($layout['map'] as $rIndex => $rowConfig)
                                    @php $rowNum = $rIndex + 1; @endphp
                                    <div class="flex justify-center space-x-2">
                                        @foreach($rowConfig as $cIndex => $cellType)
                                            @if($cellType === 'empty')
                                                <!-- Empty Aisle Space -->
                                                <div class="w-10 h-10"></div>
                                            @else
                                                @php
                                                    // Generate a seat ID like 1A, 1B, 1C based on column index
                                                    $seatChar = chr(65 + $cIndex); 
                                                    $seatId = $rowNum . $seatChar;
                                                @endphp
                                                
                                                @if(in_array($seatId, $bookedSeats))
                                                    <!-- Booked Seat -->
                                                    <div @mouseenter="hoveredBooking = {{ $seatDetails[$seatId]->id }}" @mouseleave="hoveredBooking = null" :class="hoveredBooking == {{ $seatDetails[$seatId]->id }} ? 'bg-primary-maroon scale-110 shadow-lg shadow-primary-maroon/50 z-40' : 'bg-red-500 shadow-sm shadow-red-500/50 z-10'" class="w-10 h-10 rounded-t-lg rounded-b flex flex-col justify-end items-center pb-1 text-white text-[10px] font-bold cursor-not-allowed group relative transition-all duration-300">
                                                        {{ $seatId }}
                                                        <div class="absolute bottom-full mb-3 hidden group-hover:block w-48 bg-dark-text text-white text-left p-3 rounded-xl shadow-xl z-50 pointer-events-none">
                                                            <div class="font-extrabold text-[11px] text-gray-400 mb-1 border-b border-gray-600 pb-1">Ref: {{ $seatDetails[$seatId]->booking_reference ?? '#'.$seatDetails[$seatId]->id }}</div>
                                                            <div class="font-bold text-sm mb-1">{{ $seatDetails[$seatId]->customer_name ?? 'Unknown' }}</div>
                                                            <div class="text-[11px] text-gray-300 mb-1">Phone: {{ $seatDetails[$seatId]->phone ?? 'N/A' }}</div>
                                                            <div class="text-[11px] text-gray-300">Seats: {{ is_array($seatDetails[$seatId]->seat_numbers) ? implode(', ', $seatDetails[$seatId]->seat_numbers) : $seatDetails[$seatId]->seat_numbers }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Available Seat -->
                                                    <button type="button" @click="toggleSeat('{{ $seatId }}')" 
                                                        :class="selectedSeats.includes('{{ $seatId }}') ? 'bg-primary-gold text-dark-maroon shadow-primary-gold/50' : 'bg-white hover:bg-gray-100 hover:border-gray-300 border-gray-200 {{ $cellType === 'window' ? 'border-blue-300 text-blue-700 bg-blue-50' : 'text-gray-400' }}'" 
                                                        class="w-10 h-10 border-2 rounded-t-lg rounded-b shadow-sm flex flex-col justify-end items-center pb-1 text-[10px] font-bold transition relative">
                                                        {{ $seatId }}
                                                        @if($cellType === 'window')
                                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-400 rounded-full border border-white"></div>
                                                        @endif
                                                    </button>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            @else
                                <!-- Fallback standard 2x2 layout -->
                                @php
                                    $totalRows = ceil($schedule->bus->total_seats / 4);
                                @endphp
                                @for($row = 1; $row <= $totalRows; $row++)
                                    <div class="flex justify-between">
                                        <!-- Left Side (A, B) -->
                                        <div class="flex space-x-3">
                                            @foreach(['A', 'B'] as $col)
                                                @php $seatId = $row . $col; @endphp
                                                @if(in_array($seatId, $bookedSeats))
                                                    <div @mouseenter="hoveredBooking = {{ $seatDetails[$seatId]->id }}" @mouseleave="hoveredBooking = null" :class="hoveredBooking == {{ $seatDetails[$seatId]->id }} ? 'bg-primary-maroon scale-110 shadow-lg shadow-primary-maroon/50 z-40' : 'bg-red-500 shadow-sm shadow-red-500/50 z-10'" class="w-10 h-10 rounded-t-lg rounded-b flex flex-col justify-end items-center pb-1 text-white text-[10px] font-bold cursor-not-allowed group relative transition-all duration-300">
                                                        {{ $seatId }}
                                                        <div class="absolute bottom-full mb-3 hidden group-hover:block w-48 bg-dark-text text-white text-left p-3 rounded-xl shadow-xl z-50 pointer-events-none">
                                                            <div class="font-extrabold text-[11px] text-gray-400 mb-1 border-b border-gray-600 pb-1">Ref: {{ $seatDetails[$seatId]->booking_reference ?? '#'.$seatDetails[$seatId]->id }}</div>
                                                            <div class="font-bold text-sm mb-1">{{ $seatDetails[$seatId]->customer_name ?? 'Unknown' }}</div>
                                                            <div class="text-[11px] text-gray-300 mb-1">Phone: {{ $seatDetails[$seatId]->phone ?? 'N/A' }}</div>
                                                            <div class="text-[11px] text-gray-300">Seats: {{ is_array($seatDetails[$seatId]->seat_numbers) ? implode(', ', $seatDetails[$seatId]->seat_numbers) : $seatDetails[$seatId]->seat_numbers }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <button type="button" @click="toggleSeat('{{ $seatId }}')" :class="selectedSeats.includes('{{ $seatId }}') ? 'bg-primary-gold text-dark-maroon shadow-primary-gold/50' : 'bg-white text-gray-400 hover:bg-gray-100 hover:border-gray-300 border-gray-200'" class="w-10 h-10 border-2 rounded-t-lg rounded-b shadow-sm flex flex-col justify-end items-center pb-1 text-[10px] font-bold transition">
                                                        {{ $seatId }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        <!-- Aisle -->
                                        <div class="w-10 flex items-center justify-center text-gray-300 text-xs font-bold">{{ $row }}</div>
                                        
                                        <!-- Right Side (C, D) -->
                                        <div class="flex space-x-3">
                                            @foreach(['C', 'D'] as $col)
                                                @php $seatId = $row . $col; @endphp
                                                @if(($row - 1) * 4 + (ord($col) - 64) <= $schedule->bus->total_seats)
                                                    @if(in_array($seatId, $bookedSeats))
                                                        <div @mouseenter="hoveredBooking = {{ $seatDetails[$seatId]->id }}" @mouseleave="hoveredBooking = null" :class="hoveredBooking == {{ $seatDetails[$seatId]->id }} ? 'bg-primary-maroon scale-110 shadow-lg shadow-primary-maroon/50 z-40' : 'bg-red-500 shadow-sm shadow-red-500/50 z-10'" class="w-10 h-10 rounded-t-lg rounded-b flex flex-col justify-end items-center pb-1 text-white text-[10px] font-bold cursor-not-allowed group relative transition-all duration-300">
                                                            {{ $seatId }}
                                                            <div class="absolute bottom-full mb-3 hidden group-hover:block w-48 bg-dark-text text-white text-left p-3 rounded-xl shadow-xl z-50 pointer-events-none">
                                                                <div class="font-extrabold text-[11px] text-gray-400 mb-1 border-b border-gray-600 pb-1">Ref: {{ $seatDetails[$seatId]->booking_reference ?? '#'.$seatDetails[$seatId]->id }}</div>
                                                                <div class="font-bold text-sm mb-1">{{ $seatDetails[$seatId]->customer_name ?? 'Unknown' }}</div>
                                                                <div class="text-[11px] text-gray-300 mb-1">Phone: {{ $seatDetails[$seatId]->phone ?? 'N/A' }}</div>
                                                                <div class="text-[11px] text-gray-300">Seats: {{ is_array($seatDetails[$seatId]->seat_numbers) ? implode(', ', $seatDetails[$seatId]->seat_numbers) : $seatDetails[$seatId]->seat_numbers }}</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <button type="button" @click="toggleSeat('{{ $seatId }}')" :class="selectedSeats.includes('{{ $seatId }}') ? 'bg-primary-gold text-dark-maroon shadow-primary-gold/50' : 'bg-white text-gray-400 hover:bg-gray-100 hover:border-gray-300 border-gray-200'" class="w-10 h-10 border-2 rounded-t-lg rounded-b shadow-sm flex flex-col justify-end items-center pb-1 text-[10px] font-bold transition">
                                                            {{ $seatId }}
                                                        </button>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Manual Booking Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">
                    {{ isset($targetBooking) ? 'Assign Seats' : 'Manual Booking' }}
                </h3>
            </div>
            <div class="p-6">
                @if(isset($targetBooking))
                    <div class="bg-blue-50 border border-blue-100 text-blue-700 p-3 rounded-lg text-xs font-bold mb-6">
                        <i data-lucide="info" class="w-4 h-4 inline mr-1"></i> Assigning seats for Booking: {{ $targetBooking->booking_reference }} ({{ $targetBooking->passenger_count }} passengers)
                    </div>
                @else
                    <p class="text-sm text-gray-500 mb-6">Select available seats on the map to book them manually for offline customers.</p>
                @endif
                
                <form action="{{ route(auth()->user()->getRolePrefix().'.schedules.seats.update', $schedule) }}" method="POST" id="manual-booking-form">
                    @csrf
                    
                    @if(isset($targetBooking))
                        <input type="hidden" name="target_booking_id" value="{{ $targetBooking->id }}">
                    @endif
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Passenger Name</label>
                        <input type="text" name="customer_name" required {{ isset($targetBooking) ? 'readonly' : '' }} value="{{ $targetBooking->customer_name ?? '' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition {{ isset($targetBooking) ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone_number" required {{ isset($targetBooking) ? 'readonly' : '' }} value="{{ $targetBooking->phone ?? '' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition {{ isset($targetBooking) ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email Address (Optional)</label>
                        <input type="email" name="email" {{ isset($targetBooking) ? 'readonly' : '' }} value="{{ $targetBooking->email ?? '' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition {{ isset($targetBooking) ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                    </div>


                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Boarding Point (Optional)</label>
                        <select name="boarding_point" {{ isset($targetBooking) ? 'disabled' : '' }} class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition {{ isset($targetBooking) ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                            <option value="">Select boarding point</option>
                            @if($schedule->route?->fromLocation)
                                <option value="{{ $schedule->route->fromLocation->name }}" {{ (isset($targetBooking) && $targetBooking->boarding_point == $schedule->route->fromLocation->name) ? 'selected' : '' }}>{{ $schedule->route->fromLocation->name }}</option>
                            @endif
                            @if($schedule->route?->stops)
                                @foreach($schedule->route->stops as $stop)
                                    <option value="{{ $stop->stop_name }}" {{ (isset($targetBooking) && $targetBooking->boarding_point == $stop->stop_name) ? 'selected' : '' }}>{{ $stop->stop_name }}</option>
                                @endforeach
                            @endif
                            @if($schedule->route?->toLocation)
                                <option value="{{ $schedule->route->toLocation->name }}" {{ (isset($targetBooking) && $targetBooking->boarding_point == $schedule->route->toLocation->name) ? 'selected' : '' }}>{{ $schedule->route->toLocation->name }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Dropping Point (Optional)</label>
                        <select name="dropping_point" {{ isset($targetBooking) ? 'disabled' : '' }} class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition {{ isset($targetBooking) ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                            <option value="">Select dropping point</option>
                            @if($schedule->route?->fromLocation)
                                <option value="{{ $schedule->route->fromLocation->name }}" {{ (isset($targetBooking) && $targetBooking->dropping_point == $schedule->route->fromLocation->name) ? 'selected' : '' }}>{{ $schedule->route->fromLocation->name }}</option>
                            @endif
                            @if($schedule->route?->stops)
                                @foreach($schedule->route->stops as $stop)
                                    <option value="{{ $stop->stop_name }}" {{ (isset($targetBooking) && $targetBooking->dropping_point == $stop->stop_name) ? 'selected' : '' }}>{{ $stop->stop_name }}</option>
                                @endforeach
                            @endif
                            @if($schedule->route?->toLocation)
                                <option value="{{ $schedule->route->toLocation->name }}" {{ (isset($targetBooking) && $targetBooking->dropping_point == $schedule->route->toLocation->name) ? 'selected' : '' }}>{{ $schedule->route->toLocation->name }}</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500 font-bold">Ticket Price</span>
                            <span class="text-sm font-bold text-dark-text">LKR {{ number_format($schedule->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                            <span class="text-sm text-gray-700 font-extrabold">Selected Seats</span>
                            <span class="text-lg font-extrabold text-primary-maroon" id="display-selected-count">0</span>
                        </div>
                    </div>

                    <button type="submit" onclick="return prepareForm()" class="w-full bg-primary-maroon text-white font-bold rounded-lg px-4 py-3 hover:bg-dark-maroon transition shadow-md">
                        Confirm Manual Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function prepareForm() {
        const rawValue = document.getElementById('selected-seats-input') ? document.getElementById('selected-seats-input').value : '[]';
        if(!rawValue || rawValue === '[]') {
            alert('Please select at least one seat from the map.');
            return false;
        }
        
        const seats = JSON.parse(rawValue);
        
        @if(isset($targetBooking))
        if (seats.length !== {{ $targetBooking->passenger_count }}) {
            alert('Please select exactly {{ $targetBooking->passenger_count }} seat(s) for this booking.');
            return false;
        }
        @endif
        
        // Remove old inputs
        document.querySelectorAll('.seat-input-array').forEach(e => e.remove());
        
        // Create hidden inputs for each seat
        const form = document.getElementById('manual-booking-form');
        
        seats.forEach(seat => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seat_numbers[]';
            input.value = seat;
            input.className = 'seat-input-array';
            form.appendChild(input);
        });
        
        return true;
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('seatMap', () => ({
            selectedSeats: [],
            hoveredBooking: null,
            maxSeats: {{ isset($targetBooking) ? $targetBooking->passenger_count : 'null' }},
            toggleSeat(seat) {
                if(this.selectedSeats.includes(seat)) {
                    this.selectedSeats = this.selectedSeats.filter(s => s !== seat);
                } else {
                    if (this.maxSeats !== null && this.selectedSeats.length >= this.maxSeats) {
                        alert(`You can only select ${this.maxSeats} seat(s) for this booking.`);
                        return;
                    }
                    this.selectedSeats.push(seat);
                }
                
                // Expose to outside
                if(!document.getElementById('selected-seats-input')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.id = 'selected-seats-input';
                    document.body.appendChild(input);
                }
                
                document.getElementById('selected-seats-input').value = JSON.stringify(this.selectedSeats);
                document.getElementById('display-selected-count').innerText = this.selectedSeats.length;
            }
        }));
    });
</script>
@endpush
