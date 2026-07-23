@extends('layouts.admin')

@section('title', 'Trip Financial Summary')
@section('header', 'Trip Financial Summary')

@section('content')
<div class="mb-6">
    <a href="{{ route(auth()->user()->getRolePrefix().'.schedules') }}" class="inline-flex items-center text-primary-maroon font-bold hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to Schedules
    </a>
</div>

<!-- Trip Header -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
    <div>
        <span class="bg-primary-gold/20 text-dark-maroon text-xs px-3 py-1 rounded-full font-bold uppercase">
            {{ $schedule->bus->busCompany->company_name ?? 'N/A' }}
        </span>
        <h2 class="text-2xl font-extrabold text-dark-text mt-2">
            {{ $schedule->route?->fromLocation?->name ?? '?' }} &rarr; {{ $schedule->route?->toLocation?->name ?? '?' }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i> {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
            &bull;
            <i data-lucide="clock" class="w-4 h-4 inline mr-1"></i> {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
        </p>
    </div>
    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-primary-maroon text-white rounded-xl flex items-center justify-center">
            <i data-lucide="bus" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-bold uppercase">Bus Assigned</p>
            <p class="font-extrabold text-dark-text">{{ $schedule->bus->name }} ({{ $schedule->bus->bus_number }})</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Revenue</p>
        <h3 class="text-3xl font-extrabold text-dark-text">LKR {{ number_format($totalRevenue, 2) }}</h3>
        <p class="text-xs text-gray-500 mt-2 font-semibold">{{ $totalPassengers }} seats booked</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Commission</p>
        <h3 class="text-3xl font-extrabold text-red-600">LKR {{ number_format($totalCommission, 2) }}</h3>
        <p class="text-xs text-gray-500 mt-2 font-semibold">Payable to Admin</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Net Company Revenue</p>
        <h3 class="text-3xl font-extrabold text-green-600">LKR {{ number_format($netRevenue, 2) }}</h3>
        <p class="text-xs text-gray-500 mt-2 font-semibold">Revenue minus Commission</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Collected / Pending</p>
        <h3 class="text-xl font-extrabold text-dark-text">LKR {{ number_format($totalPaid, 2) }}</h3>
        <p class="text-xs text-red-500 mt-1 font-bold">Unpaid: LKR {{ number_format($totalPending, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Booking Sources -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
        <div>
            <h3 class="font-extrabold text-lg text-dark-text mb-6">Booking Sources</h3>
            <div class="space-y-4">
                @foreach(['website' => 'Website', 'admin' => 'Admin Panel', 'staff' => 'Staff Panel', 'counter' => 'Counter / Walkin'] as $key => $label)
                    @php
                        $data = $bySource->get($key, ['count' => 0, 'revenue' => 0, 'passengers' => 0]);
                    @endphp
                    <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                        <div>
                            <p class="font-bold text-dark-text">{{ $label }}</p>
                            <p class="text-xs text-gray-400">{{ $data['count'] }} bookings ({{ $data['passengers'] }} seats)</p>
                        </div>
                        <div class="text-right">
                            <p class="font-extrabold text-dark-maroon">LKR {{ number_format($data['revenue'], 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Booking Details List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-dark-text">Bookings Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Ref</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Source</th>
                        <th class="px-6 py-4">Revenue</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($bookings as $booking)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-dark-text">{{ $booking->booking_reference }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-dark-text">{{ $booking->customer_name }}</p>
                                <p class="text-xs text-gray-400">{{ $booking->phone }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold uppercase">
                                    {{ $booking->booking_source }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-extrabold text-dark-maroon">LKR {{ number_format($booking->total_amount, 2) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->booking_status == 'confirmed')
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">Confirmed</span>
                                @elseif($booking->booking_status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-xs font-bold">Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No bookings made for this trip.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
