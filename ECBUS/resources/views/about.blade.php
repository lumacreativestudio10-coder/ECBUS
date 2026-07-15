@extends('layouts.app')

@section('title', 'About Us - ECBUS')

@section('content')

<!-- Hero Section -->
<section class="relative pt-24 pb-20 lg:pt-32 lg:pb-28 overflow-hidden bg-dark-maroon">
    <div class="absolute inset-0 z-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Bus Travel" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-dark-maroon to-transparent"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 animate-fade-in-up">
            ABOUT <span class="text-primary-gold">ECBUS</span>
        </h1>
        <p class="text-lg md:text-xl font-light opacity-90 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
            Revolutionizing the way you travel across Sri Lanka with comfort, safety, and reliability.
        </p>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="ECBUS Journey" class="w-full h-auto object-cover transform hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 border-4 border-primary-gold/30 rounded-3xl m-4 pointer-events-none"></div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-sm font-bold text-primary-gold uppercase tracking-widest mb-2">Our Story</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-6">JOURNEY BEGAN WITH A SIMPLE IDEA</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Founded in 2024, ECBUS was born out of the necessity to make long-distance bus travel in Sri Lanka completely hassle-free. We realized that passengers often struggled with booking seats, facing long queues and uncertain schedules. 
                </p>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Our platform connects passengers with top-rated bus companies, offering a seamless online booking experience. From choosing your favorite window seat to receiving an instant e-ticket on WhatsApp, we have digitized the entire process.
                </p>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-primary-maroon">
                        <h4 class="text-3xl font-extrabold text-dark-maroon mb-1">50+</h4>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Bus Partners</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-primary-gold">
                        <h4 class="text-3xl font-extrabold text-dark-maroon mb-1">100k+</h4>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Happy Travelers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-20 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-gray-50 rounded-3xl p-10 md:p-12 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-primary-maroon rounded-2xl flex items-center justify-center mb-8 shadow-lg transform -rotate-3">
                    <i data-lucide="eye" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-dark-text mb-4">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    To be the leading, most trusted digital travel companion in Sri Lanka, connecting every city and town with an organized, punctual, and highly premium bus transportation network.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-3xl p-10 md:p-12 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-primary-gold rounded-2xl flex items-center justify-center mb-8 shadow-lg transform rotate-3">
                    <i data-lucide="target" class="w-8 h-8 text-dark-maroon"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-dark-text mb-4">Our Mission</h3>
                <p class="text-gray-600 leading-relaxed">
                    To provide a flawless seat booking experience by leveraging modern technology, ensuring transparency in pricing, and prioritizing the safety and comfort of our passengers above all else.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <div class="mb-16">
            <h2 class="text-sm font-bold text-primary-gold uppercase tracking-widest mb-2">The ECBUS Advantage</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-dark-text">WHY CHOOSE US</h3>
            <div class="w-24 h-1 bg-gradient-to-r from-primary-maroon to-primary-gold mx-auto mt-6 rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:-translate-y-2 transition duration-300">
                <div class="w-16 h-16 mx-auto bg-primary-gold/20 rounded-full flex items-center justify-center mb-6 text-primary-maroon">
                    <i data-lucide="smartphone" class="w-8 h-8"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-3">Seamless Booking</h4>
                <p class="text-gray-500 text-sm">Book your favorite seat in under 2 minutes through our user-friendly platform.</p>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:-translate-y-2 transition duration-300">
                <div class="w-16 h-16 mx-auto bg-primary-gold/20 rounded-full flex items-center justify-center mb-6 text-primary-maroon">
                    <i data-lucide="shield-check" class="w-8 h-8"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-3">Verified Partners</h4>
                <p class="text-gray-500 text-sm">We only collaborate with highly rated, government-registered bus companies.</p>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:-translate-y-2 transition duration-300">
                <div class="w-16 h-16 mx-auto bg-primary-gold/20 rounded-full flex items-center justify-center mb-6 text-primary-maroon">
                    <i data-lucide="headphones" class="w-8 h-8"></i>
                </div>
                <h4 class="text-xl font-bold text-dark-text mb-3">24/7 Support</h4>
                <p class="text-gray-500 text-sm">Our customer support team is always awake and ready to assist you on WhatsApp.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to action -->
<section class="py-20 relative overflow-hidden bg-dark-maroon">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-6 text-white">Experience the difference today.</h2>
        <a href="{{ route('home') }}" class="inline-block bg-primary-gold text-dark-maroon px-8 py-4 rounded-xl font-extrabold text-lg hover:bg-white hover:text-dark-maroon transition duration-300 shadow-xl">
            BOOK YOUR SEAT NOW
        </a>
    </div>
</section>

@endsection

@push('scripts')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
</style>
@endpush
