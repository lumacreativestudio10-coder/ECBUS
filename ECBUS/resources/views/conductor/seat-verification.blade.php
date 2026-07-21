@extends('layouts.admin')

@section('title', 'Seat Verification')
@section('header', 'Seat Verification')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-gray-500 font-medium">Bus: <span class="text-dark-text font-bold">{{ $schedule->bus->bus_number }}</span></p>
        <p class="text-gray-500 font-medium">Route: <span class="text-dark-text font-bold">{{ $schedule->route->fromLocation->name }} to {{ $schedule->route->toLocation->name }}</span></p>
    </div>
    <a href="{{ route('conductor.passenger_list', $schedule->id) }}" class="bg-primary-maroon text-white px-4 py-2 rounded-lg font-bold hover:bg-dark-maroon transition flex items-center">
        <i data-lucide="list" class="w-4 h-4 mr-2"></i> Passenger List
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Seat Layout Area -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-6">
                    <div class="flex items-center"><div class="w-4 h-4 rounded bg-gray-100 border border-gray-200 mr-2"></div><span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Available</span></div>
                    <div class="flex items-center"><div class="w-4 h-4 rounded bg-blue-500 mr-2"></div><span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Booked (Pending)</span></div>
                    <div class="flex items-center"><div class="w-4 h-4 rounded bg-green-500 mr-2"></div><span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Boarded</span></div>
                    <div class="flex items-center"><div class="w-4 h-4 rounded bg-red-500 mr-2"></div><span class="text-xs text-gray-500 font-bold uppercase tracking-wider">No Show</span></div>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <i data-lucide="voicemail" class="w-6 h-6 text-gray-400 transform -rotate-90"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full">
                    @php
                        $layout = $schedule->bus->seat_layout ?? [];
                    @endphp

                    @if(empty($layout))
                        <div class="text-center p-8 text-gray-500">
                            No seat layout defined for this bus.
                        </div>
                    @else
                        <div class="flex flex-col gap-3 relative border-4 border-gray-200 rounded-3xl p-6 bg-gray-50 max-w-fit mx-auto">
                            <!-- Driver area -->
                            <div class="absolute top-6 right-6 w-10 h-10 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            
                            <div class="mt-12"></div>
                            
                            @foreach($layout as $rowIndex => $row)
                                <div class="flex gap-3 justify-center">
                                    @foreach($row as $colIndex => $seat)
                                        @if($seat)
                                            @php
                                                $seatLabel = $seat['label'];
                                                $status = $seatStatusMap[$seatLabel] ?? 'available';
                                                
                                                $bgClass = 'bg-white border-gray-300 text-gray-600';
                                                if ($status == 'pending') $bgClass = 'bg-blue-500 text-white border-blue-600 shadow-md transform -translate-y-1';
                                                if ($status == 'boarded') $bgClass = 'bg-green-500 text-white border-green-600 shadow-md transform -translate-y-1';
                                                if ($status == 'no_show') $bgClass = 'bg-red-500 text-white border-red-600 shadow-md transform -translate-y-1';
                                            @endphp
                                            <button 
                                                class="w-12 h-12 rounded-t-xl rounded-b-md border-2 flex items-center justify-center font-bold text-sm transition-all {{ $bgClass }}"
                                                onclick="showPassengerDetails('{{ $seatLabel }}', '{{ $status }}')"
                                            >
                                                {{ $seatLabel }}
                                            </button>
                                        @else
                                            <div class="w-12 h-12"></div> <!-- Aisle -->
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Details Sidebar Area -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
            <h3 class="font-bold text-lg text-dark-text mb-4 pb-4 border-b border-gray-100">Seat Details</h3>
            
            <div id="default-message" class="text-center py-8 text-gray-500">
                <i data-lucide="mouse-pointer-click" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                <p>Click on a booked seat to view passenger details.</p>
            </div>

            <div id="passenger-details" class="hidden">
                <div class="mb-4">
                    <span class="text-xs font-bold text-gray-500 uppercase">Seat Number</span>
                    <h2 id="detail-seat" class="text-3xl font-black text-dark-maroon">--</h2>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Passenger Name</span>
                        <p id="detail-name" class="font-bold text-dark-text text-lg">--</p>
                    </div>
                    
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Phone Number</span>
                        <p id="detail-phone" class="font-medium text-gray-600">--</p>
                    </div>
                    
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Boarding Point</span>
                        <p id="detail-boarding" class="font-medium text-gray-600">--</p>
                    </div>
                    
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Current Status</span>
                        <div id="detail-status-badge" class="mt-1 inline-block px-3 py-1 rounded-full text-xs font-bold">--</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const passengerData = {!! json_encode($seatPassengerMap) !!};
    
    function showPassengerDetails(seatLabel, status) {
        if (status === 'available') {
            document.getElementById('default-message').classList.remove('hidden');
            document.getElementById('passenger-details').classList.add('hidden');
            return;
        }

        const data = passengerData[seatLabel];
        if (data) {
            document.getElementById('default-message').classList.add('hidden');
            document.getElementById('passenger-details').classList.remove('hidden');
            
            document.getElementById('detail-seat').textContent = seatLabel;
            document.getElementById('detail-name').textContent = data.name;
            document.getElementById('detail-phone').textContent = data.phone;
            document.getElementById('detail-boarding').textContent = data.boarding_point;
            
            const badge = document.getElementById('detail-status-badge');
            if (status === 'pending') {
                badge.className = 'mt-1 inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700';
                badge.textContent = 'Booked (Pending)';
            } else if (status === 'boarded') {
                badge.className = 'mt-1 inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
                badge.textContent = 'Boarded';
            } else if (status === 'no_show') {
                badge.className = 'mt-1 inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
                badge.textContent = 'No Show';
            }
        }
    }
</script>

@endsection
