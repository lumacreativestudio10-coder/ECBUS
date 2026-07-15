@extends('layouts.app')

@section('title', 'Bus Partners - ECBUS')

@section('content')

<!-- Hero Section -->
<div class="pt-32 pb-16 bg-gradient-to-b from-gray-50 to-white text-center px-6">
    <div class="max-w-3xl mx-auto animate-fade-in-up">
        <div class="inline-flex items-center space-x-2 bg-primary-gold/20 text-dark-maroon px-4 py-2 rounded-full font-bold text-sm mb-6">
            <i data-lucide="handshake" class="w-4 h-4"></i>
            <span>Our Trusted Network</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-dark-text mb-6 leading-tight">
            Our Official <span class="text-primary-maroon">Bus Partners</span>
        </h1>
        <p class="text-gray-600 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto">
            We proudly work with trusted transport partners to provide safe, comfortable and reliable journeys across Sri Lanka.
        </p>
    </div>
</div>

<!-- Partner Cards Section -->
<div class="py-12 bg-white px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($partners as $partner)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="p-8 text-center border-b border-gray-50 bg-gradient-to-b from-gray-50/50 to-transparent">
                    @if($partner->logo)
                        <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-white shadow-md mb-4 group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-md mb-4 group-hover:scale-105 transition duration-300">
                            <i data-lucide="bus" class="w-10 h-10 text-gray-400"></i>
                        </div>
                    @endif
                    <h3 class="text-2xl font-extrabold text-dark-text">{{ $partner->name }}</h3>
                </div>
                
                <div class="p-8 space-y-6">
                    @if($partner->description)
                        <p class="text-sm text-gray-600 text-center leading-relaxed">
                            {{ $partner->description }}
                        </p>
                    @endif

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary-maroon mt-0.5 flex-shrink-0"></i>
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Operating Routes</h4>
                                <p class="text-sm font-semibold text-gray-700 whitespace-pre-line">{{ $partner->operating_routes ?: 'Multiple Routes' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i data-lucide="star" class="w-5 h-5 text-primary-gold mt-0.5 flex-shrink-0"></i>
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Experience</h4>
                                <p class="text-sm font-semibold text-gray-700">Luxury AC & Non-AC</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('home') }}" class="block w-full text-center bg-gray-50 hover:bg-primary-maroon hover:text-white text-dark-maroon font-bold py-3 px-4 rounded-xl transition duration-300 border border-gray-100 hover:border-transparent mt-4">
                        View Routes
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i data-lucide="info" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-xl font-bold text-gray-500">More partners joining soon!</h3>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Why Travel Section -->
<div class="py-20 bg-gray-50 px-6">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="text-3xl font-extrabold text-dark-text mb-12">Why Travel With Our Partners</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Safe Travel</h3>
                <p class="text-sm text-gray-500">Verified operators prioritizing your safety on every journey.</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="sofa" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Comfortable Seats</h3>
                <p class="text-sm text-gray-500">Modern buses with spacious and comfortable seating.</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">On-Time Service</h3>
                <p class="text-sm text-gray-500">Punctual departures and arrivals to respect your schedule.</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="user-check" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Professional Drivers</h3>
                <p class="text-sm text-gray-500">Experienced staff ensuring a smooth and pleasant trip.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-16 bg-white text-center px-6 border-t border-gray-100">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-extrabold text-dark-text mb-6">Ready to start your journey?</h2>
        <a href="{{ route('home') }}" class="inline-flex items-center bg-primary-maroon text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-dark-maroon transition shadow-lg hover:shadow-xl hover:-translate-y-1">
            <i data-lucide="search" class="w-5 h-5 mr-2"></i> Book Your Seat Now
        </a>
    </div>
</div>

@endsection
