<a href="{{ route(auth()->user()->getRolePrefix().'.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs(auth()->user()->getRolePrefix().'.dashboard') ? 'bg-primary-gold text-dark-maroon font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }}">
    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
</a>
<a href="{{ route(auth()->user()->getRolePrefix().'.bookings') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.bookings') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i> Manage Bookings
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Settings</p>
</div>

<a href="{{ route(auth()->user()->getRolePrefix().'.profile') }}" class="flex items-center px-4 py-3 {{ request()->routeIs(auth()->user()->getRolePrefix().'.profile') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
    <i data-lucide="user" class="w-5 h-5 mr-3"></i> My Profile
</a>
