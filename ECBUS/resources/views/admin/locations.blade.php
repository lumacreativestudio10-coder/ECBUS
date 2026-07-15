@extends('layouts.admin')

@section('title', 'Manage Locations')
@section('header', 'Manage Destinations')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <ul class="list-disc list-inside font-bold text-sm">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ isEditOpen: false, editLoc: { id: null, name: '' }, openEdit(id, name) { this.editLoc = {id, name}; this.isEditOpen = true; } }">

    <!-- Add New Location Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">Add Destination</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.locations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">City / Location Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Jaffna" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                    </div>

                    <button type="submit" class="w-full bg-primary-maroon text-white font-bold rounded-lg px-4 py-3 hover:bg-dark-maroon transition shadow-md mt-4">
                        <i data-lucide="plus-circle" class="w-5 h-5 inline-block mr-1"></i> Add Location
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Locations List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">All Destinations</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($locations as $location)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-500">{{ $location->id }}</td>
                            <td class="px-6 py-4 font-bold text-dark-text">{{ $location->name }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit({{ $location->id }}, '{{ addslashes($location->name) }}')" type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this location?');">
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
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">No locations found.</td>
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
            <div x-show="isEditOpen" @click="isEditOpen = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isEditOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="`{{ url('/admin/locations') }}/${editLoc.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="edit" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Edit Destination</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">City / Location Name</label>
                                        <input type="text" name="name" x-model="editLoc.name" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                            Save Changes
                        </button>
                        <button type="button" @click="isEditOpen = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
