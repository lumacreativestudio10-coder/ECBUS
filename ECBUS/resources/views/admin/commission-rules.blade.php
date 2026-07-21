@extends('layouts.admin')

@section('title', 'Commission Rules')
@section('header', 'Commission Rules')

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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8" x-data="{ open: false }">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
        <h3 class="font-extrabold text-lg text-dark-text">Add Commission Rule</h3>
        <button @click="open = !open" class="text-primary-maroon hover:text-dark-maroon font-bold text-sm transition flex items-center">
            <span x-text="open ? 'Close' : 'Add Rule'"></span>
            <i data-lucide="plus" class="w-4 h-4 ml-1" x-show="!open"></i>
            <i data-lucide="minus" class="w-4 h-4 ml-1" x-show="open" style="display: none;"></i>
        </button>
    </div>
    
    <div x-show="open" x-transition class="p-6" style="display: none;">
        <form action="{{ route('admin.commission_rules.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Booking Source</label>
                    <select name="booking_source" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                        <option value="website">Website</option>
                        <option value="counter">Counter</option>
                        <option value="staff">Staff</option>
                        <option value="phone">Phone</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Commission Type</label>
                    <select name="type" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (LKR)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Value</label>
                    <input type="number" step="0.01" name="value" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" placeholder="e.g. 5 or 100">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-primary-maroon text-white font-bold rounded-lg px-6 py-2.5 hover:bg-dark-maroon transition shadow-md">Save Rule</button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">Current Rules</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Booking Source</th>
                    <th class="px-6 py-4 font-bold">Type</th>
                    <th class="px-6 py-4 font-bold">Value</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($rules as $rule)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-dark-text uppercase">{{ $rule->booking_source }}</div>
                    </td>
                    <td class="px-6 py-4 capitalize font-medium text-gray-600">
                        {{ $rule->type }}
                    </td>
                    <td class="px-6 py-4 font-bold text-primary-maroon">
                        {{ $rule->type === 'percentage' ? $rule->value . '%' : 'LKR ' . number_format($rule->value, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.commission_rules.toggle', $rule) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $rule->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $rule->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right space-x-3" x-data="{ editOpen: false }">
                        <button @click="editOpen = true" class="text-gray-400 hover:text-blue-600 transition" title="Edit">
                            <i data-lucide="edit" class="w-4 h-4 inline"></i>
                        </button>
                        
                        <form action="{{ route('admin.commission_rules.destroy', $rule) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this rule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div x-show="editOpen" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                                    <div class="absolute inset-0 bg-dark-maroon/50 backdrop-blur-sm"></div>
                                </div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                <div x-show="editOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                    <form action="{{ route('admin.commission_rules.update', $rule) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                            <h3 class="font-extrabold text-lg text-dark-text">Edit Rule</h3>
                                            <button type="button" @click="editOpen = false" class="text-gray-400 hover:text-red-500 transition">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                        <div class="p-6 space-y-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1">Booking Source</label>
                                                <select name="booking_source" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                    <option value="website" {{ $rule->booking_source == 'website' ? 'selected' : '' }}>Website</option>
                                                    <option value="counter" {{ $rule->booking_source == 'counter' ? 'selected' : '' }}>Counter</option>
                                                    <option value="staff" {{ $rule->booking_source == 'staff' ? 'selected' : '' }}>Staff</option>
                                                    <option value="phone" {{ $rule->booking_source == 'phone' ? 'selected' : '' }}>Phone</option>
                                                    <option value="admin" {{ $rule->booking_source == 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1">Commission Type</label>
                                                <select name="type" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                    <option value="percentage" {{ $rule->type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                    <option value="fixed" {{ $rule->type == 'fixed' ? 'selected' : '' }}>Fixed Amount (LKR)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1">Value</label>
                                                <input type="number" step="0.01" name="value" value="{{ $rule->value }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                                            <button type="button" @click="editOpen = false" class="px-4 py-2.5 text-sm font-bold text-gray-600 hover:text-dark-text transition">Cancel</button>
                                            <button type="submit" class="bg-primary-maroon text-white font-bold rounded-lg px-4 py-2.5 hover:bg-dark-maroon transition shadow-md">Update Rule</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                        <i data-lucide="alert-circle" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p class="font-medium text-sm">No commission rules configured yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
