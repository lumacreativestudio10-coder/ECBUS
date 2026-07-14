@extends('layouts.app')

@section('content')

<!-- ================================================== -->
<!-- 3. HERO SECTION & 4. BUS SEARCH FORM -->
<!-- ================================================== -->
<section class="relative pt-24 pb-48 lg:pt-36 lg:pb-56 overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Luxury Bus" class="w-full h-full object-cover">
        <!-- Dark Maroon Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-dark-maroon/95 to-dark-maroon/70"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 animate-fade-in-up" style="animation: fadeInUp 0.8s ease-out;">
            TRAVEL SMARTER <br class="hidden md:block">
            WITH <span class="text-primary-gold">ECBUS</span>
        </h1>
        <p class="text-lg md:text-xl font-light opacity-90 max-w-2xl mx-auto animate-fade-in-up" style="animation: fadeInUp 1s ease-out;">
            Book Your Bus Seat Easily, Quickly & Securely.
        </p>
    </div>
</section>

<!-- Floating Search Form -->
<section class="relative z-20 -mt-32 max-w-6xl mx-auto px-6 mb-20">
    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100 animate-fade-in-up" style="animation: fadeInUp 1.2s ease-out;">
        <form x-data="bookingForm" @submit.prevent="validate" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
            
            <div class="lg:col-span-1">
                <label class="block text-sm font-bold text-dark-text mb-2 uppercase tracking-wide">From</label>
                <div class="relative">
                    <i data-lucide="map-pin" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                    <select x-model="from" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon appearance-none font-medium" :class="{'border-red-500': errors.from}">
                        <option value="">Select Departure</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Jaffna">Jaffna</option>
                    </select>
                    <p x-show="errors.from" class="text-red-500 text-xs mt-1 absolute" x-text="errors.from"></p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <label class="block text-sm font-bold text-dark-text mb-2 uppercase tracking-wide">To</label>
                <div class="relative">
                    <i data-lucide="map-pin" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                    <select x-model="to" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon appearance-none font-medium" :class="{'border-red-500': errors.to}">
                        <option value="">Select Destination</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Jaffna">Jaffna</option>
                    </select>
                    <p x-show="errors.to" class="text-red-500 text-xs mt-1 absolute" x-text="errors.to"></p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <label class="block text-sm font-bold text-dark-text mb-2 uppercase tracking-wide">Travel Date</label>
                <div class="relative">
                    <i data-lucide="calendar" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                    <input type="date" x-model="date" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon font-medium text-gray-700" :class="{'border-red-500': errors.date}">
                    <p x-show="errors.date" class="text-red-500 text-xs mt-1 absolute" x-text="errors.date"></p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <label class="block text-sm font-bold text-dark-text mb-2 uppercase tracking-wide">Passengers</label>
                <div class="relative">
                    <i data-lucide="users" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                    <select x-model="passengers" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon appearance-none font-medium">
                        <option value="1">1 Passenger</option>
                        <option value="2">2 Passengers</option>
                        <option value="3">3 Passengers</option>
                        <option value="4">4 Passengers</option>
                    </select>
                </div>
            </div>

            <div class="lg:col-span-1">
                <button type="submit" class="w-full bg-dark-maroon text-white font-bold py-3.5 rounded-xl hover:bg-primary-maroon transition duration-300 shadow-md flex justify-center items-center h-full min-h-[50px]">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i> SEARCH BUSES
                </button>
            </div>
        </form>

        <!-- Service Benefits -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10 pt-8 border-t border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="bg-primary-gold/20 p-3 rounded-full text-primary-gold">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-bold text-dark-text">Easy Booking</h4>
                    <p class="text-sm text-gray-500">Book in just a few clicks</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-primary-gold/20 p-3 rounded-full text-primary-gold">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-bold text-dark-text">Safe & Trusted</h4>
                    <p class="text-sm text-gray-500">Travel with confidence</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-primary-gold/20 p-3 rounded-full text-primary-gold">
                    <i data-lucide="headphones" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-bold text-dark-text">24/7 Support</h4>
                    <p class="text-sm text-gray-500">We are here to help</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================================== -->
<!-- 6. POPULAR ROUTES SECTION -->
<!-- ================================================== -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col items-center mb-16 text-center">
            <h2 class="text-sm font-bold text-primary-gold uppercase tracking-widest mb-2">Top Destinations</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-dark-text">POPULAR ROUTES</h3>
            <div class="w-24 h-1 bg-gradient-to-r from-primary-maroon to-primary-gold mt-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $routes = [
                    ['from' => 'Kalmune', 'to' => 'Colombo', 'price' => '2,500', 'img' => 'photo-1582293041079-7814c27aa606'],
                    ['from' => 'Jaffna', 'to' => 'Colombo', 'price' => '2,800', 'img' => 'photo-1625732128691-8bc4e3b7b9cb'],
                    ['from' => 'Colombo', 'to' => 'Jaffna', 'price' => '2,800', 'img' => 'photo-1544620347-c4fd4a3d5957'],
                    ['from' => 'Batticaloa', 'to' => 'Colombo', 'price' => '2,400', 'img' => 'photo-1588668214407-6ea9a6d8c272'],
                    ['from' => 'Trincomalee', 'to' => 'Colombo', 'price' => '2,600', 'img' => 'photo-1506461883276-594a12b11e61'],
                    ['from' => 'Kandy', 'to' => 'Colombo', 'price' => '2,000', 'img' => 'photo-1552465011-b4e21bf6e79a'],
                ];
            @endphp

            @foreach($routes as $route)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group cursor-pointer border border-gray-50 flex flex-col h-full">
                <div class="relative h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/{{ $route['img'] }}?auto=format&fit=crop&w=800&q=80" alt="{{ $route['to'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white font-bold text-lg flex items-center">
                        <i data-lucide="map-pin" class="w-5 h-5 mr-1 text-primary-gold"></i> {{ $route['to'] }}
                    </div>
                </div>
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex items-center justify-between text-dark-text font-bold text-xl mb-4">
                        <span>{{ $route['from'] }}</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 text-primary-gold"></i>
                        <span>{{ $route['to'] }}</span>
                    </div>
                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Starting From</p>
                            <p class="text-xl font-extrabold text-primary-maroon">LKR {{ $route['price'] }}</p>
                        </div>
                        <button class="bg-primary-maroon text-white px-5 py-2 rounded-lg font-bold text-sm hover:bg-dark-maroon transition shadow-md">VIEW BUSES</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ================================================== -->
<!-- 7. HOW IT WORKS SECTION -->
<!-- ================================================== -->
<section class="py-20 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-4">HOW IT WORKS</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Follow these 3 simple steps to book your journey.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-1 bg-gradient-to-r from-primary-gold to-primary-maroon opacity-30"></div>
            
            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="map" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                    <span class="absolute -top-3 -right-3 bg-dark-maroon text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">01</span>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">Choose Your Route</h4>
                <p class="text-gray-500">Select your departure, destination and date.</p>
            </div>

            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="armchair" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                    <span class="absolute -top-3 -right-3 bg-dark-maroon text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">02</span>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">Select Your Seat</h4>
                <p class="text-gray-500">Choose your preferred bus and seat easily.</p>
            </div>

            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="check-circle-2" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                    <span class="absolute -top-3 -right-3 bg-dark-maroon text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">03</span>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">Confirm & Book</h4>
                <p class="text-gray-500">Enter your details and confirm your booking.</p>
            </div>
        </div>
    </div>
</section>

<!-- ================================================== -->
<!-- 8. FEATURED BUSES SECTION -->
<!-- ================================================== -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-2">FEATURED BUSES</h2>
                <p class="text-gray-600">Travel in our most comfortable and premium fleet.</p>
            </div>
            <a href="#" class="hidden md:flex items-center text-primary-maroon font-bold hover:text-primary-gold transition">
                View All <i data-lucide="arrow-right" class="w-5 h-5 ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Bus 1 -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-extrabold text-dark-text">EC Express</h3>
                        <p class="text-sm text-gray-500">Luxury AC</p>
                    </div>
                    <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                        <i data-lucide="users" class="w-3 h-3 mr-1"></i> 12 Seats Available
                    </div>
                </div>
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-center font-bold text-lg text-dark-text mb-6">
                        <span>Kalmune</span>
                        <div class="flex-grow mx-4 relative flex items-center justify-center">
                            <div class="w-full h-px bg-gray-300"></div>
                            <i data-lucide="bus" class="absolute w-6 h-6 text-primary-gold bg-white px-1"></i>
                        </div>
                        <span>Colombo</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center mb-6">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Departure</p>
                            <p class="font-bold text-dark-text">08:00 PM</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Duration</p>
                            <p class="font-bold text-primary-maroon">9 Hours</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Arrival</p>
                            <p class="font-bold text-dark-text">05:00 AM</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Price</p>
                        <p class="text-2xl font-extrabold text-dark-maroon">LKR 2,500</p>
                    </div>
                    <button class="bg-primary-gold text-dark-text px-6 py-2.5 rounded-lg font-bold hover:bg-yellow-500 transition shadow-md">VIEW SEATS</button>
                </div>
            </div>

            <!-- Bus 2 -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-extrabold text-dark-text">Royal Line</h3>
                        <p class="text-sm text-gray-500">Super Luxury</p>
                    </div>
                    <div class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                        <i data-lucide="users" class="w-3 h-3 mr-1"></i> 8 Seats Available
                    </div>
                </div>
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-center font-bold text-lg text-dark-text mb-6">
                        <span>Jaffna</span>
                        <div class="flex-grow mx-4 relative flex items-center justify-center">
                            <div class="w-full h-px bg-gray-300"></div>
                            <i data-lucide="bus" class="absolute w-6 h-6 text-primary-gold bg-white px-1"></i>
                        </div>
                        <span>Colombo</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center mb-6">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Departure</p>
                            <p class="font-bold text-dark-text">07:30 PM</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Duration</p>
                            <p class="font-bold text-primary-maroon">9 Hours</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Arrival</p>
                            <p class="font-bold text-dark-text">04:30 AM</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Price</p>
                        <p class="text-2xl font-extrabold text-dark-maroon">LKR 2,800</p>
                    </div>
                    <button class="bg-primary-gold text-dark-text px-6 py-2.5 rounded-lg font-bold hover:bg-yellow-500 transition shadow-md">VIEW SEATS</button>
                </div>
            </div>

            <!-- Bus 3 -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-extrabold text-dark-text">Super Line</h3>
                        <p class="text-sm text-gray-500">Luxury Non AC</p>
                    </div>
                    <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                        <i data-lucide="users" class="w-3 h-3 mr-1"></i> 15 Seats Available
                    </div>
                </div>
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-center font-bold text-lg text-dark-text mb-6">
                        <span>Batticaloa</span>
                        <div class="flex-grow mx-4 relative flex items-center justify-center">
                            <div class="w-full h-px bg-gray-300"></div>
                            <i data-lucide="bus" class="absolute w-6 h-6 text-primary-gold bg-white px-1"></i>
                        </div>
                        <span>Colombo</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center mb-6">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Departure</p>
                            <p class="font-bold text-dark-text">06:30 PM</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Duration</p>
                            <p class="font-bold text-primary-maroon">9 Hours</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 uppercase">Arrival</p>
                            <p class="font-bold text-dark-text">03:30 AM</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Price</p>
                        <p class="text-2xl font-extrabold text-dark-maroon">LKR 2,400</p>
                    </div>
                    <button class="bg-primary-gold text-dark-text px-6 py-2.5 rounded-lg font-bold hover:bg-yellow-500 transition shadow-md">VIEW SEATS</button>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center md:hidden">
            <a href="#" class="inline-flex items-center text-primary-maroon font-bold hover:text-primary-gold transition">
                View All Buses <i data-lucide="arrow-right" class="w-5 h-5 ml-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ================================================== -->
<!-- 9. OFFICIAL BUS PARTNERS -->
<!-- ================================================== -->
<section class="py-16 bg-white border-y border-gray-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-extrabold text-dark-text text-center mb-10">OUR OFFICIAL BUS PARTNERS</h2>
        
        <!-- Swiper for Partners -->
        <div class="swiper partner-swiper">
            <div class="swiper-wrapper flex items-center">
                @php
                    $partners = ['EC Express', 'Royal Line', 'Super Line', 'Speed Line', 'National Travels'];
                @endphp
                @foreach($partners as $partner)
                <div class="swiper-slide">
                    <div class="flex flex-col items-center justify-center p-6 grayscale hover:grayscale-0 transition duration-300 opacity-60 hover:opacity-100 cursor-pointer">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i data-lucide="bus" class="w-8 h-8 text-dark-maroon"></i>
                        </div>
                        <h4 class="font-bold text-dark-text text-center">{{ $partner }}</h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ================================================== -->
<!-- 10. CUSTOMER REVIEWS -->
<!-- ================================================== -->
<section class="py-24 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-4">WHAT OUR PASSENGERS SAY</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-primary-maroon to-primary-gold mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="swiper review-swiper pb-12">
            <div class="swiper-wrapper">
                <!-- Review 1 -->
                <div class="swiper-slide h-auto">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-full flex flex-col relative">
                        <i data-lucide="quote" class="absolute top-6 right-6 w-12 h-12 text-gray-100"></i>
                        <div class="flex text-yellow-400 mb-4">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                        <p class="text-gray-600 italic mb-8 flex-grow relative z-10">
                            "Excellent service! The bus was very clean, comfortable, and left exactly on time. Booking through ECBUS was seamless and saved me a lot of hassle at the bus stand."
                        </p>
                        <div class="flex items-center">
                            <img src="https://i.pravatar.cc/100?img=1" alt="Kamal" class="w-12 h-12 rounded-full mr-4 border-2 border-primary-gold">
                            <div>
                                <h4 class="font-bold text-dark-text">Kamal Perera</h4>
                                <p class="text-xs text-gray-500 font-semibold">Colombo → Jaffna</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="swiper-slide h-auto">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-full flex flex-col relative">
                        <i data-lucide="quote" class="absolute top-6 right-6 w-12 h-12 text-gray-100"></i>
                        <div class="flex text-yellow-400 mb-4">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                        <p class="text-gray-600 italic mb-8 flex-grow relative z-10">
                            "I travel to Kandy every weekend. ECBUS makes my life so much easier. The UI is great, payment is smooth, and I get my e-ticket on WhatsApp instantly."
                        </p>
                        <div class="flex items-center">
                            <img src="https://i.pravatar.cc/100?img=5" alt="Nimeshi" class="w-12 h-12 rounded-full mr-4 border-2 border-primary-gold">
                            <div>
                                <h4 class="font-bold text-dark-text">Nimeshi Fernando</h4>
                                <p class="text-xs text-gray-500 font-semibold">Colombo → Kandy</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="swiper-slide h-auto">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-full flex flex-col relative">
                        <i data-lucide="quote" class="absolute top-6 right-6 w-12 h-12 text-gray-100"></i>
                        <div class="flex text-yellow-400 mb-4">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5"></i>
                        </div>
                        <p class="text-gray-600 italic mb-8 flex-grow relative z-10">
                            "Very professional. The driver was safe, and the AC was perfect for the long journey to Batticaloa. Will definitely book again next month!"
                        </p>
                        <div class="flex items-center">
                            <img src="https://i.pravatar.cc/100?img=11" alt="Mohammed" class="w-12 h-12 rounded-full mr-4 border-2 border-primary-gold">
                            <div>
                                <h4 class="font-bold text-dark-text">Mohammed Fazil</h4>
                                <p class="text-xs text-gray-500 font-semibold">Colombo → Batticaloa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination mt-8 relative"></div>
        </div>
    </div>
</section>

<!-- ================================================== -->
<!-- 11. CALL TO ACTION SECTION -->
<!-- ================================================== -->
<section class="py-20 relative overflow-hidden bg-dark-maroon">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="bg-gradient-to-r from-primary-maroon to-dark-maroon rounded-3xl p-10 md:p-16 shadow-2xl border border-white/10 flex flex-col md:flex-row items-center justify-between">
            <div class="text-white mb-8 md:mb-0 md:mr-8 text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4 text-primary-gold">READY TO START YOUR JOURNEY?</h2>
                <p class="text-lg opacity-90 max-w-xl">Book your seat now and travel with ease, comfort, and security.</p>
            </div>
            <div>
                <a href="#" class="inline-block bg-primary-gold text-dark-maroon px-8 py-4 rounded-xl font-extrabold text-lg hover:bg-white hover:text-dark-maroon transition duration-300 shadow-xl whitespace-nowrap transform hover:-translate-y-1">
                    BOOK YOUR SEAT NOW
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .swiper-pagination-bullet {
        background: #D4AF37;
        opacity: 0.5;
    }
    .swiper-pagination-bullet-active {
        opacity: 1;
        background: #800000;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Partner Swiper
        const partnerSwiper = new Swiper('.partner-swiper', {
            slidesPerView: 2,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 30 },
                768: { slidesPerView: 4, spaceBetween: 40 },
                1024: { slidesPerView: 5, spaceBetween: 50 },
            }
        });

        // Initialize Review Swiper
        const reviewSwiper = new Swiper('.review-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: true,
            },
            breakpoints: {
                768: { slidesPerView: 2, spaceBetween: 30 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            }
        });
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingForm', () => ({
            from: '',
            to: '',
            date: '',
            passengers: '1',
            errors: {},

            validate() {
                this.errors = {};
                let isValid = true;

                if (!this.from) {
                    this.errors.from = 'Departure is required.';
                    isValid = false;
                }
                
                if (!this.to) {
                    this.errors.to = 'Destination is required.';
                    isValid = false;
                }
                
                if (this.from && this.to && this.from === this.to) {
                    this.errors.to = 'Destination must be different.';
                    isValid = false;
                }

                if (!this.date) {
                    this.errors.date = 'Travel date is required.';
                    isValid = false;
                } else {
                    const selectedDate = new Date(this.date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    if (selectedDate < today) {
                        this.errors.date = 'Cannot select past date.';
                        isValid = false;
                    }
                }

                if (isValid) {
                    // Navigate to routes page with query params
                    const searchParams = new URLSearchParams({
                        from: this.from,
                        to: this.to,
                        date: this.date,
                        passengers: this.passengers
                    });
                    window.location.href = `{{ route('routes') }}?${searchParams.toString()}`;
                }
            }
        }));
    });
</script>
@endpush
