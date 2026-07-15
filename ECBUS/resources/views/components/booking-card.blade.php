@props(['booking'])

<div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row relative">
    <!-- Decorative cutouts for ticket look -->
    <div class="hidden md:block absolute -left-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-cream rounded-full border-r border-gray-100"></div>
    <div class="hidden md:block absolute -right-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-cream rounded-full border-l border-gray-100"></div>

    <!-- Left section (Details) -->
    <div class="p-8 md:p-10 w-full md:w-3/4 flex flex-col justify-between relative border-b md:border-b-0 border-dashed border-gray-200">
        <div class="flex justify-between items-start mb-8">
            <div>
                <span class="{{ $booking->status === 'Confirmed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-3 py-1 rounded-full text-xs font-bold flex items-center mb-4 inline-flex">
                    <i data-lucide="{{ $booking->status === 'Confirmed' ? 'check-circle' : 'x-circle' }}" class="w-3 h-3 mr-1"></i> {{ strtoupper($booking->status) }}
                </span>
                <h3 class="text-2xl font-extrabold text-dark-text">
                    {{ $booking->schedule->route->fromLocation->name ?? 'Unknown' }} to {{ $booking->schedule->route->toLocation->name ?? 'Unknown' }}
                </h3>
                <p class="text-gray-500 mt-1 font-medium">Booking ID: <span class="text-dark-text font-bold">ECB-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-gray-500 mt-1 text-sm font-medium">Passenger: <span class="text-dark-text">{{ $booking->passenger_name }} ({{ $booking->passenger_count }} Seats)</span></p>
            </div>
            <div class="text-right">
                <img src="{{ asset('image/logo.png') }}" alt="ECBUS" class="h-8 mb-2 ml-auto opacity-50">
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Date</p>
                <p class="font-extrabold text-dark-text text-lg">{{ \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Time</p>
                <p class="font-extrabold text-dark-text text-lg">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Seat(s)</p>
                <p class="font-extrabold text-primary-maroon text-lg">
                    {{ is_array($booking->seat_numbers) && count($booking->seat_numbers) > 0 ? implode(', ', $booking->seat_numbers) : 'TBD' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Bus Type</p>
                <p class="font-bold text-dark-text">{{ $booking->schedule->bus->busType->name ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-500">{{ $booking->schedule->bus->operator->name ?? 'Unknown' }}</p>
            </div>
        </div>
    </div>

    <!-- Right section (Action/QR) -->
    <div class="p-8 md:p-10 w-full md:w-1/4 bg-gray-50 flex flex-col items-center justify-center relative md:border-l border-dashed border-gray-200">
        <div class="text-center mb-6 w-full border-b border-gray-200 pb-6 md:border-none md:pb-0">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Total Paid</p>
            <h4 class="text-3xl font-extrabold text-dark-maroon">LKR {{ number_format($booking->total_amount, 0) }}</h4>
        </div>
        
        <button class="w-full bg-primary-gold text-dark-maroon py-3 px-4 rounded-xl font-bold shadow-md hover:bg-yellow-500 transition flex items-center justify-center mb-3">
            <i data-lucide="download" class="w-5 h-5 mr-2"></i> E-Ticket
        </button>
        @if($booking->status === 'Confirmed')
        <form action="#" method="POST" class="w-full" onsubmit="return confirm('Contact admin to cancel ticket?');">
            @csrf
            <button class="w-full text-red-500 hover:text-red-700 py-2 px-4 rounded-xl font-bold text-sm transition flex items-center justify-center border border-transparent hover:border-red-200">
                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Cancel Ticket
            </button>
        </form>
        @endif
    </div>
</div>
