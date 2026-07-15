<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'passenger_count' => 'required|integer|min:1',
            'boarding_point' => 'nullable|string|max:255',
            'dropping_point' => 'nullable|string|max:255',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        if ($schedule->available_seats < $request->passenger_count) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough seats available.'
            ], 400);
        }

        $booking = Booking::create([
            'schedule_id' => $schedule->id,
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'passenger_count' => $request->passenger_count,
            'boarding_point' => $request->boarding_point,
            'dropping_point' => $request->dropping_point,
            'seat_numbers' => [], // To be assigned later by Admin or via future seat map
            'total_amount' => $schedule->price * $request->passenger_count,
            'booking_status' => 'pending' // Enquiry status
        ]);

        $schedule->decrement('available_seats', $request->passenger_count);

        return response()->json([
            'success' => true,
            'message' => 'Booking submitted successfully!',
            'booking_id' => $booking->id
        ]);
    }

    public function myBookings(Request $request)
    {
        $phone = $request->get('phone');
        $bookings = collect();

        if ($phone) {
            $bookings = Booking::with(['schedule.route.fromLocation', 'schedule.route.toLocation', 'schedule.bus'])
                ->where('phone', $phone)
                ->latest()
                ->get();
        }

        return view('my-booking', compact('bookings', 'phone'));
    }

    public function downloadTicket(Booking $booking)
    {
        $booking->load('schedule.bus.busCompany', 'schedule.route.fromLocation', 'schedule.route.toLocation');
        return view('ticket_print', compact('booking'));
    }
}
