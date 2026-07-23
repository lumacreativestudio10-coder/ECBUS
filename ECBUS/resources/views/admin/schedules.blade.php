@extends('layouts.admin')

@section('title', 'Manage Schedules')
@section('header', 'Manage Bus Schedules')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div x-data="scheduleManager()">

    <!-- Header Actions & Search -->
    <div class="flex justify-between items-center mb-6 gap-4">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" x-model="searchQuery" placeholder="Search by Company, Bus or Route..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition">
        </div>
        <button @click="isAddOpen = true" class="inline-flex items-center justify-center bg-primary-maroon text-white text-sm font-bold rounded-lg px-4 py-2 hover:bg-dark-maroon transition shadow-sm whitespace-nowrap">
            <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Add Schedule
        </button>
    </div>

    <!-- Schedules List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Bus</th>
                        <th class="px-6 py-4">Route</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($schedules as $schedule)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition" 
                        x-show="matchesSearch('{{ addslashes($schedule->bus?->busCompany?->company_name) }}', '{{ addslashes($schedule->bus?->name) }}', '{{ addslashes($schedule->route?->name) }}')">
                        <td class="px-6 py-4">
                            <p class="font-bold text-dark-text">{{ $schedule->bus?->busCompany?->company_name ?? 'N/A' }}</p>
                            <span class="inline-block bg-primary-gold/20 text-dark-maroon text-[10px] px-2 py-0.5 rounded font-bold mt-1 uppercase">{{ $schedule->bus?->name ?? 'Unknown Bus' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-dark-text">{{ $schedule->route?->name ?? 'Unknown Route' }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $schedule->route?->fromLocation?->name ?? '?' }} → {{ $schedule->route?->toLocation?->name ?? '?' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-500 mt-1">{{ $schedule->date }} | {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-extrabold text-dark-maroon">LKR {{ number_format($schedule->price, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route(auth()->user()->getRolePrefix().'.schedules.manifest', $schedule) }}" target="_blank" class="inline-flex items-center text-dark-maroon hover:text-white bg-gray-100 hover:bg-dark-maroon transition px-3 py-1.5 rounded-lg font-bold text-xs">
                                    <i data-lucide="printer" class="w-3.5 h-3.5 mr-1"></i> CMS
                                </a>
                                <a href="{{ route(auth()->user()->getRolePrefix().'.schedules.seats', $schedule) }}" class="inline-flex items-center text-primary-maroon hover:text-dark-maroon bg-primary-gold/20 hover:bg-primary-gold/40 transition px-3 py-1.5 rounded-lg font-bold text-xs">
                                    <i data-lucide="armchair" class="w-3.5 h-3.5 mr-1"></i> Seats
                                </a>
                                <button @click="openEdit({{ json_encode($schedule) }})" type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route(auth()->user()->getRolePrefix().'.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No schedules found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="isAddOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isAddOpen" @click="isAddOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isAddOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route(auth()->user()->getRolePrefix().'.schedules.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="calendar-plus" class="h-5 w-5 text-green-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Add New Schedule</h3>
                                <div class="mt-4 space-y-4 text-left">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if(auth()->user()->isSuperAdmin())
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Company</label>
                                            <select x-model="selectedCompanyId" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                <option value="">All Companies...</option>
                                                @foreach($busCompanies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Bus</label>
                                            <select name="bus_id" x-model="addBusId" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="">Select Bus...</option>
                                                <template x-for="bus in filteredBuses" :key="bus.id">
                                                    <option :value="bus.id" x-text="bus.name + ' (' + (bus.bus_type?.name || 'Unknown Type') + ')'"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Route</label>
                                        <select name="route_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                            <option value="">Select Route...</option>
                                            @foreach($routes as $route)
                                                <option value="{{ $route->id }}">{{ $route->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Date</label>
                                        <input type="date" name="date" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Driver</label>
                                            <select name="driver_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                <option value="">Select Driver...</option>
                                                <template x-for="driver in addFilteredDrivers" :key="driver.id">
                                                    <option :value="driver.id" x-text="driver.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Conductor</label>
                                            <select name="conductor_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                <option value="">Select Conductor...</option>
                                                <template x-for="conductor in addFilteredConductors" :key="conductor.id">
                                                    <option :value="conductor.id" x-text="conductor.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Departure Time</label>
                                            <input type="time" name="departure_time" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Arrival Time</label>
                                            <input type="time" name="arrival_time" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Ticket Price (LKR)</label>
                                            <input type="number" name="price" step="0.01" min="0" placeholder="e.g. 2500" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                            <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="scheduled">Scheduled</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                            Save Schedule
                        </button>
                        <button type="button" @click="isAddOpen = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="isEditOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isEditOpen" @click="isEditOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isEditOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="`{{ url('/'.auth()->user()->getRolePrefix().'/schedules') }}/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="edit" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Edit Schedule</h3>
                                <div class="mt-4 space-y-4 text-left">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if(auth()->user()->isSuperAdmin())
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Company</label>
                                            <select x-model="editSelectedCompanyId" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                <option value="">All Companies...</option>
                                                @foreach($busCompanies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Bus</label>
                                            <select name="bus_id" x-model="editData.bus_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="">Select Bus...</option>
                                                <template x-for="bus in editFilteredBuses" :key="bus.id">
                                                    <option :value="bus.id" x-text="bus.name + ' (' + (bus.bus_type?.name || 'Unknown Type') + ')'"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Route</label>
                                        <select name="route_id" x-model="editData.route_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                            <option value="">Select Route...</option>
                                            @foreach($routes as $route)
                                                <option value="{{ $route->id }}">{{ $route->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Date</label>
                                        <input type="date" name="date" x-model="editData.date_formatted" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Driver</label>
                                            <select name="driver_id" x-model="editData.driver_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                <option value="">Select Driver...</option>
                                                <template x-for="driver in editFilteredDrivers" :key="driver.id">
                                                    <option :value="driver.id" x-text="driver.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Conductor</label>
                                            <select name="conductor_id" x-model="editData.conductor_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                <option value="">Select Conductor...</option>
                                                <template x-for="conductor in editFilteredConductors" :key="conductor.id">
                                                    <option :value="conductor.id" x-text="conductor.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Departure Time</label>
                                            <input type="time" name="departure_time" x-model="editData.departure_time" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Arrival Time</label>
                                            <input type="time" name="arrival_time" x-model="editData.arrival_time" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Ticket Price (LKR)</label>
                                            <input type="number" name="price" x-model="editData.price" step="0.01" min="0" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                            <select name="status" x-model="editData.status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="scheduled">Scheduled</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                            Save Changes
                        </button>
                        <button type="button" @click="isEditOpen = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('scheduleManager', () => ({
        isAddOpen: false,
        isEditOpen: false, 
        editData: {}, 
        searchQuery: '',
        buses: @json($buses),
        drivers: @json($drivers),
        conductors: @json($conductors),
        selectedCompanyId: '',
        editSelectedCompanyId: '',
        addBusId: '',
        
        matchesSearch(company, bus, route) {
            if(this.searchQuery.trim() === '') return true;
            let q = this.searchQuery.toLowerCase();
            return (company || '').toLowerCase().includes(q) || 
                   (bus || '').toLowerCase().includes(q) || 
                   (route || '').toLowerCase().includes(q);
        },
        
        get filteredBuses() {
            if (!this.selectedCompanyId) return this.buses;
            return this.buses.filter(b => b.bus_company_id == this.selectedCompanyId);
        },
        
        get editFilteredBuses() {
            if (!this.editSelectedCompanyId) return this.buses;
            return this.buses.filter(b => b.bus_company_id == this.editSelectedCompanyId);
        },
        
        get addFilteredDrivers() {
            if (!this.addBusId) return this.drivers;
            let bus = this.buses.find(b => b.id == this.addBusId);
            if (!bus || !bus.bus_company_id) return this.drivers;
            return this.drivers.filter(d => d.company_id == bus.bus_company_id);
        },

        get addFilteredConductors() {
            if (!this.addBusId) return this.conductors;
            let bus = this.buses.find(b => b.id == this.addBusId);
            if (!bus || !bus.bus_company_id) return this.conductors;
            return this.conductors.filter(c => c.company_id == bus.bus_company_id);
        },
        
        get editFilteredDrivers() {
            if (!this.editData.bus_id) return this.drivers;
            let bus = this.buses.find(b => b.id == this.editData.bus_id);
            if (!bus || !bus.bus_company_id) return this.drivers;
            return this.drivers.filter(d => d.company_id == bus.bus_company_id);
        },

        get editFilteredConductors() {
            if (!this.editData.bus_id) return this.conductors;
            let bus = this.buses.find(b => b.id == this.editData.bus_id);
            if (!bus || !bus.bus_company_id) return this.conductors;
            return this.conductors.filter(c => c.company_id == bus.bus_company_id);
        },
        
        openEdit(schedule) { 
            let d = schedule.date; 
            if(d && d.includes('T')) d = d.split('T')[0]; 
            this.editData = { ...schedule, date_formatted: d }; 
            this.editSelectedCompanyId = schedule.bus?.bus_company_id || '';
            this.isEditOpen = true; 
        } 
    }));
});
</script>
@endpush
