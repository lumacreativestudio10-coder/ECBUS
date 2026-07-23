@extends('layouts.admin')

@section('title', 'Manage Routes')
@section('header', 'Manage Travel Routes')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div x-data="routeManager()">

    <!-- Header Actions & Search -->
    <div class="flex justify-between items-center mb-6 gap-4">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" x-model="searchQuery" placeholder="Search by Route Name or Location..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition">
        </div>
        <button @click="isAddOpen = true" class="inline-flex items-center justify-center bg-primary-maroon text-white text-sm font-bold rounded-lg px-4 py-2 hover:bg-dark-maroon transition shadow-sm whitespace-nowrap">
            <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Add Route
        </button>
    </div>

    <!-- Routes List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Route Name</th>
                        <th class="px-6 py-4">From → To</th>
                        <th class="px-6 py-4">Est. Info</th>
                        <th class="px-6 py-4">Starting Price</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition"
                        x-show="matchesSearch('{{ addslashes($route->name) }}', '{{ addslashes($route->fromLocation->name ?? '') }}', '{{ addslashes($route->toLocation->name ?? '') }}')">
                        <td class="px-6 py-4 font-bold text-dark-text">
                            {{ $route->name }}
                            @if($route->status)
                                <span class="inline-block ml-2 w-2 h-2 bg-green-500 rounded-full" title="Active"></span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-700">{{ $route->fromLocation->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-400">to {{ $route->toLocation->name ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            <div><i data-lucide="clock" class="w-3 h-3 inline"></i> {{ $route->duration_string }}</div>
                            <div><i data-lucide="map-pin" class="w-3 h-3 inline"></i> {{ $route->distance ? $route->distance . ' km' : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 font-extrabold text-primary-maroon">
                            LKR {{ number_format($route->starting_price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                @if(auth()->user()->isSuperAdmin() || $route->created_by === auth()->id())
                                    <button @click='openEdit({{ json_encode($route) }})' type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                @endif
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route(auth()->user()->getRolePrefix().'.routes.destroy', $route) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this route?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 font-medium">
                            <i data-lucide="map" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            No routes configured yet.
                        </td>
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
                <form action="{{ route(auth()->user()->getRolePrefix().'.routes.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="map" class="h-5 w-5 text-green-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Add New Route</h3>
                                <div class="mt-4 space-y-4 text-left">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Route Name</label>
                                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Colombo - Kandy Express" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">From</label>
                                            <select name="from_location_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="">Select...</option>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">To</label>
                                            <select name="to_location_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="">Select...</option>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Distance (km)</label>
                                            <input type="number" name="distance" value="{{ old('distance') }}" step="0.1" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Duration (Minutes)</label>
                                            <input type="number" name="estimated_duration_minutes" value="{{ old('estimated_duration_minutes') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Starting Price (LKR)</label>
                                            <input type="number" name="starting_price" value="{{ old('starting_price') }}" step="0.01" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                            <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Stops Builder for New Route -->
                                    <div class="border-t border-gray-100 pt-4 mt-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-bold text-sm text-gray-700">Intermediate Stops</h4>
                                            <button type="button" @click="addStop('new')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-md font-bold transition">
                                                + Add Stop
                                            </button>
                                        </div>
                                        <input type="hidden" name="stops" :value="JSON.stringify(newStops)">
                                        
                                        <div class="space-y-2">
                                            <template x-for="(stop, index) in newStops" :key="index">
                                                <div class="flex gap-2 items-start bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                    <div class="flex-grow">
                                                        <input type="text" x-model="stop.stop_name" placeholder="Stop Name" class="w-full border border-gray-200 rounded text-xs px-2 py-1.5 focus:border-primary-maroon outline-none" required>
                                                    </div>
                                                    <div class="w-24">
                                                        <input type="number" x-model="stop.time_offset_minutes" placeholder="Mins" class="w-full border border-gray-200 rounded text-xs px-2 py-1.5 focus:border-primary-maroon outline-none" title="Minutes from start">
                                                    </div>
                                                    <button type="button" @click="removeStop('new', index)" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </template>
                                            <div x-show="newStops.length === 0" class="text-xs text-gray-400 text-center py-2 italic">
                                                No stops added. This route is point-to-point.
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                            Save Route
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
                <form :action="`{{ url('/'.auth()->user()->getRolePrefix().'/routes') }}/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="edit" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Edit Route</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Route Name</label>
                                        <input type="text" name="name" x-model="editData.name" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">From</label>
                                            <select name="from_location_id" x-model="editData.from_location_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">To</label>
                                            <select name="to_location_id" x-model="editData.to_location_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Distance (km)</label>
                                            <input type="number" name="distance" x-model="editData.distance" step="0.1" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Duration (Minutes)</label>
                                            <input type="number" name="estimated_duration_minutes" x-model="editData.estimated_duration_minutes" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Starting Price (LKR)</label>
                                            <input type="number" name="starting_price" x-model="editData.starting_price" step="0.01" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                            <select name="status" x-model="editData.status_string" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Stops Builder for Edit Route -->
                                    <div class="border-t border-gray-100 pt-4 mt-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-bold text-sm text-gray-700">Intermediate Stops</h4>
                                            <button type="button" @click="addStop('edit')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-md font-bold transition">
                                                + Add Stop
                                            </button>
                                        </div>
                                        <input type="hidden" name="stops" :value="JSON.stringify(editStops)">
                                        
                                        <div class="space-y-2">
                                            <template x-for="(stop, index) in editStops" :key="index">
                                                <div class="flex gap-2 items-start bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                    <div class="flex-grow">
                                                        <input type="text" x-model="stop.stop_name" placeholder="Stop Name" class="w-full border border-gray-200 rounded text-xs px-2 py-1.5 focus:border-primary-maroon outline-none" required>
                                                    </div>
                                                    <div class="w-24">
                                                        <input type="number" x-model="stop.time_offset_minutes" placeholder="Mins" class="w-full border border-gray-200 rounded text-xs px-2 py-1.5 focus:border-primary-maroon outline-none" title="Minutes from start">
                                                    </div>
                                                    <button type="button" @click="removeStop('edit', index)" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </template>
                                            <div x-show="editStops.length === 0" class="text-xs text-gray-400 text-center py-2 italic">
                                                No stops configured.
                                            </div>
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
    Alpine.data('routeManager', () => ({
        isAddOpen: false,
        isEditOpen: false, 
        editData: {}, 
        newStops: [],
        editStops: [],
        searchQuery: '',
        
        matchesSearch(name, from, to) {
            if(this.searchQuery.trim() === '') return true;
            let q = this.searchQuery.toLowerCase();
            return (name || '').toLowerCase().includes(q) || 
                   (from || '').toLowerCase().includes(q) || 
                   (to || '').toLowerCase().includes(q);
        },
        
        openEdit(route) { 
            this.editData = { ...route, status_string: route.status ? 'active' : 'inactive' }; 
            this.editStops = route.stops ? JSON.parse(JSON.stringify(route.stops)) : [];
            this.isEditOpen = true; 
        },
        addStop(type) {
            if(type === 'new') this.newStops.push({stop_name: '', time_offset_minutes: 0});
            else this.editStops.push({stop_name: '', time_offset_minutes: 0});
        },
        removeStop(type, index) {
            if(type === 'new') this.newStops.splice(index, 1);
            else this.editStops.splice(index, 1);
        }
    }));
});
</script>
@endpush
