@extends('layouts.admin')

@section('title', 'Edit Booking')
@section('header', 'Edit Booking: ' . ($booking->booking_reference ?? '#'.$booking->id))

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-gray-500 hover:text-primary-maroon font-bold text-sm transition flex items-center">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to Booking
    </a>
</div>

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <ul class="list-disc list-inside font-bold text-sm">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 max-w-3xl mx-auto">
    <h3 class="font-extrabold text-lg text-dark-text border-b border-gray-100 pb-4 mb-6">Update Passenger Details</h3>
    
    <form action="{{ route('admin.bookings.updateDetails', $booking) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" required value="{{ old('customer_name', $booking->customer_name) }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:border-primary-maroon focus:ring-primary-maroon outline-none transition text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                <input type="text" name="phone" required value="{{ old('phone', $booking->phone) }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:border-primary-maroon focus:ring-primary-maroon outline-none transition text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Boarding Point</label>
                    <input type="text" name="boarding_point" value="{{ old('boarding_point', $booking->boarding_point) }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:border-primary-maroon focus:ring-primary-maroon outline-none transition text-sm" placeholder="e.g. Jaffna Bus Stand">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Dropping Point</label>
                    <input type="text" name="dropping_point" value="{{ old('dropping_point', $booking->dropping_point) }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:border-primary-maroon focus:ring-primary-maroon outline-none transition text-sm" placeholder="e.g. Mulliyawalai">
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-lg text-xs flex">
                <i data-lucide="info" class="w-5 h-5 mr-2 shrink-0"></i>
                <p>Note: To change assigned seats or the bus schedule, you must use the Manage Seats interface or cancel this booking and create a new one.</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4 border-t border-gray-100 pt-6">
            <a href="{{ route('admin.bookings.show', $booking) }}" class="px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-primary-maroon text-white font-bold rounded-lg hover:bg-dark-maroon transition shadow-md">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
