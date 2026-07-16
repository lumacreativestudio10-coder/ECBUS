<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | ECBUS Admin</title>
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 text-dark-text antialiased font-sans flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-dark-maroon/50 lg:hidden backdrop-blur-sm" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-dark-maroon text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl">
        <div class="flex items-center justify-center h-20 border-b border-white/10 bg-black/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('image/logo.png') }}" alt="ECBUS Logo" class="h-8 filter brightness-0 invert">
                <span class="text-xl font-extrabold tracking-wider">ADMIN</span>
            </a>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary-gold text-dark-maroon font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white font-medium' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.bookings') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i> Manage Bookings
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">CRM Modules</p>
            </div>

            <a href="{{ route('admin.bus_companies') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.bus_companies') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                <i data-lucide="building" class="w-5 h-5 mr-3"></i> Bus Companies
            </a>

            <a href="{{ route('admin.routes') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.routes') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                <i data-lucide="map" class="w-5 h-5 mr-3"></i> Routes
            </a>
            
            <a href="{{ route('admin.reviews') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.reviews') ? 'bg-dark-maroon text-white font-bold' : 'text-gray-300 hover:bg-dark-maroon hover:text-white' }} rounded-xl transition-all mb-2">
                <i data-lucide="star" class="w-5 h-5 mr-3"></i> Manage Reviews
            </a>

            <a href="{{ route('admin.contact_messages') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.contact_messages') ? 'bg-dark-maroon text-white font-bold' : 'text-gray-300 hover:bg-dark-maroon hover:text-white' }} rounded-xl transition-all mb-2">
                <i data-lucide="mail" class="w-5 h-5 mr-3"></i> Contact Messages
            </a>
            
            <a href="{{ route('admin.buses') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.buses') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                <i data-lucide="bus" class="w-5 h-5 mr-3"></i> Manage Buses
            </a>

            <a href="{{ route('admin.schedules') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.schedules') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                <i data-lucide="clock" class="w-5 h-5 mr-3"></i> Bus Schedules
            </a>

            <a href="{{ route('admin.locations') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.locations') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                <i data-lucide="map-pin" class="w-5 h-5 mr-3"></i> Destinations
            </a>
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Settings</p>
            </div>
            
            <!-- Manage Users (Super Admin and Company Admin only) -->
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isCompanyAdmin())
                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.users') ? 'bg-primary-maroon text-white font-bold' : 'text-gray-300 hover:text-white hover:bg-white/10 transition font-medium' }} rounded-xl mb-2">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i> Manage Users
                </a>
            @endif

        </nav>

        <div class="p-4 border-t border-white/10">
            <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-400 hover:text-white transition font-medium mb-2">
                <i data-lucide="external-link" class="w-5 h-5 mr-3"></i> Back to Website
            </a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition font-medium">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 h-20 flex items-center justify-between px-6 lg:px-10 z-10 flex-shrink-0">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-dark-text focus:outline-none lg:hidden mr-4">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-2xl font-extrabold text-dark-text">@yield('header')</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-bold text-dark-text">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->role->name ?? 'ECBUS Panel' }}</p>
                </div>
                <div class="w-10 h-10 bg-primary-gold rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                    <i data-lucide="shield-check" class="w-5 h-5 text-dark-maroon"></i>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 lg:p-10">
            @yield('content')
        </main>
        
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
