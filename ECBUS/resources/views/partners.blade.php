@extends('layouts.app')

@section('title', 'Bus Partners - ECBUS')

@section('content')

<!-- Hero Section -->
<section class="relative pt-24 pb-32 lg:pt-36 lg:pb-40 overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Bus Partners" class="w-full h-full object-cover">
        <!-- Dark Maroon Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-dark-maroon/95 to-dark-maroon/80"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center text-white">
        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 text-white px-5 py-2 rounded-full font-bold text-sm mb-8 shadow-sm animate-fade-in-up" style="animation: fadeInUp 0.6s ease-out;">
            <i data-lucide="award" class="w-4 h-4 text-primary-gold"></i>
            <span>Our Trusted Network</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 animate-fade-in-up" style="animation: fadeInUp 0.8s ease-out;">
            Our Official <br class="hidden md:block">
            <span class="text-primary-gold">Bus Partners</span>
        </h1>
        <p class="text-lg md:text-xl font-light opacity-90 max-w-2xl mx-auto animate-fade-in-up" style="animation: fadeInUp 1s ease-out;">
            Experience premium travel. We proudly collaborate with Sri Lanka's finest transport partners to ensure your journey is safe, comfortable, and unforgettable.
        </p>
    </div>
</section>

<!-- Partner Cards Section -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-4 uppercase">Our Trusted Partners</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Travel with Sri Lanka's finest and most reliable transport operators.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($partners as $partner)
            <div class="bg-white rounded-3xl border border-gray-100/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-2 transition-all duration-500 overflow-hidden group relative flex flex-col">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-maroon/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                
                <div class="p-8 pb-8 text-center relative z-10 flex-grow flex flex-col justify-center">
                    <div class="relative inline-block mb-6 mx-auto">
                        <div class="absolute inset-0 bg-primary-gold/20 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 group-hover:scale-110"></div>
                        @if($partner->logo)
                            <div class="relative w-24 h-24 mx-auto rounded-full bg-white border-4 border-white shadow-lg overflow-hidden group-hover:scale-105 transition duration-500 z-10">
                                <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->company_name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="relative w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center border-4 border-white shadow-lg group-hover:scale-105 transition duration-500 z-10">
                                <i data-lucide="bus" class="w-12 h-12 text-gray-400 group-hover:text-primary-maroon transition-colors duration-300"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900 group-hover:text-primary-maroon transition-colors duration-300 mb-4">{{ $partner->company_name }}</h3>
                    
                    @if($partner->description)
                        <p class="text-sm text-gray-500 text-center leading-relaxed line-clamp-3">
                            {{ $partner->description }}
                        </p>
                    @endif
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
</section>

<!-- Why Travel Section -->
<section class="py-20 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-4 uppercase">WHY TRAVEL WITH <span class="text-primary-gold">OUR PARTNERS</span></h2>
            <p class="text-gray-500 max-w-2xl mx-auto">We've selected the best operators to ensure your journey is nothing short of excellent.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="shield-check" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">Safe Travel</h4>
                <p class="text-gray-500">Verified operators prioritizing your safety on every journey.</p>
            </div>
            
            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="sofa" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">Supreme Comfort</h4>
                <p class="text-gray-500">Modern buses with spacious, ergonomic and comfortable seating.</p>
            </div>
            
            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="clock" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">On-Time Service</h4>
                <p class="text-gray-500">Punctual departures and arrivals to respect your schedule.</p>
            </div>
            
            <div class="text-center relative z-10">
                <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center mb-6 relative group hover:-translate-y-2 transition duration-300">
                    <div class="absolute inset-0 rounded-full bg-primary-gold/20 scale-0 group-hover:scale-100 transition duration-500"></div>
                    <i data-lucide="user-check" class="w-10 h-10 text-primary-maroon relative z-10"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-2">Expert Drivers</h4>
                <p class="text-gray-500">Experienced staff ensuring a smooth and pleasant trip.</p>
            </div>
        </div>
    </div>
</section>

@endsection
