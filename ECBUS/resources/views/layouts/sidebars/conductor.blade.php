<a href="{{ route('conductor.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('conductor.dashboard') ? 'bg-primary-gold text-dark-maroon font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
</a>

<a href="{{ route('conductor.today_trips') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('conductor.today_trips') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="bus" class="w-5 h-5 mr-3"></i> Today's Trips
</a>

<a href="{{ route('conductor.global_passenger_list') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('conductor.passenger_list') || request()->routeIs('conductor.global_passenger_list') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="users" class="w-5 h-5 mr-3"></i> Passenger List
</a>

<a href="{{ route('conductor.global_passenger_list') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('conductor.boarding') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="clipboard-check" class="w-5 h-5 mr-3"></i> Boarding
</a>

<a href="{{ route('conductor.ticket_verification') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('conductor.ticket_verification') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="ticket" class="w-5 h-5 mr-3"></i> Ticket Verification
</a>

<a href="{{ route('conductor.trip_history') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('conductor.trip_history') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }} mb-2">
    <i data-lucide="history" class="w-5 h-5 mr-3"></i> Trip History
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Settings</p>
</div>

<a href="{{ route('conductor.profile') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('conductor.profile') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="user" class="w-5 h-5 mr-3"></i> My Profile
</a>
