@extends('layouts.admin')

@section('title', 'Commission Rules')
@section('header', 'Commission Rules')

@section('content')

<div class="mb-6 bg-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
    <i data-lucide="info" class="w-5 h-5 mr-3"></i>
    <span class="block sm:inline text-sm font-bold">These are the active commission rules configured by the system administrator. They apply to all bookings according to their source.</span>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">Active Commission Rules</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Booking Source</th>
                    <th class="px-6 py-4 font-bold">Type</th>
                    <th class="px-6 py-4 font-bold text-right">Value</th>
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
                    <td class="px-6 py-4 font-extrabold text-primary-maroon text-right">
                        {{ $rule->type === 'percentage' ? $rule->value . '%' : 'LKR ' . number_format($rule->value, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                        <i data-lucide="shield-alert" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p class="font-medium text-sm">No active commission rules are currently configured.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
