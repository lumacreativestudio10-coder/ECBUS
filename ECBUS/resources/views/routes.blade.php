@extends('layouts.app')

@section('title', 'Search Results - ECBUS')

@section('content')
<div x-data="searchController()" class="bg-gray-50 min-h-screen pb-20">
    
    <!-- Search Summary Header -->
    <div class="bg-dark-maroon text-white pt-24 pb-8 shadow-md">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-wrap justify-between md:justify-around items-center bg-white/10 p-6 rounded-2xl backdrop-blur-md border border-white/20 gap-4 md:gap-0">
                <div class="text-center md:text-left flex-shrink-0">
                    <p class="text-xs uppercase tracking-wider text-gray-300 font-semibold mb-1">From</p>
                    <h2 class="text-xl md:text-2xl font-bold" x-text="params.from || 'Colombo'"></h2>
                </div>
                
                <div class="bg-primary-gold/20 p-2 md:p-3 rounded-full text-primary-gold flex-shrink-0">
                    <i data-lucide="arrow-right-left" class="w-5 h-5 md:w-6 md:h-6"></i>
                </div>
                
                <div class="text-center md:text-left flex-shrink-0">
                    <p class="text-xs uppercase tracking-wider text-gray-300 font-semibold mb-1">To</p>
                    <h2 class="text-xl md:text-2xl font-bold" x-text="params.to || 'Kandy'"></h2>
                </div>
                
                <div class="hidden md:block w-px h-12 bg-white/20 mx-2"></div>
                
                <div class="flex items-center flex-shrink-0">
                    <i data-lucide="calendar" class="w-5 h-5 text-primary-gold mr-2 md:mr-3"></i>
                    <span class="font-medium text-sm md:text-base" x-text="formatDate(params.date) || '24 July 2026'"></span>
                </div>
                
                <div class="flex items-center flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5 text-primary-gold mr-2 md:mr-3"></i>
                    <span class="font-medium text-sm md:text-base" x-text="(params.passengers || '1') + ' Passenger(s)'"></span>
                </div>
                
                <a href="{{ route('home') }}" class="bg-white text-dark-maroon px-5 py-2.5 rounded-lg font-bold hover:bg-primary-gold hover:text-white transition shadow-sm text-sm flex-shrink-0 mt-4 md:mt-0 w-full md:w-auto text-center">
                    MODIFY SEARCH
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 pt-10">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="font-extrabold text-lg text-dark-text flex items-center">
                            <i data-lucide="sliders-horizontal" class="w-5 h-5 mr-2 text-primary-maroon"></i> Filters
                        </h3>
                        <button class="text-sm text-primary-maroon font-bold hover:text-dark-maroon">Clear All</button>
                    </div>

                    <!-- Travel Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-dark-text mb-2 uppercase tracking-wide">Travel Date</label>
                        <div class="relative">
                            <i data-lucide="calendar" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                            <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon font-medium text-gray-700 outline-none">
                        </div>
                    </div>
                    
                    <!-- Passengers -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-dark-text mb-2 uppercase tracking-wide">Passengers</label>
                        <div class="relative">
                            <i data-lucide="users" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                            <input type="number" name="passengers" value="{{ $passengers ?? 1 }}" min="1" max="4" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon font-medium text-gray-700 outline-none">
                        </div>
                    </div>

                    <!-- Departure Time -->
                    <div class="mb-8">
                        <h4 class="font-bold text-dark-text mb-4 text-sm uppercase tracking-wide">Departure Time</h4>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text flex items-center">
                                    <i data-lucide="sunrise" class="w-4 h-4 mr-2 text-gray-400"></i> Morning (06:00 - 12:00)
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text flex items-center">
                                    <i data-lucide="sun" class="w-4 h-4 mr-2 text-gray-400"></i> Afternoon (12:00 - 18:00)
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text flex items-center">
                                    <i data-lucide="moon" class="w-4 h-4 mr-2 text-gray-400"></i> Night (18:00 - 06:00)
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Bus Type -->
                    <div class="mb-8">
                        <h4 class="font-bold text-dark-text mb-4 text-sm uppercase tracking-wide">Bus Type</h4>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text">Super Luxury (A/C)</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text">Luxury (A/C)</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text">Semi-Luxury (Non A/C)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Boarding Points -->
                    <div>
                        <h4 class="font-bold text-dark-text mb-4 text-sm uppercase tracking-wide">Boarding Points</h4>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text">Pettah Bus Stand</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text">Bambalapitiya</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon">
                                <span class="ml-3 text-gray-600 group-hover:text-dark-text">Wellawatte</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bus Listing Results -->
            <div class="w-full lg:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold text-dark-text">
                        <span class="text-primary-maroon">{{ $schedules->count() ?? 0 }} Buses</span> found
                    </h3>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 font-bold mr-3">Sort By:</span>
                        <select class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium focus:ring-primary-maroon focus:border-primary-maroon outline-none">
                            <option>Price (Low to High)</option>
                            <option>Price (High to Low)</option>
                            <option>Departure (Earliest)</option>
                            <option>Duration (Shortest)</option>
                        </select>
                    </div>
                </div>

                <!-- Bus Cards List -->
                <div class="space-y-6">
                    @forelse ($schedules as $schedule)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition duration-300">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                
                                <!-- Operator Info -->
                                <div class="flex items-center mb-6 md:mb-0 w-full md:w-1/4">
                                    <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center mr-4 flex-shrink-0">
                                        <i data-lucide="{{ $schedule->bus->busCompany->logo ?? 'bus-front' }}" class="w-8 h-8 text-primary-maroon"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-lg text-dark-text">{{ $schedule->bus->busCompany->company_name }}</h4>
                                        <span class="inline-block bg-primary-gold/20 text-dark-maroon text-xs px-2 py-0.5 rounded font-bold mt-1">{{ $schedule->bus->type }}</span>
                                    </div>
                                </div>
                                
                                <!-- Journey Time -->
                                <div class="flex items-center justify-between w-full md:w-2/5 px-2 mb-6 md:mb-0">
                                    <div class="text-center">
                                        <h5 class="text-xl font-extrabold text-dark-text">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</h5>
                                        <p class="text-xs font-semibold text-gray-500">{{ $schedule->route->fromLocation->name ?? '?' }}</p>
                                    </div>
                                    <div class="flex-grow mx-4 relative flex flex-col items-center">
                                        @php
                                            $departure = \Carbon\Carbon::parse($schedule->departure_time);
                                            $arrival = \Carbon\Carbon::parse($schedule->arrival_time);
                                            if($arrival < $departure) $arrival->addDay();
                                            $duration = $departure->diff($arrival);
                                        @endphp
                                        <span class="text-xs text-gray-400 font-bold mb-1">{{ $duration->format('%Hh %Im') }}</span>
                                        <div class="w-full h-px bg-gray-300 relative">
                                            <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-primary-gold"></div>
                                            <div class="absolute -top-1 -left-1 w-2 h-2 rounded-full border border-gray-300 bg-white"></div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h5 class="text-xl font-extrabold text-dark-text">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</h5>
                                        <p class="text-xs font-semibold text-gray-500">{{ $schedule->route->toLocation->name ?? '?' }}</p>
                                        @if($arrival > $departure->copy()->endOfDay())
                                            <p class="text-xs font-semibold text-gray-500 text-red-500">Next Day</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Amenities (Dummy for now) -->
                                <div class="hidden md:flex w-1/5 justify-center space-x-3 text-gray-400">
                                    <div class="relative group cursor-pointer hover:text-primary-maroon transition"><i data-lucide="wifi" class="w-5 h-5"></i></div>
                                    <div class="relative group cursor-pointer hover:text-primary-maroon transition"><i data-lucide="snowflake" class="w-5 h-5"></i></div>
                                </div>

                                <!-- Price & Action -->
                                <div class="w-full md:w-auto flex md:flex-col justify-between items-center md:items-end border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 mt-4 md:mt-0">
                                    <div class="text-left md:text-right mb-0 md:mb-3">
                                        <h4 class="text-2xl font-extrabold text-dark-maroon">LKR {{ number_format($schedule->price, 0) }}</h4>
                                        @php
                                            $availableSeats = max(0, $schedule->bus->total_seats - ($schedule->booked_seats ?? 0));
                                            $locs = [];
                                            if($schedule->route->fromLocation) $locs[] = $schedule->route->fromLocation->name;
                                            if($schedule->route->stops) {
                                                foreach($schedule->route->stops as $stop) {
                                                    $locs[] = $stop->stop_name;
                                                }
                                            }
                                            if($schedule->route->toLocation) $locs[] = $schedule->route->toLocation->name;
                                        @endphp
                                        <p class="text-xs font-semibold {{ $availableSeats > 5 ? 'text-green-600' : 'text-red-600' }} mt-1">{{ $availableSeats }} Seats Available</p>
                                    </div>
                                    <button @click="openBookingModal('{{ addslashes($schedule->bus->busCompany->company_name) }}', {{ $schedule->price }}, {{ $schedule->id }}, {{ json_encode($locs) }})" class="bg-primary-maroon text-white px-6 py-2.5 rounded-lg font-bold hover:bg-dark-maroon transition shadow-md whitespace-nowrap">
                                        BOOK NOW
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center col-span-full">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="bus" class="w-10 h-10 text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-dark-text mb-2">No Buses Found</h3>
                            <p class="text-gray-500">We couldn't find any buses for this route on the selected date.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center mt-10">
                    <div class="flex space-x-2">
                        <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-gray-200 bg-white text-gray-500 hover:bg-gray-50">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-primary-maroon text-white font-bold shadow-md">1</button>
                        <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-gray-200 bg-white text-dark-text font-bold hover:bg-gray-50">2</button>
                        <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-gray-200 bg-white text-dark-text font-bold hover:bg-gray-50">3</button>
                        <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-gray-200 bg-white text-gray-500 hover:bg-gray-50">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <div x-show="isModalOpen" x-transition.opacity.duration.300ms @click="closeModal()" class="fixed inset-0 bg-dark-maroon/80 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div x-show="isModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
            
            <!-- Header -->
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-10">
                <h3 class="font-extrabold text-xl text-dark-text">Complete Booking</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-red-500 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Success State -->
            <div x-show="isSuccess" class="p-12 flex flex-col items-center justify-center text-center h-full">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-green-500 mb-6 animate-bounce">
                    <i data-lucide="check-circle" class="w-10 h-10"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-dark-text mb-2">Booking Submitted!</h2>
                <p class="text-gray-500 mb-6">Your payment is being verified. Redirecting to your dashboard...</p>
                <div class="w-16 h-1 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 animate-pulse"></div>
                </div>
            </div>

            <!-- Form State -->
            <div x-show="!isSuccess" class="overflow-y-auto p-6 flex-grow">
                
                <!-- Bus Summary -->
                <div class="bg-primary-maroon/5 border border-primary-maroon/20 rounded-2xl p-4 mb-6 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-primary-maroon font-bold uppercase tracking-wider mb-1">Selected Bus</p>
                        <h4 class="font-extrabold text-lg text-dark-maroon" x-text="selectedBus.name"></h4>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 font-bold mb-1">Price per seat</p>
                        <h4 class="font-extrabold text-dark-text" x-text="'LKR ' + selectedBus.price.toLocaleString()"></h4>
                    </div>
                </div>

                <form @submit.prevent="submitBooking" class="space-y-5">
                    
                    <!-- Passenger Details -->
                    <div>
                        <label class="block text-sm font-bold text-dark-text mb-1">Full Name</label>
                        <input type="text" x-model="booking.name" required placeholder="John Doe" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon outline-none transition">
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="w-2/3">
                            <label class="block text-sm font-bold text-dark-text mb-1">Phone Number</label>
                            <input type="tel" x-model="booking.phone" required placeholder="077 123 4567" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon outline-none transition">
                        </div>
                        <div class="w-1/3">
                            <label class="block text-sm font-bold text-dark-text mb-1">Passengers</label>
                            <input type="number" x-model.number="booking.count" required min="1" max="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon outline-none transition text-center font-bold">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-dark-text mb-1">Boarding Point</label>
                            <select x-model="booking.boarding_point" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon outline-none transition">
                                <option value="" disabled>Select boarding point</option>
                                <template x-for="loc in locations">
                                    <option :value="loc" x-text="loc"></option>
                                </template>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-dark-text mb-1">Dropping Point</label>
                            <select x-model="booking.dropping_point" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon outline-none transition">
                                <option value="" disabled>Select dropping point</option>
                                <template x-for="loc in locations">
                                    <option :value="loc" x-text="loc"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <button type="submit" :disabled="isSubmitting" class="w-full bg-primary-gold text-dark-text font-bold py-4 rounded-xl shadow-md hover:bg-yellow-500 transition mt-6 flex justify-center items-center">
                        <span x-show="!isSubmitting">SUBMIT ENQUIRY</span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <i data-lucide="loader-2" class="w-5 h-5 mr-2 animate-spin"></i> Processing...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchController', () => ({
            params: {},
            isModalOpen: false,
            isSubmitting: false,
            isSuccess: false,
            locations: [],
            selectedBus: { name: '', price: 0 },
            booking: { name: '', phone: '', count: 1, boarding_point: '', dropping_point: '' },
            
            init() {
                const urlParams = new URLSearchParams(window.location.search);
                this.params = {
                    from: urlParams.get('from'),
                    to: urlParams.get('to'),
                    date: urlParams.get('date'),
                    passengers: urlParams.get('passengers') || 1
                };
                this.booking.count = this.params.passengers;
            },
            
            formatDate(dateString) {
                if(!dateString) return null;
                const options = { day: 'numeric', month: 'short', year: 'numeric' };
                return new Date(dateString).toLocaleDateString('en-GB', options);
            },

            openBookingModal(busName, price, scheduleId, locsArray) {
                this.selectedBus = { name: busName, price: price, schedule_id: scheduleId };
                this.locations = locsArray || [];
                this.booking.count = {{ $passengers ?? 1 }};
                this.booking.name = '';
                this.booking.phone = '';
                this.booking.boarding_point = '';
                this.booking.dropping_point = '';
                this.isModalOpen = true;
                this.isSuccess = false;
                setTimeout(() => lucide.createIcons(), 50);
            },

            closeModal() {
                this.isModalOpen = false;
                setTimeout(() => {
                    this.isSubmitting = false;
                    this.isSuccess = false;
                    this.booking = { name: '', phone: '', count: this.params.passengers, boarding_point: '', dropping_point: '' };
                }, 300);
            },

            async submitBooking() {
                this.isSubmitting = true;
                
                try {
                    const response = await fetch("{{ route('booking.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            schedule_id: this.selectedBus.schedule_id,
                            customer_name: this.booking.name,
                            phone: this.booking.phone,
                            passenger_count: this.booking.count,
                            boarding_point: this.booking.boarding_point,
                            dropping_point: this.booking.dropping_point
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.isSubmitting = false;
                        this.isSuccess = true;
                        
                        // Redirect to My Bookings page with phone number
                        setTimeout(() => {
                            window.location.href = "{{ route('booking') }}?phone=" + encodeURIComponent(this.booking.phone);
                        }, 2000);
                    } else {
                        alert(data.message || 'Something went wrong.');
                        this.isSubmitting = false;
                    }
                } catch (error) {
                    console.error("Error submitting booking:", error);
                    alert("Error submitting booking.");
                    this.isSubmitting = false;
                }
            }
        }));
    });
</script>
@endpush
