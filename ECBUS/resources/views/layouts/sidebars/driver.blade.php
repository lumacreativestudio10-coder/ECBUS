<a href="{{ route('driver.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('driver.dashboard') ? 'bg-primary-gold text-dark-maroon font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
</a>

<a href="{{ route('driver.my_trips') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('driver.my_trips') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="bus" class="w-5 h-5 mr-3"></i> My Trips
</a>

<a href="{{ route('driver.global_passenger_list') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('driver.passenger_list') || request()->routeIs('driver.global_passenger_list') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="users" class="w-5 h-5 mr-3"></i> Passenger List
</a>

<a href="{{ route('driver.global_route_details') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('driver.route_details') || request()->routeIs('driver.global_route_details') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="map" class="w-5 h-5 mr-3"></i> Route Details
</a>

<a href="{{ route('driver.trip_history') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('driver.trip_history') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="history" class="w-5 h-5 mr-3"></i> Trip History
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Settings</p>
</div>

<a href="{{ route('driver.profile') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('driver.profile') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="user" class="w-5 h-5 mr-3"></i> My Profile
</a>
