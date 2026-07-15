@extends('layouts.admin')

@section('title', 'Manage Partners')
@section('header', 'Manage Bus Partners')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ isEditOpen: false, editData: {}, openEdit(partner) { this.editData = { ...partner, status: (partner.status == 1 || partner.status === true) ? 'active' : 'inactive' }; this.isEditOpen = true; } }">

    <!-- Add New Partner Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">Add New Partner</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Partner Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. EC Express" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                        @error('name') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Logo (Auto-converted to WebP, Max 5MB)</label>
                        <input type="file" name="logo" accept="image/*" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                        @error('logo') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Operating Routes</label>
                        <textarea name="operating_routes" placeholder="e.g. Kalmune → Colombo&#10;Colombo → Jaffna" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">{{ old('operating_routes') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                        <textarea name="description" placeholder="e.g. EC Express provides comfortable..." rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary-maroon text-white font-bold rounded-lg px-4 py-3 hover:bg-dark-maroon transition shadow-md mt-4">
                        <i data-lucide="plus-circle" class="w-5 h-5 inline-block mr-1"></i> Add Partner
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Partners List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-dark-text">Registered Partners</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                            <th class="px-6 py-4">Partner</th>
                            <th class="px-6 py-4">Routes</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $partner)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 flex items-center space-x-3">
                                @if($partner->logo)
                                    <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <i data-lucide="bus" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="font-bold text-dark-text">{{ $partner->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="text-xs">{{ Str::limit($partner->operating_routes, 50, '...') ?: 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($partner->status)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Active</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit({{ json_encode($partner) }})" type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this partner?');">
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
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                No partners registered yet.
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
                <form :action="`{{ url('/admin/partners') }}/${editData.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="edit" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Edit Partner</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Partner Name</label>
                                        <input type="text" name="name" x-model="editData.name" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-2">Logo</label>
                                        
                                        <!-- Current Logo Preview -->
                                        <div class="mb-3 flex items-center space-x-4" x-show="editData.logo">
                                            <div class="relative">
                                                <img :src="editData.logo ? '/storage/' + editData.logo : ''" class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-sm" alt="Current Logo">
                                                <div class="absolute -top-2 -right-2 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md shadow-sm">Current</div>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Upload a new image below <br>to replace the current logo.
                                            </div>
                                        </div>

                                        <input type="file" name="logo" accept="image/*" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Operating Routes</label>
                                        <textarea name="operating_routes" x-model="editData.operating_routes" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                        <select name="status" x-model="editData.status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                                        <textarea name="description" x-model="editData.description" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
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
