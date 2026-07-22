<footer class="bg-dark-maroon text-cream pt-16 pb-8 border-t-[6px] border-primary-gold mt-20">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        <!-- Column 1 -->
        <div class="lg:col-span-2">
            <a href="{{ url('/') }}" class="inline-block mb-6 bg-white/10 p-2 rounded-xl backdrop-blur-sm">
                <img src="{{ asset('image/logo.png') }}" alt="ECBUS Logo" class="h-12 w-auto">
            </a>
            <p class="opacity-80 mb-6 leading-relaxed pr-8">
                Your trusted travel partner for safe, comfortable and affordable journeys across Sri Lanka.
            </p>
            <div class="flex space-x-4">
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary-gold hover:text-dark-text transition duration-300">
                    <i data-lucide="facebook" class="w-5 h-5"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary-gold hover:text-dark-text transition duration-300">
                    <i data-lucide="instagram" class="w-5 h-5"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary-gold hover:text-dark-text transition duration-300">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                </a>
            </div>
        </div>

        <!-- Column 2 -->
        <div>
            <h4 class="text-lg font-bold text-primary-gold mb-6 uppercase tracking-wider">Quick Links</h4>
            <ul class="space-y-3 opacity-80">
                <li><a href="{{ route('home') }}" class="hover:text-primary-gold transition flex items-center"><i data-lucide="chevron-right" class="w-4 h-4 mr-1"></i> Home</a></li>
                <li><a href="{{ route('routes') }}" class="hover:text-primary-gold transition flex items-center"><i data-lucide="chevron-right" class="w-4 h-4 mr-1"></i> Routes</a></li>
                <li><a href="{{ route('partners') }}" class="hover:text-primary-gold transition flex items-center"><i data-lucide="chevron-right" class="w-4 h-4 mr-1"></i> Bus Partners</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-primary-gold transition flex items-center"><i data-lucide="chevron-right" class="w-4 h-4 mr-1"></i> About Us</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-primary-gold transition flex items-center"><i data-lucide="chevron-right" class="w-4 h-4 mr-1"></i> Contact Us</a></li>
                <li><a href="{{ route('booking') }}" class="hover:text-primary-gold transition flex items-center"><i data-lucide="chevron-right" class="w-4 h-4 mr-1"></i> My Booking</a></li>
            </ul>
        </div>

        <!-- Column 3 -->
        <div>
            <h4 class="text-lg font-bold text-primary-gold mb-6 uppercase tracking-wider">Popular Routes</h4>
            @php
                $footerPopularRoutes = \App\Models\PopularRoute::with(['fromLocation', 'toLocation'])->where('status', 1)->take(5)->get();
            @endphp
            <ul class="space-y-3 opacity-80">
                @forelse($footerPopularRoutes as $route)
                    <li>
                        <a href="{{ route('routes') }}?from={{ urlencode($route->fromLocation->name ?? '') }}&to={{ urlencode($route->toLocation->name ?? '') }}" class="hover:text-primary-gold transition flex items-center">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-2 opacity-50"></i> {{ $route->fromLocation->name ?? 'Unknown' }} &rarr; {{ $route->toLocation->name ?? 'Unknown' }}
                        </a>
                    </li>
                @empty
                    <li><span class="opacity-50 flex items-center"><i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> No Routes Available</span></li>
                @endforelse
            </ul>
        </div>

        <!-- Column 4 -->
        <div>
            <h4 class="text-lg font-bold text-primary-gold mb-6 uppercase tracking-wider">Contact Us</h4>
            <ul class="space-y-4 opacity-80">
                <li class="flex items-start">
                    <i data-lucide="phone-call" class="w-5 h-5 mr-3 text-primary-gold mt-0.5"></i>
                    <div>
                        <p>+94 77 123 4567</p>
                        <p>+94 77 123 4567</p>
                    </div>
                </li>
                <li class="flex items-center">
                    <i data-lucide="mail" class="w-5 h-5 mr-3 text-primary-gold"></i>
                    <p>info@ecbus.lk</p>
                </li>
                <li class="flex items-start">
                    <i data-lucide="map-pinned" class="w-5 h-5 mr-3 text-primary-gold mt-0.5"></i>
                    <p>Colombo, Sri Lanka</p>
                </li>
            </ul>
            
            <!-- Payment Methods -->
            {{-- <div class="mt-8">
                <h4 class="text-sm font-bold text-primary-gold mb-3 uppercase tracking-wider">Payment Methods</h4>
                <div class="flex space-x-3">
                    <div class="bg-white px-2 py-1 rounded flex items-center justify-center h-8 w-12"><span class="text-dark-text font-bold text-xs">VISA</span></div>
                    <div class="bg-white px-2 py-1 rounded flex items-center justify-center h-8 w-12"><span class="text-dark-text font-bold text-xs">MC</span></div>
                    <div class="bg-white px-2 py-1 rounded flex items-center justify-center h-8 w-12"><span class="text-dark-text font-bold text-xs">BANK</span></div>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row justify-between items-center opacity-60 text-sm">
            <p>&copy; {{ date('Y') }} ECBUS. All Rights Reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="{{ route('admin.login') }}" class="hover:text-primary-gold transition font-bold"><i data-lucide="lock" class="w-3 h-3 inline mr-1"></i>Staff Login</a>
                <a href="#" class="hover:text-primary-gold transition">Terms & Conditions</a>
                <a href="#" class="hover:text-primary-gold transition">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
