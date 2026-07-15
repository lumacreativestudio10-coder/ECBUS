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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ isEditOpen: false, editData: {}, openEdit(route) { this.editData = { ...route, status_string: route.status ? 'active' : 'inactive' }; this.isEditOpen = true; } }">

    <!-- Add New Route Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">Add New Route</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.routes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Route Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Colombo - Kandy Express" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                        @error('name') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">From</label>
                            <select name="from_location_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                <option value="">Select...</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('from_location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('from_location_id') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">To</label>
                            <select name="to_location_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                <option value="">Select...</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('to_location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('to_location_id') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Distance (km)</label>
                            <input type="number" name="distance" value="{{ old('distance') }}" step="0.1" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            @error('distance') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Duration</label>
                            <input type="text" name="estimated_duration" value="{{ old('estimated_duration') }}" placeholder="e.g. 3h 30m" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            @error('estimated_duration') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Starting Price (LKR)</label>
                            <input type="number" name="starting_price" value="{{ old('starting_price') }}" step="0.01" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            @error('starting_price') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary-maroon text-white font-bold rounded-lg px-4 py-3 hover:bg-dark-maroon transition shadow-md mt-4">
                        <i data-lucide="map" class="w-5 h-5 inline-block mr-1"></i> Add Route
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Routes List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">Available Routes</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
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
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
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
                                <div><i data-lucide="clock" class="w-3 h-3 inline"></i> {{ $route->estimated_duration ?? 'N/A' }}</div>
                                <div><i data-lucide="map-pin" class="w-3 h-3 inline"></i> {{ $route->distance ? $route->distance . ' km' : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-primary-maroon">
                                LKR {{ number_format($route->starting_price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit({{ json_encode($route) }})" type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this route?');">
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
    </div>

    <!-- Edit Modal -->
    <div x-show="isEditOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isEditOpen" @click="isEditOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isEditOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="`{{ url('/admin/routes') }}/${editData.id}`" method="POST">
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
                                    <div class="grid grid-cols-2 gap-4">
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
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Distance (km)</label>
                                            <input type="number" name="distance" x-model="editData.distance" step="0.1" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Duration</label>
                                            <input type="text" name="estimated_duration" x-model="editData.estimated_duration" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
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
