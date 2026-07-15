@extends('layouts.admin')

@section('title', 'Manage Bookings')
@section('header', 'Manage Bookings')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">All Bookings</h3>
        <div class="relative">
            <i data-lucide="search" class="w-5 h-5 absolute left-3 top-2.5 text-gray-400"></i>
            <input type="text" placeholder="Search bookings..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-primary-maroon focus:border-primary-maroon text-sm outline-none">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Passenger Details</th>
                    <th class="px-6 py-4">Route & Bus</th>
                    <th class="px-6 py-4">Payment</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($bookings as $booking)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-dark-text">#{{ $booking->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-dark-text">{{ $booking->passenger_name }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->phone_number }}</p>
                        <p class="text-xs text-gray-400 mt-1"><i data-lucide="users" class="w-3 h-3 inline"></i> {{ $booking->passenger_count }} Passengers</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-dark-text">Booking #{{ $booking->id }}</div>
                        <p class="font-bold text-dark-text">{{ $booking->schedule?->route?->fromLocation?->name }} &rarr; {{ $booking->schedule?->route?->toLocation?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->schedule?->date }} | {{ $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') : '' }}</p>
                        <span class="inline-block bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold mt-1 uppercase">{{ $booking->schedule?->bus?->operator?->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-extrabold text-dark-maroon">LKR {{ number_format($booking->total_amount, 2) }}</p>
                        @if($booking->payment_receipt)
                            <a href="{{ Storage::url($booking->payment_receipt) }}" target="_blank" class="text-xs text-primary-maroon font-bold hover:underline flex items-center mt-1">
                                <i data-lucide="file-image" class="w-3 h-3 mr-1"></i> View Receipt
                            </a>
                        @else
                            <span class="text-xs text-gray-400">No Receipt</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-full px-3 py-1 outline-none border border-gray-200 cursor-pointer hover:bg-gray-50 {{ $booking->status == 'Confirmed' ? 'text-green-700 bg-green-50' : ($booking->status == 'Pending' ? 'text-yellow-700 bg-yellow-50' : 'text-red-700 bg-red-50') }}">
                                <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Confirmed" {{ $booking->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-gray-400 hover:text-dark-maroon transition p-2 rounded-lg hover:bg-gray-100">
                            <i data-lucide="more-vertical" class="w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No bookings found in the system.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
