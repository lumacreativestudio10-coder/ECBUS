<nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('image/logo.png') }}" alt="ECBUS Logo" class="h-10 w-auto">
        </a>

        <!-- Desktop Links -->
        <div class="hidden lg:flex items-center space-x-8 font-medium">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon' }} transition duration-300">Home</a>
            <a href="{{ route('routes') }}" class="{{ request()->routeIs('routes') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon' }} transition duration-300">Routes</a>
            <a href="{{ route('partners') }}" class="{{ request()->routeIs('partners') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon' }} transition duration-300">Bus Partners</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon' }} transition duration-300">About Us</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon' }} transition duration-300">Contact Us</a>
            <a href="{{ route('booking') }}" class="{{ request()->routeIs('booking') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon' }} transition duration-300">My Booking</a>
        </div>

        <!-- Book Now Button (Desktop) -->
        <div class="hidden lg:block">
            <a href="{{ route('routes') }}" class="bg-primary-gold text-dark-text px-6 py-2.5 rounded-full font-bold shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-300">
                BOOK NOW
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-primary-maroon focus:outline-none">
            <i data-lucide="menu" class="w-8 h-8" x-show="!mobileMenuOpen"></i>
            <i data-lucide="x" class="w-8 h-8" x-show="mobileMenuOpen" x-cloak></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full left-0" x-cloak>
        <div class="flex flex-col px-6 py-4 space-y-4">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon font-medium' }}">Home</a>
            <a href="{{ route('routes') }}" class="{{ request()->routeIs('routes') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon font-medium' }}">Routes</a>
            <a href="{{ route('partners') }}" class="{{ request()->routeIs('partners') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon font-medium' }}">Bus Partners</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon font-medium' }}">About Us</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon font-medium' }}">Contact Us</a>
            <a href="{{ route('booking') }}" class="{{ request()->routeIs('booking') ? 'text-primary-maroon font-bold' : 'text-dark-text hover:text-primary-maroon font-medium' }}">My Booking</a>
            <a href="{{ route('routes') }}" class="bg-primary-gold text-dark-text text-center px-6 py-3 rounded-xl font-bold shadow-md mt-2">
                BOOK NOW
            </a>
        </div>
    </div>
</nav>
