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
            'passenger_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'passenger_count' => 'required|integer|min:1'
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        // For now, since we don't have a frontend seat selector, 
        // we'll just book the number of seats requested without assigning specific seat numbers yet,
        // or we could assign empty array [] for seat_numbers.
        
        $booking = Booking::create([
            'schedule_id' => $schedule->id,
            'passenger_name' => $request->passenger_name,
            'phone' => $request->phone,
            'passenger_count' => $request->passenger_count,
            'seat_numbers' => [], // To be assigned later by Admin or via future seat map
            'total_amount' => $schedule->price * $request->passenger_count,
            'status' => 'Confirmed' // Assuming auto-confirm for now
        ]);

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
}
