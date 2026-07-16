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
                    .custom-filters-right { display: flex; flex-wrap: wrap; gap: 12px; width: 100%; align-items: flex-end; }
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

                <form action="{{ route(auth()->user()->getRolePrefix().'.bus_companies') }}" method="GET" class="custom-filter-container">
                    
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
                                <select name="status" onchange="this.form.submit()" style="color-scheme: light;" class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-black focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition bg-white cursor-pointer shadow-sm appearance-none">
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
                                <select name="district" onchange="this.form.submit()" style="color-scheme: light;" class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2.5 text-sm text-black focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition bg-white cursor-pointer shadow-sm appearance-none">
                                    <option value="">All Districts</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute pointer-events-none" style="right: 12px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="custom-btn-group">
                            <button type="submit" class="custom-btn bg-primary-maroon hover:bg-dark-maroon text-white font-bold rounded-lg px-5 py-2.5 text-sm transition flex items-center shadow-md">
                                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
                            </button>
                            <a href="{{ route(auth()->user()->getRolePrefix().'.bus_companies') }}" class="custom-btn bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-lg px-4 py-2.5 text-sm transition flex items-center shadow-sm" title="Reset Filters">
                                <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 bg-white flex justify-between items-center">
                    <h3 class="font-extrabold text-lg text-dark-text">Registered Bus Companies</h3>
                    <button @click="isAddOpen = true" class="bg-primary-maroon hover:bg-dark-maroon text-white font-bold rounded-lg px-4 py-2 text-sm transition flex items-center shadow-md">
                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add Company
                    </button>
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
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openView({{ json_encode($company) }})" type="button" class="text-gray-400 hover:text-blue-600 transition p-1" title="View">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </button>
                                        <button @click="openEdit({{ json_encode($company) }})" type="button" class="text-gray-400 hover:text-green-600 transition p-1" title="Edit">
                                            <i data-lucide="edit" class="w-5 h-5"></i>
                                        </button>
                                        <form action="{{ route(auth()->user()->getRolePrefix().'.bus_companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this bus company?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition p-1" title="Delete">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
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
                <form action="{{ route(auth()->user()->getRolePrefix().'.bus_companies.store') }}" method="POST" enctype="multipart/form-data">
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
                <form :action="`{{ url('/'.auth()->user()->getRolePrefix().'/bus-companies') }}/${editData.id}`" method="POST" enctype="multipart/form-data">
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

    <!-- Premium View Modal -->
    <div x-show="isViewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <style>
            .pm-modal { font-family: 'Inter', sans-serif; }
            .pm-header { padding: 32px; background: #fff; border-bottom: 1px solid #f3f4f6; position: relative; overflow: hidden; }
            .pm-logo-box { width: 80px; height: 80px; background: #f9fafb; border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 1px solid #f3f4f6; flex-shrink: 0; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); overflow: hidden; position: relative; }
            .pm-title { font-size: 32px; line-height: 1; font-weight: 800; color: #111827; letter-spacing: -0.025em; margin-bottom: 8px; margin-top: 0; }
            .pm-badge-active { color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 6px; }
            .pm-badge-inactive { color: #b91c1c; background: #fef2f2; border: 1px solid #fecaca; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 6px; }
            .pm-dot { width: 6px; height: 6px; border-radius: 50%; }
            .pm-dot-green { background-color: #22c55e; }
            .pm-dot-red { background-color: #ef4444; }
            .pm-meta-text { font-size: 14px; font-weight: 500; color: #6b7280; display: flex; align-items: center; gap: 16px; }
            .pm-body { padding: 32px; max-height: 70vh; overflow-y: auto; background: #fafafa; }
            .pm-grid-layout { display: grid; gap: 24px; grid-template-columns: 2fr 1fr; }
            .pm-split-grid { display: grid; gap: 24px; grid-template-columns: 1fr 1fr; }
            .pm-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 10px -4px rgba(0,0,0,0.05); border: 1px solid rgba(243,244,246,0.8); padding: 24px; transition: all 0.2s; }
            .pm-card:hover { box-shadow: 0 4px 20px -4px rgba(0,0,0,0.08); }
            .pm-card-title { font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 20px; margin-top: 0; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f9fafb; padding-bottom: 8px; }
            .pm-label { display: block; font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
            .pm-value { font-weight: 600; color: #1f2937; font-size: 14px; line-height: 1.5; }
            .pm-value-mono { font-family: monospace; font-weight: 700; color: #374151; font-size: 15px; background: #f9fafb; padding: 6px 12px; border-radius: 8px; border: 1px solid #f3f4f6; display: inline-flex; align-items: center; justify-content: space-between; width: 100%; box-sizing: border-box; }
            
            .pm-stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
            .pm-stat-card { padding: 16px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid rgba(0,0,0,0.05); min-height: 100px; }
            .pm-stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
            .pm-stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.7; }
            .pm-stat-value { font-size: 24px; font-weight: 900; }
            
            .pm-highlight-card { background: linear-gradient(to right, #8B0000, #5a0000); border-radius: 12px; padding: 20px; color: white; box-shadow: 0 10px 15px -3px rgba(139,0,0,0.2); margin-bottom: 20px; position: relative; overflow: hidden; }
            .pm-highlight-label { display: block; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; position: relative; z-index: 10; }
            .pm-highlight-value { font-weight: 800; font-size: 30px; position: relative; z-index: 10; display: flex; align-items: baseline; gap: 6px; }
            
            .pm-doc-item { display: flex; align-items: center; justify-content: space-between; padding: 12px; border-radius: 12px; border: 1px solid #f3f4f6; transition: all 0.2s; cursor: pointer; margin-bottom: 12px; }
            .pm-doc-item:last-child { margin-bottom: 0; }
            .pm-doc-item:hover { border-color: #e5e7eb; background: #f9fafb; }
            .pm-doc-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 12px; }
            .pm-doc-actions { display: flex; gap: 4px; opacity: 0; transition: opacity 0.2s; }
            .pm-doc-item:hover .pm-doc-actions { opacity: 1; }
            .pm-doc-btn { padding: 6px; color: #9ca3af; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
            .pm-doc-btn:hover { color: #111827; background: #f3f4f6; }
            
            .pm-footer { background: #fff; padding: 20px 32px; border-top: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; border-radius: 0 0 18px 18px; }
            .pm-footer-buttons { display: flex; gap: 12px; }
            .pm-footer-btn { padding: 10px 24px; font-size: 14px; font-weight: 700; border-radius: 12px; transition: all 0.2s; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; }
            .pm-btn-primary { background: #8B0000; color: white; box-shadow: 0 4px 6px -1px rgba(139,0,0,0.1); }
            .pm-btn-primary:hover { background: #6b0000; box-shadow: 0 10px 15px -3px rgba(139,0,0,0.2); }
            .pm-btn-secondary { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
            .pm-btn-secondary:hover { background: #f9fafb; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }

            /* Utility classes to avoid Tailwind dependency */
            .pm-flex { display: flex; }
            .pm-items-center { align-items: center; }
            .pm-justify-between { justify-content: space-between; }
            .pm-gap-2 { gap: 8px; }
            .pm-gap-4 { gap: 16px; }
            .pm-gap-6 { gap: 24px; }
            .pm-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
            .pm-mb-4 { margin-bottom: 16px; }
            .pm-mt-2 { margin-top: 8px; }
            
            @media (max-width: 1024px) {
                .pm-grid-layout { grid-template-columns: 1fr; }
            }
            @media (max-width: 768px) {
                .pm-split-grid { grid-template-columns: 1fr; }
                .pm-stat-grid { grid-template-columns: repeat(2, 1fr); }
                .pm-footer { flex-direction: column; gap: 16px; align-items: flex-start; }
                .pm-footer-buttons { width: 100%; display: flex; gap: 12px; }
                .pm-footer-btn { flex: 1; }
            }
        </style>

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isViewOpen" @click="isViewOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isViewOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#ffffff] text-left overflow-hidden shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] transform transition-all sm:my-8 sm:align-middle w-full pm-modal" style="max-width: 1200px; border-radius: 18px;">
                
                <!-- Premium Header -->
                <div class="pm-header">
                    <div class="pm-flex pm-justify-between" style="align-items: flex-start; position: relative; z-index: 10;">
                        <div class="pm-flex pm-items-center pm-gap-6">
                            <!-- Logo -->
                            <div class="pm-logo-box">
                                <img x-show="viewData.logo" :src="'/storage/' + viewData.logo" style="width:100%; height:100%; object-fit:cover;" alt="Logo">
                                <div x-show="!viewData.logo" class="pm-flex pm-items-center" style="justify-content:center; width:100%; height:100%; background:rgba(139,0,0,0.05);">
                                    <i data-lucide="building-2" style="width:40px; height:40px; color:#8B0000; opacity:0.8;"></i>
                                </div>
                            </div>
                            <!-- Title & Badges -->
                            <div>
                                <div class="pm-flex pm-items-center pm-gap-4" style="margin-bottom: 8px;">
                                    <h3 class="pm-title" x-text="viewData.company_name"></h3>
                                    <span x-show="viewData.status == 1 || viewData.status === true" class="pm-badge-active">
                                        <span class="pm-dot pm-dot-green"></span> Active
                                    </span>
                                    <span x-show="viewData.status == 0 || viewData.status === false" class="pm-badge-inactive">
                                        <span class="pm-dot pm-dot-red"></span> Inactive
                                    </span>
                                </div>
                                <div class="pm-meta-text">
                                    <span class="pm-flex pm-items-center pm-gap-2"><i data-lucide="hash" style="width:16px; height:16px; opacity:0.6;"></i> Code: <strong style="color:#374151; margin-left:4px;" x-text="viewData.company_code || 'N/A'"></strong></span>
                                    <span style="color:#d1d5db;">|</span>
                                    <span class="pm-flex pm-items-center pm-gap-2"><i data-lucide="calendar" style="width:16px; height:16px; opacity:0.6;"></i> Joined: <strong style="color:#374151; margin-left:4px;" x-text="viewData.created_at ? new Date(viewData.created_at).toLocaleDateString() : 'N/A'"></strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pm-flex pm-items-center pm-gap-2">
                            <button type="button" @click="isViewOpen = false; openEdit(viewData)" class="pm-doc-btn" style="padding: 8px 16px; font-weight: bold; border-radius: 10px;">
                                <i data-lucide="edit-3" style="width:16px; height:16px; margin-right:8px;"></i> Edit
                            </button>
                            <button type="button" @click="isViewOpen = false" class="pm-doc-btn" style="padding: 8px; border-radius: 10px; border-color: transparent;">
                                <i data-lucide="x" style="width:20px; height:20px;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Body (Bento Grid) -->
                <div class="pm-body">
                    
                    <div class="pm-grid-layout">
                        
                        <!-- Left Column (General Info) -->
                        <div style="display: flex; flex-direction: column; gap: 24px;">
                            
                            <!-- Section 1 & 2: Company & Contact (Split Grid) -->
                            <div class="pm-split-grid">
                                <!-- Company Information -->
                                <div class="pm-card">
                                    <h4 class="pm-card-title">
                                        <i data-lucide="building" style="width:16px; height:16px; color:#8B0000; opacity:0.8;"></i> Company Information
                                    </h4>
                                    <div>
                                        <div class="pm-grid-2 pm-mb-4">
                                            <div>
                                                <span class="pm-label">Company Name</span>
                                                <div class="pm-value" x-text="viewData.company_name"></div>
                                            </div>
                                            <div>
                                                <span class="pm-label">Company Code</span>
                                                <div class="pm-value" x-text="viewData.company_code || 'N/A'"></div>
                                            </div>
                                        </div>
                                        <div class="pm-grid-2 pm-mb-4">
                                            <div>
                                                <span class="pm-label">Registration No.</span>
                                                <div class="pm-value" style="font-family: monospace;" x-text="viewData.registration_number || 'N/A'"></div>
                                            </div>
                                            <div>
                                                <span class="pm-label">License Number</span>
                                                <div class="pm-value" style="font-family: monospace;" x-text="viewData.license_number || 'N/A'"></div>
                                            </div>
                                        </div>
                                        <div class="pm-grid-2" style="padding-top: 12px; border-top: 1px solid #f9fafb;">
                                            <div>
                                                <span class="pm-label">Owner Name</span>
                                                <div class="pm-value" x-text="viewData.contact_person"></div>
                                            </div>
                                            <div>
                                                <span class="pm-label">Contact Person</span>
                                                <div class="pm-value" x-text="viewData.contact_person"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="pm-card">
                                    <h4 class="pm-card-title">
                                        <i data-lucide="phone-call" style="width:16px; height:16px; color:#3b82f6; opacity:0.8;"></i> Contact Information
                                    </h4>
                                    <div>
                                        <div class="pm-grid-2 pm-mb-4">
                                            <div class="pm-flex pm-gap-2">
                                                <div style="width:32px; height:32px; border-radius:50%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    <i data-lucide="smartphone" style="width:16px; height:16px; color:#6b7280;"></i>
                                                </div>
                                                <div>
                                                    <span class="pm-label">Mobile</span>
                                                    <div class="pm-value" x-text="viewData.mobile_number"></div>
                                                </div>
                                            </div>
                                            <div class="pm-flex pm-gap-2">
                                                <div style="width:32px; height:32px; border-radius:50%; background:#dcfce7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    <i data-lucide="message-circle" style="width:16px; height:16px; color:#22c55e;"></i>
                                                </div>
                                                <div>
                                                    <span class="pm-label">WhatsApp</span>
                                                    <div class="pm-value" x-text="viewData.whatsapp_number || 'N/A'"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pm-flex pm-gap-2 pm-mb-4">
                                            <div style="width:32px; height:32px; border-radius:50%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                <i data-lucide="phone" style="width:16px; height:16px; color:#6b7280;"></i>
                                            </div>
                                            <div>
                                                <span class="pm-label">Office Number</span>
                                                <div class="pm-value" x-text="viewData.telephone || 'N/A'"></div>
                                            </div>
                                        </div>
                                        <div class="pm-grid-2" style="padding-top: 12px; border-top: 1px solid #f9fafb;">
                                            <div class="pm-flex pm-gap-2" style="overflow: hidden;">
                                                <div style="width:32px; height:32px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    <i data-lucide="mail" style="width:16px; height:16px; color:#3b82f6;"></i>
                                                </div>
                                                <div style="overflow: hidden;">
                                                    <span class="pm-label">Email</span>
                                                    <div class="pm-value" style="color:#2563eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="viewData.email || 'N/A'"></div>
                                                </div>
                                            </div>
                                            <div class="pm-flex pm-gap-2" style="overflow: hidden;">
                                                <div style="width:32px; height:32px; border-radius:50%; background:#f3e8ff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    <i data-lucide="globe" style="width:16px; height:16px; color:#a855f7;"></i>
                                                </div>
                                                <div style="overflow: hidden;">
                                                    <span class="pm-label">Website</span>
                                                    <div class="pm-value" style="color:#9333ea; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="viewData.website || 'N/A'"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 5: Business Information (Stats) -->
                            <div class="pm-card">
                                <h4 class="pm-card-title">
                                    <i data-lucide="bar-chart-2" style="width:16px; height:16px; color:#6366f1; opacity:0.8;"></i> Business Information
                                </h4>
                                <div class="pm-stat-grid">
                                    <div class="pm-stat-card" style="background: linear-gradient(135deg, #eef2ff, #fff); border-color: #e0e7ff;">
                                        <div class="pm-stat-header">
                                            <span class="pm-stat-label" style="color:#3730a3;">Total Buses</span>
                                            <i data-lucide="bus" style="width:16px; height:16px; color:#818cf8;"></i>
                                        </div>
                                        <div class="pm-stat-value" style="color:#312e81;">32</div>
                                    </div>
                                    <div class="pm-stat-card" style="background: linear-gradient(135deg, #ecfdf5, #fff); border-color: #d1fae5;">
                                        <div class="pm-stat-header">
                                            <span class="pm-stat-label" style="color:#065f46;">Total Routes</span>
                                            <i data-lucide="map" style="width:16px; height:16px; color:#34d399;"></i>
                                        </div>
                                        <div class="pm-stat-value" style="color:#064e3b;">15</div>
                                    </div>
                                    <div class="pm-stat-card" style="background: linear-gradient(135deg, #fffbeb, #fff); border-color: #fef3c7;">
                                        <div class="pm-stat-header">
                                            <span class="pm-stat-label" style="color:#92400e;">Total Drivers</span>
                                            <i data-lucide="users" style="width:16px; height:16px; color:#fbbf24;"></i>
                                        </div>
                                        <div class="pm-stat-value" style="color:#78350f;">45</div>
                                    </div>
                                    <div class="pm-stat-card" style="background: linear-gradient(135deg, #eff6ff, #fff); border-color: #dbeafe;">
                                        <div class="pm-stat-header">
                                            <span class="pm-stat-label" style="color:#1e40af;">Total Bookings</span>
                                            <i data-lucide="ticket" style="width:16px; height:16px; color:#60a5fa;"></i>
                                        </div>
                                        <div class="pm-stat-value" style="color:#1e3a8a;">1,204</div>
                                    </div>
                                    <div class="pm-stat-card" style="background: linear-gradient(135deg, #f0fdf4, #fff); border-color: #dcfce7;">
                                        <div class="pm-stat-header">
                                            <span class="pm-stat-label" style="color:#166534;">Total Revenue</span>
                                            <i data-lucide="wallet" style="width:16px; height:16px; color:#4ade80;"></i>
                                        </div>
                                        <div class="pm-stat-value" style="color:#14532d;">LKR 450K</div>
                                    </div>
                                    <div class="pm-stat-card" style="background: linear-gradient(135deg, #fff7ed, #fff); border-color: #ffedd5;">
                                        <div class="pm-stat-header">
                                            <span class="pm-stat-label" style="color:#9a3412;">Avg Rating</span>
                                            <i data-lucide="star" style="width:16px; height:16px; color:#fb923c; fill: #fb923c;"></i>
                                        </div>
                                        <div class="pm-stat-value" style="color:#7c2d12;">4.8 <span style="font-size:14px; opacity:0.5;">/5</span></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Right Column (Location, Financial, Docs) -->
                        <div style="display: flex; flex-direction: column; gap: 24px;">
                            
                            <!-- Section 4: Financial Information -->
                            <div class="pm-card">
                                <h4 class="pm-card-title">
                                    <i data-lucide="landmark" style="width:16px; height:16px; color:#10b981; opacity:0.8;"></i> Financial Information
                                </h4>
                                
                                <!-- Highlight Card -->
                                <div class="pm-highlight-card">
                                    <span class="pm-highlight-label">Commission Per Seat</span>
                                    <div class="pm-highlight-value">
                                        <span style="font-size:18px; opacity:0.8; font-weight:600;">LKR</span> <span x-text="viewData.commission_per_seat"></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="pm-grid-2 pm-mb-4">
                                        <div>
                                            <span class="pm-label">Bank Name</span>
                                            <div class="pm-value" x-text="viewData.bank_name || 'N/A'"></div>
                                        </div>
                                        <div>
                                            <span class="pm-label">Branch</span>
                                            <div class="pm-value" x-text="viewData.branch_name || 'N/A'"></div>
                                        </div>
                                    </div>
                                    <div class="pm-mb-4">
                                        <span class="pm-label">Account Name</span>
                                        <div class="pm-value" x-text="viewData.account_name || 'N/A'"></div>
                                    </div>
                                    <div>
                                        <span class="pm-label">Account Number</span>
                                        <div class="pm-value-mono">
                                            <span x-text="viewData.account_number || 'N/A'"></span>
                                            <i data-lucide="copy" style="width:16px; height:16px; color:#d1d5db; cursor:pointer;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Location -->
                            <div class="pm-card">
                                <div class="pm-flex pm-justify-between pm-items-center" style="margin-bottom: 20px;">
                                    <h4 class="pm-card-title" style="margin: 0; border: none; padding: 0;">
                                        <i data-lucide="map-pin" style="width:16px; height:16px; color:#ef4444; opacity:0.8;"></i> Location
                                    </h4>
                                    <button style="font-size:10px; font-weight:700; color:#2563eb; background:#eff6ff; padding:4px 10px; border-radius:6px; border:none; display:flex; align-items:center; text-transform:uppercase; letter-spacing:0.05em; cursor:pointer;">
                                        <i data-lucide="map" style="width:12px; height:12px; margin-right:4px;"></i> Maps
                                    </button>
                                </div>
                                <div>
                                    <div class="pm-mb-4">
                                        <span class="pm-label">Address</span>
                                        <div class="pm-value" x-text="viewData.address || 'N/A'"></div>
                                    </div>
                                    <div class="pm-grid-2">
                                        <div style="background:#f9fafb; border-radius:8px; padding:12px; border:1px solid #f3f4f6;">
                                            <span class="pm-label">City</span>
                                            <div class="pm-value" x-text="viewData.city || 'N/A'"></div>
                                        </div>
                                        <div style="background:#f9fafb; border-radius:8px; padding:12px; border:1px solid #f3f4f6;">
                                            <span class="pm-label">District</span>
                                            <div class="pm-value" x-text="viewData.district || 'N/A'"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 6: Documents -->
                            <div class="pm-card">
                                <h4 class="pm-card-title">
                                    <i data-lucide="file-text" style="width:16px; height:16px; color:#f59e0b; opacity:0.8;"></i> Documents
                                </h4>
                                <div>
                                    <!-- Document Item -->
                                    <div class="pm-doc-item">
                                        <div class="pm-flex pm-items-center pm-gap-2">
                                            <div class="pm-doc-icon" style="background:#fef2f2; color:#ef4444;">
                                                <i data-lucide="file-badge" style="width:20px; height:20px;"></i>
                                            </div>
                                            <div>
                                                <div class="pm-value">Business Registration</div>
                                                <div style="font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-top:2px;">PDF • 2.4 MB</div>
                                            </div>
                                        </div>
                                        <div class="pm-doc-actions">
                                            <button class="pm-doc-btn" title="Preview"><i data-lucide="eye" style="width:14px; height:14px;"></i></button>
                                            <button class="pm-doc-btn" title="Download"><i data-lucide="download" style="width:14px; height:14px;"></i></button>
                                        </div>
                                    </div>
                                    <!-- Document Item -->
                                    <div class="pm-doc-item">
                                        <div class="pm-flex pm-items-center pm-gap-2">
                                            <div class="pm-doc-icon" style="background:#eff6ff; color:#3b82f6;">
                                                <i data-lucide="shield-check" style="width:20px; height:20px;"></i>
                                            </div>
                                            <div>
                                                <div class="pm-value">Operating License</div>
                                                <div style="font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-top:2px;">PDF • 1.1 MB</div>
                                            </div>
                                        </div>
                                        <div class="pm-doc-actions">
                                            <button class="pm-doc-btn" title="Preview"><i data-lucide="eye" style="width:14px; height:14px;"></i></button>
                                            <button class="pm-doc-btn" title="Download"><i data-lucide="download" style="width:14px; height:14px;"></i></button>
                                        </div>
                                    </div>
                                    <!-- Document Item -->
                                    <div class="pm-doc-item">
                                        <div class="pm-flex pm-items-center pm-gap-2">
                                            <div class="pm-doc-icon" style="background:#faf5ff; color:#a855f7;">
                                                <i data-lucide="shield" style="width:20px; height:20px;"></i>
                                            </div>
                                            <div>
                                                <div class="pm-value">Insurance Policy</div>
                                                <div style="font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-top:2px;">PDF • 3.0 MB</div>
                                            </div>
                                        </div>
                                        <div class="pm-doc-actions">
                                            <button class="pm-doc-btn" title="Preview"><i data-lucide="eye" style="width:14px; height:14px;"></i></button>
                                            <button class="pm-doc-btn" title="Download"><i data-lucide="download" style="width:14px; height:14px;"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="pm-footer">
                    <!-- Left side footer info -->
                    <div class="pm-meta-text">
                        <div class="pm-flex pm-items-center">
                            <i data-lucide="user" style="width:14px; height:14px; margin-right:6px; opacity:0.7;"></i>
                            Created by: <span style="color:#4b5563; font-weight:700; margin-left:4px;">Admin</span>
                        </div>
                        <div class="pm-flex pm-items-center">
                            <i data-lucide="clock" style="width:14px; height:14px; margin-right:6px; opacity:0.7;"></i>
                            Updated: <span style="color:#4b5563; font-weight:700; margin-left:4px;" x-text="viewData.updated_at ? new Date(viewData.updated_at).toLocaleDateString() : 'Just now'"></span>
                        </div>
                    </div>
                    
                    <!-- Right side buttons -->
                    <div class="pm-footer-buttons">
                        <button type="button" @click="isViewOpen = false" class="pm-footer-btn pm-btn-secondary">
                            Close
                        </button>
                        <button type="button" @click="isViewOpen = false; openEdit(viewData)" class="pm-footer-btn pm-btn-primary">
                            <i data-lucide="edit-2" style="width:16px; height:16px;"></i> Edit Company
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
</div>
@endsection
