<a href="{{ route(auth()->user()->getRolePrefix().'.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs(auth()->user()->getRolePrefix().'.dashboard') ? 'bg-primary-gold text-dark-maroon font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }}">
    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
</a>
<a href="{{ route(auth()->user()->getRolePrefix().'.bookings') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.bookings') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i> Manage Bookings
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Company Management</p>
</div>

<a href="{{ route(auth()->user()->getRolePrefix().'.buses') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.buses') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="bus" class="w-5 h-5 mr-3"></i> Manage Buses
</a>

<a href="{{ route(auth()->user()->getRolePrefix().'.routes') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.routes') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="map" class="w-5 h-5 mr-3"></i> Routes
</a>

<a href="{{ route(auth()->user()->getRolePrefix().'.locations') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.locations') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="map-pin" class="w-5 h-5 mr-3"></i> Destinations
</a>

<a href="{{ route(auth()->user()->getRolePrefix().'.schedules') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.schedules') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="clock" class="w-5 h-5 mr-3"></i> Bus Schedules
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Settings</p>
</div>

<a href="{{ route(auth()->user()->getRolePrefix().'.users') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.users') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="users" class="w-5 h-5 mr-3"></i> Manage Users
</a>

<a href="{{ route(auth()->user()->getRolePrefix().'.profile') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.profile') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="user" class="w-5 h-5 mr-3"></i> My Profile
</a>
