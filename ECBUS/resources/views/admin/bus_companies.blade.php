@extends('layouts.admin')

@section('title', 'Manage Bus Companies')
@section('header', 'Manage Bus Companies')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Error!</strong>
    <ul class="list-disc pl-5 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex flex-col gap-6" x-data="{ isEditOpen: false, isAddOpen: false, isViewOpen: false, editData: {}, viewData: {}, openEdit(company) { this.editData = { ...company, status: (company.status == 1 || company.status === true) ? '1' : '0' }; this.isEditOpen = true; }, openView(company) { this.viewData = company; this.isViewOpen = true; } }">



    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6">
        
        <!-- Left Side: Table & Filters -->
        <div class="flex flex-col gap-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <style>
                    .custom-filter-container { display: flex; flex-wrap: wrap; gap: 16px; width: 100%; align-items: flex-end; }
                    .custom-search { width: 100%; }
                    .custom-filters-right { display: flex; flex-wrap: wrap; gap: 12px; width: 100%; }
                    .custom-dropdown { width: 100%; }
                    .custom-btn-group { display: flex; gap: 8px; width: 100%; }
                    .custom-btn { flex: 1; justify-content: center; }
                    
                    @media (min-width: 640px) {
                        .custom-dropdown { width: 150px; }
                        .custom-btn-group { width: auto; }
                        .custom-btn { flex: none; width: auto; }
                    }
                    @media (min-width: 850px) {
                        .custom-search { flex-grow: 1; max-width: 350px; }
                        .custom-filters-right { width: auto; margin-left: auto; }
                    }
                </style>

                <form action="{{ route('admin.bus_companies') }}" method="GET" class="custom-filter-container">
                    
                    <!-- Search Input (Left) -->
                    <div class="custom-search">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company name..." class="w-full border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm text-gray-800 focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition bg-white shadow-sm">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Filter Dropdowns & Buttons (Right) -->
                    <div class="custom-filters-right">
                        
                        <div class="custom-dropdown">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <div class="relative w-full">
                                <select name="status" style="color-scheme: light;" class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-black focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition bg-white cursor-pointer shadow-sm appearance-none">
                                    <option value="">All Statuses</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute pointer-events-none" style="right: 12px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>

                        <div class="custom-dropdown">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">District</label>
                            <div class="relative w-full">
                                <select name="district" style="color-scheme: light;" class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-black focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition bg-white cursor-pointer shadow-sm appearance-none">
                                    <option value="">All Districts</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute pointer-events-none" style="right: 12px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>
                        
                        <div class="custom-dropdown">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Commission</label>
                            <div class="relative w-full">
                                <select name="commission" style="color-scheme: light;" class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-black focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition bg-white cursor-pointer shadow-sm appearance-none">
                                    <option value="">All Commission</option>
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute pointer-events-none" style="right: 12px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="custom-btn-group">
                            <button type="submit" class="custom-btn bg-primary-maroon hover:bg-dark-maroon text-white font-bold rounded-lg px-5 py-2.5 text-sm transition flex items-center shadow-md">
                                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
                            </button>
                            <a href="{{ route('admin.bus_companies') }}" class="custom-btn bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-lg px-4 py-2.5 text-sm transition flex items-center shadow-sm" title="Reset Filters">
                                <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 bg-white">
                    <h3 class="font-extrabold text-lg text-dark-text">Registered Bus Companies</h3>
                </div>
                
                <div class="overflow-x-auto flex-grow">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-white text-gray-400 text-[10px] uppercase font-bold border-b border-gray-100">
                                <th class="px-6 py-4">Company</th>
                                <th class="px-4 py-4">Contact Person</th>
                                <th class="px-4 py-4">Whatsapp</th>
                                <th class="px-4 py-4">Mobile</th>
                                <th class="px-4 py-4 text-center">Commission</th>
                                <th class="px-4 py-4 text-center">Routes</th>
                                <th class="px-4 py-4 text-center">Buses</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($busCompanies as $company)
                            <tr class="hover:bg-gray-50/30 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center border border-gray-100 shadow-sm overflow-hidden bg-white shrink-0">
                                            @if($company->logo)
                                                <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->company_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-primary-maroon text-white flex items-center justify-center font-bold text-lg">
                                                    {{ substr($company->company_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-dark-text text-[13px] group-hover:text-primary-maroon transition">{{ $company->company_name }}</div>
                                            <div class="text-[11px] text-gray-400">Code: {{ $company->company_code ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-gray-700 text-[13px]">{{ $company->contact_person }}</div>
                                    <div class="text-[11px] text-gray-400">Manager</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center text-[13px] text-gray-600">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5 mr-1.5 text-green-500"></i>
                                        {{ $company->whatsapp_number ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center text-[13px] text-gray-600">
                                        <i data-lucide="phone-call" class="w-3.5 h-3.5 mr-1.5 text-gray-400"></i>
                                        {{ $company->mobile_number }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="font-extrabold text-red-500 text-[13px]">
                                        LKR {{ number_format($company->commission_per_seat, 2) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center font-semibold text-gray-600">15</td>
                                <td class="px-4 py-4 text-center font-semibold text-gray-600">32</td>
                                <td class="px-4 py-4">
                                    @if($company->status)
                                        <span class="text-green-600 bg-green-50 border border-green-100 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Active</span>
                                    @else
                                        <span class="text-red-500 bg-red-50 border border-red-100 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button @click="openView({{ json_encode($company) }})" type="button" class="text-gray-400 hover:text-gray-700 bg-white border border-gray-200 transition p-1.5 rounded-md hover:bg-gray-50 shadow-sm" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="openEdit({{ json_encode($company) }})" type="button" class="text-gray-400 hover:text-blue-600 bg-white border border-gray-200 transition p-1.5 rounded-md hover:bg-blue-50 shadow-sm" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <form action="{{ route('admin.bus_companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this bus company?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 bg-red-50 border border-red-100 transition p-1.5 rounded-md hover:bg-red-100 shadow-sm" title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-400 font-medium">
                                    <i data-lucide="building" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                    No bus companies registered yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-between items-center">
                    <div class="text-xs text-gray-500 font-medium">
                        Showing {{ $busCompanies->firstItem() ?? 0 }} to {{ $busCompanies->lastItem() ?? 0 }} of {{ $busCompanies->total() }} companies
                    </div>
                    <div>
                        {{ $busCompanies->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Add New Modal -->
    <div x-show="isAddOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isAddOpen" @click="isAddOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isAddOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form action="{{ route('admin.bus_companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5 border-b pb-3">
                            <h3 class="text-xl leading-6 font-extrabold text-gray-900" id="modal-title">Add New Bus Company</h3>
                            <button type="button" @click="isAddOpen = false" class="text-gray-400 hover:text-gray-500">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
                            <!-- Basic Details -->
                            <div class="col-span-1 md:col-span-2" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Basic Information</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Company Name *</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Company Code</label>
                                <input type="text" name="company_code" value="{{ old('company_code') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Registration Number</label>
                                <input type="text" name="registration_number" value="{{ old('registration_number') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">License Number</label>
                                <input type="text" name="license_number" value="{{ old('license_number') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>

                            <!-- Contact Details -->
                            <div class="col-span-1 md:col-span-2 mt-4" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Contact Information</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Contact Person (Owner Name) *</label>
                                <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number (Numbers only) *</label>
                                <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">WhatsApp Number (Numbers only)</label>
                                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Telephone (Numbers only)</label>
                                <input type="text" name="telephone" value="{{ old('telephone') }}" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Website</label>
                                <input type="url" name="website" value="{{ old('website') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            
                            <div class="col-span-1 md:col-span-2" style="grid-column: 1 / -1;">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Address</label>
                                <textarea name="address" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">{{ old('address') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">District</label>
                                <input type="text" name="district" value="{{ old('district') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>

                            <!-- Financial Details -->
                            <div class="col-span-1 md:col-span-2 mt-4" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Financial Information</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Commission Per Seat (LKR) *</label>
                                <input type="number" step="0.01" name="commission_per_seat" value="{{ old('commission_per_seat', '0') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            
                            <!-- Bank Details Separated -->
                            <div class="col-span-1 md:col-span-2 mt-4 bg-gray-50 border border-gray-200 p-4 rounded-xl shadow-inner" style="grid-column: 1 / -1;">
                                <h5 class="font-bold text-dark-maroon text-base mb-4 flex items-center border-b border-gray-200 pb-2">
                                    <i data-lucide="landmark" class="w-5 h-5 mr-2"></i> Bank Details
                                </h5>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Bank Name</label>
                                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Branch Name</label>
                                        <input type="text" name="branch_name" value="{{ old('branch_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Name</label>
                                        <input type="text" name="account_name" value="{{ old('account_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Number</label>
                                        <input type="text" name="account_number" value="{{ old('account_number') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Branch Code (IFSC/Sort Code)</label>
                                        <input type="text" name="branch_code" value="{{ old('branch_code') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">SWIFT Code</label>
                                        <input type="text" name="swift_code" value="{{ old('swift_code') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Settings -->
                            <div class="col-span-1 md:col-span-2 mt-4" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Additional Settings</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Logo <span class="text-gray-400 font-normal">(Max 5MB, Auto-converted to WebP)</span></label>
                                <input type="file" name="logo" accept="image/*" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Status *</label>
                                <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-span-1 md:col-span-2" style="grid-column: 1 / -1;">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">{{ old('description') }}</textarea>
                            </div>

                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                            Save Company
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
            <div x-show="isEditOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form :action="`{{ url('/admin/bus-companies') }}/${editData.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5 border-b pb-3">
                            <h3 class="text-xl leading-6 font-extrabold text-gray-900" id="modal-title">Edit Bus Company</h3>
                            <button type="button" @click="isEditOpen = false" class="text-gray-400 hover:text-gray-500">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
                            <!-- Basic Details -->
                            <div class="col-span-1 md:col-span-2" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Basic Information</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Company Name *</label>
                                <input type="text" name="company_name" x-model="editData.company_name" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Company Code</label>
                                <input type="text" name="company_code" x-model="editData.company_code" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Registration Number</label>
                                <input type="text" name="registration_number" x-model="editData.registration_number" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">License Number</label>
                                <input type="text" name="license_number" x-model="editData.license_number" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>

                            <!-- Contact Details -->
                            <div class="col-span-1 md:col-span-2 mt-4" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Contact Information</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Contact Person (Owner Name) *</label>
                                <input type="text" name="contact_person" x-model="editData.contact_person" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number (Numbers only) *</label>
                                <input type="text" name="mobile_number" x-model="editData.mobile_number" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">WhatsApp Number (Numbers only)</label>
                                <input type="text" name="whatsapp_number" x-model="editData.whatsapp_number" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Telephone (Numbers only)</label>
                                <input type="text" name="telephone" x-model="editData.telephone" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" x-model="editData.email" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Website</label>
                                <input type="url" name="website" x-model="editData.website" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            
                            <div class="col-span-1 md:col-span-2" style="grid-column: 1 / -1;">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Address</label>
                                <textarea name="address" x-model="editData.address" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">City</label>
                                <input type="text" name="city" x-model="editData.city" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">District</label>
                                <input type="text" name="district" x-model="editData.district" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>

                            <!-- Financial Details -->
                            <div class="col-span-1 md:col-span-2 mt-4" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Financial Information</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Commission Per Seat (LKR) *</label>
                                <input type="number" step="0.01" name="commission_per_seat" x-model="editData.commission_per_seat" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                            </div>
                            
                            <!-- Bank Details Separated -->
                            <div class="col-span-1 md:col-span-2 mt-4 bg-gray-50 border border-gray-200 p-4 rounded-xl shadow-inner" style="grid-column: 1 / -1;">
                                <h5 class="font-bold text-dark-maroon text-base mb-4 flex items-center border-b border-gray-200 pb-2">
                                    <i data-lucide="landmark" class="w-5 h-5 mr-2"></i> Bank Details
                                </h5>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Bank Name</label>
                                        <input type="text" name="bank_name" x-model="editData.bank_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Branch Name</label>
                                        <input type="text" name="branch_name" x-model="editData.branch_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Name</label>
                                        <input type="text" name="account_name" x-model="editData.account_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Number</label>
                                        <input type="text" name="account_number" x-model="editData.account_number" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Branch Code (IFSC/Sort Code)</label>
                                        <input type="text" name="branch_code" x-model="editData.branch_code" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">SWIFT Code</label>
                                        <input type="text" name="swift_code" x-model="editData.swift_code" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Settings -->
                            <div class="col-span-1 md:col-span-2 mt-4" style="grid-column: 1 / -1;">
                                <h4 class="font-bold text-primary-maroon border-b pb-1 mb-3">Additional Settings</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-2">Logo <span class="text-gray-400 font-normal">(Max 5MB, Auto-converted to WebP)</span></label>
                                <div class="mb-3 flex items-center space-x-4" x-show="editData.logo">
                                    <div class="relative">
                                        <img :src="editData.logo ? '/storage/' + editData.logo : ''" class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-sm" alt="Current Logo">
                                        <div class="absolute -top-2 -right-2 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md shadow-sm">Current</div>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Upload a new image below <br>to replace the current logo.
                                    </div>
                                </div>
                                <input type="file" name="logo" accept="image/*" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Status *</label>
                                <select name="status" x-model="editData.status" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-span-1 md:col-span-2" style="grid-column: 1 / -1;">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                                <textarea name="description" x-model="editData.description" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
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

    <!-- View Modal -->
    <div x-show="isViewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isViewOpen" @click="isViewOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isViewOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-gray-50 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full" style="max-width: 800px;">
                
                <!-- Modal Header (Profile Style) -->
                <div class="bg-white border-b border-gray-100 px-6 py-6 sm:px-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-primary-maroon/5 rounded-full blur-2xl"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div class="flex items-center space-x-5">
                            <div class="w-16 h-16 bg-primary-maroon/10 rounded-2xl flex items-center justify-center border border-primary-maroon/20 shrink-0 shadow-sm">
                                <i data-lucide="building-2" class="w-8 h-8 text-primary-maroon"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-2xl font-extrabold text-gray-900" x-text="viewData.company_name"></h3>
                                    <span x-show="viewData.status == 1 || viewData.status === true" class="text-green-700 bg-green-100 border border-green-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">Active</span>
                                    <span x-show="viewData.status == 0 || viewData.status === false" class="text-red-700 bg-red-100 border border-red-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">Inactive</span>
                                </div>
                                <div class="text-sm font-medium text-gray-500 flex items-center">
                                    <i data-lucide="hash" class="w-4 h-4 mr-1 opacity-70"></i> Code: <span class="ml-1" x-text="viewData.company_code || 'N/A'"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="isViewOpen = false" class="text-gray-400 hover:text-gray-700 bg-gray-50 hover:bg-gray-200 p-2 rounded-full transition outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Cards) -->
                <div class="px-6 py-6 sm:px-8 max-h-[65vh] overflow-y-auto">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Contact Info Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 flex items-center border-b border-gray-50 pb-2">
                                <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-500"></i> Contact Information
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Contact Person</span>
                                    <div class="font-bold text-gray-800 text-sm mt-0.5" x-text="viewData.contact_person"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Mobile</span>
                                        <div class="font-bold text-gray-800 text-sm mt-0.5 flex items-center">
                                            <i data-lucide="phone" class="w-3 h-3 mr-1.5 text-gray-400"></i> <span x-text="viewData.mobile_number"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">WhatsApp</span>
                                        <div class="font-bold text-gray-800 text-sm mt-0.5 flex items-center">
                                            <i data-lucide="message-circle" class="w-3 h-3 mr-1.5 text-green-500"></i> <span x-text="viewData.whatsapp_number || 'N/A'"></span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Email</span>
                                    <div class="font-bold text-blue-600 text-sm mt-0.5" x-text="viewData.email || 'N/A'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 flex items-center border-b border-gray-50 pb-2">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-red-500"></i> Location Details
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Address</span>
                                    <div class="font-bold text-gray-800 text-sm mt-0.5 leading-relaxed" x-text="viewData.address || 'N/A'"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">City</span>
                                        <div class="font-bold text-gray-800 text-sm mt-0.5" x-text="viewData.city || 'N/A'"></div>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">District</span>
                                        <div class="font-bold text-gray-800 text-sm mt-0.5" x-text="viewData.district || 'N/A'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financials Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 md:col-span-2">
                            <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 flex items-center border-b border-gray-50 pb-2">
                                <i data-lucide="landmark" class="w-4 h-4 mr-2 text-primary-gold"></i> Financial & Bank Information
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="bg-red-50 rounded-xl p-4 border border-red-100 flex flex-col justify-center shadow-sm">
                                    <span class="block text-[10px] font-bold text-red-400 uppercase">Commission Per Seat</span>
                                    <div class="font-extrabold text-red-600 text-xl mt-1">LKR <span x-text="viewData.commission_per_seat"></span></div>
                                </div>
                                
                                <div class="sm:col-span-2 grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Bank Name</span>
                                        <div class="font-bold text-gray-800 text-sm mt-0.5" x-text="viewData.bank_name || 'N/A'"></div>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Branch</span>
                                        <div class="font-bold text-gray-800 text-sm mt-0.5" x-text="viewData.branch_name || 'N/A'"></div>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Account Number</span>
                                        <div class="font-mono font-bold text-gray-900 text-sm mt-0.5 bg-gray-50 inline-block px-3 py-1.5 rounded-lg border border-gray-200" x-text="viewData.account_number || 'N/A'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-white px-6 py-4 border-t border-gray-100 flex justify-end">
                    <button type="button" @click="isViewOpen = false" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-bold rounded-xl px-6 py-2.5 text-sm transition shadow-sm">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
