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

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-8 overflow-x-auto whitespace-nowrap">
            <button @click="activeTab = 'upcoming'" 
                    :class="activeTab === 'upcoming' ? 'border-primary-maroon text-primary-maroon font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="pb-4 px-6 border-b-4 transition duration-300 outline-none flex items-center">
                <i data-lucide="calendar-clock" class="w-5 h-5 mr-2" :class="activeTab === 'upcoming' ? 'opacity-100' : 'opacity-50'"></i>
                Upcoming Journeys
            </button>
            <button @click="activeTab = 'past'" 
                    :class="activeTab === 'past' ? 'border-primary-maroon text-primary-maroon font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="pb-4 px-6 border-b-4 transition duration-300 outline-none flex items-center">
                <i data-lucide="history" class="w-5 h-5 mr-2" :class="activeTab === 'past' ? 'opacity-100' : 'opacity-50'"></i>
                Past Bookings
            </button>
        </div>

        <!-- Upcoming Tab Content -->
        <div x-show="activeTab === 'upcoming'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
            
            <!-- Ticket 1 -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row relative">
                <!-- Decorative cutouts for ticket look -->
                <div class="hidden md:block absolute -left-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-cream rounded-full border-r border-gray-100"></div>
                <div class="hidden md:block absolute -right-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-cream rounded-full border-l border-gray-100"></div>

                <!-- Left section (Details) -->
                <div class="p-8 md:p-10 w-full md:w-3/4 flex flex-col justify-between relative border-b md:border-b-0 border-dashed border-gray-200">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center mb-4 inline-flex">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> CONFIRMED
                            </span>
                            <h3 class="text-2xl font-extrabold text-dark-text">Colombo to Kandy</h3>
                            <p class="text-gray-500 mt-1 font-medium">Booking ID: <span class="text-dark-text font-bold">ECB-789214</span></p>
                        </div>
                        <div class="text-right">
                            <img src="{{ asset('image/logo.png') }}" alt="ECBUS" class="h-8 mb-2 ml-auto opacity-50">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Date</p>
                            <p class="font-extrabold text-dark-text text-lg">24 July 2026</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Time</p>
                            <p class="font-extrabold text-dark-text text-lg">08:00 AM</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Seat(s)</p>
                            <p class="font-extrabold text-primary-maroon text-lg">A1, A2</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Bus Type</p>
                            <p class="font-bold text-dark-text">Super Luxury</p>
                            <p class="text-xs text-gray-500">EC Express</p>
                        </div>
                    </div>
                </div>

                <!-- Right section (Action/QR) -->
                <div class="p-8 md:p-10 w-full md:w-1/4 bg-gray-50 flex flex-col items-center justify-center relative md:border-l border-dashed border-gray-200">
                    <div class="text-center mb-6 w-full border-b border-gray-200 pb-6 md:border-none md:pb-0">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Total Paid</p>
                        <h4 class="text-3xl font-extrabold text-dark-maroon">LKR 4,000</h4>
                    </div>
                    
                    <button class="w-full bg-primary-gold text-dark-maroon py-3 px-4 rounded-xl font-bold shadow-md hover:bg-yellow-500 transition flex items-center justify-center mb-3">
                        <i data-lucide="download" class="w-5 h-5 mr-2"></i> E-Ticket
                    </button>
                    <button class="w-full text-red-500 hover:text-red-700 py-2 px-4 rounded-xl font-bold text-sm transition flex items-center justify-center border border-transparent hover:border-red-200">
                        <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Cancel Ticket
                    </button>
                </div>
            </div>

        </div>

        <!-- Past Bookings Tab Content -->
        <div x-show="activeTab === 'past'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
            
            <!-- Past Ticket 1 -->
            <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 flex flex-col md:flex-row relative opacity-70 hover:opacity-100 transition duration-300">
                <div class="p-6 md:p-8 w-full flex flex-col justify-between">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                        <div class="flex items-center">
                            <div class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold mr-4 inline-flex items-center">
                                <i data-lucide="check" class="w-3 h-3 mr-1"></i> COMPLETED
                            </div>
                            <span class="text-gray-400 text-sm font-semibold">ECB-541299</span>
                        </div>
                        <span class="text-gray-400 font-bold">12 June 2026</span>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-xl font-extrabold text-dark-text">Jaffna to Colombo</h3>
                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                <span>Royal Line (Luxury)</span>
                                <span class="mx-2">•</span>
                                <span>Seat B4</span>
                            </div>
                        </div>
                        <button class="text-primary-maroon font-bold text-sm hover:text-primary-gold flex items-center transition bg-gray-50 px-4 py-2 rounded-lg border border-gray-100 hover:border-primary-gold">
                            <i data-lucide="star" class="w-4 h-4 mr-2"></i> Rate Journey
                        </button>
                    </div>
                </div>
            </div>

            <!-- Past Ticket 2 -->
            <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 flex flex-col md:flex-row relative opacity-70 hover:opacity-100 transition duration-300">
                <div class="p-6 md:p-8 w-full flex flex-col justify-between">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                        <div class="flex items-center">
                            <div class="bg-red-50 text-red-500 px-3 py-1 rounded-full text-xs font-bold mr-4 inline-flex items-center">
                                <i data-lucide="x" class="w-3 h-3 mr-1"></i> CANCELLED
                            </div>
                            <span class="text-gray-400 text-sm font-semibold">ECB-400123</span>
                        </div>
                        <span class="text-gray-400 font-bold">05 May 2026</span>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-xl font-extrabold text-dark-text line-through opacity-70">Colombo to Batticaloa</h3>
                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                <span>Super Line (Non A/C)</span>
                                <span class="mx-2">•</span>
                                <span>Seat C12</span>
                            </div>
                        </div>
                        <span class="font-bold text-red-500">Refunded LKR 2,400</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
