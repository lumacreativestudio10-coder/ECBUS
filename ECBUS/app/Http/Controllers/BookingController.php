<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function lockSeats(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats' => 'required|array',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        $bookedSeats = Booking::where('schedule_id', $schedule->id)
            ->where('booking_status', '!=', 'cancelled')
            ->pluck('seat_numbers')
            ->flatten()
            ->toArray();

        $lockedSeats = \DB::table('seat_locks')
            ->where('schedule_id', $schedule->id)
            ->where('expires_at', '>', now())
            ->where('session_id', '!=', session()->getId())
            ->pluck('seat_number')
            ->toArray();

        $unavailable = array_intersect($request->seats, array_merge($bookedSeats, $lockedSeats));
        
        if (!empty($unavailable)) {
            return response()->json(['success' => false, 'message' => 'Seats are taken or locked.', 'unavailable' => array_values($unavailable)], 400);
        }

        foreach ($request->seats as $seat) {
            \DB::table('seat_locks')->updateOrInsert(
                ['schedule_id' => $schedule->id, 'seat_number' => $seat],
                ['session_id' => session()->getId(), 'expires_at' => now()->addMinutes(10), 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'Seats locked for 10 minutes.']);
    }

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
            'seats' => 'nullable|array',
            'is_walkin' => 'nullable|boolean'
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        $seats = $request->seats ?? [];

        if ($schedule->available_seats < $request->passenger_count) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough seats available.'
            ], 400);
        }

        $bookingSource = 'website';
        if (auth()->check()) {
            if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2) {
                $bookingSource = 'admin';
            } elseif (auth()->user()->role_id == 3 || auth()->user()->role_id == 4 || auth()->user()->role_id == 5) {
                $bookingSource = 'staff';
            }
        } elseif ($request->is_walkin) {
            $bookingSource = 'counter';
        }

        $booking = Booking::create([
            'schedule_id' => $schedule->id,
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'passenger_count' => $request->passenger_count,
            'boarding_point' => $request->boarding_point,
            'dropping_point' => $request->dropping_point,
            'seat_numbers' => $seats,
            'total_amount' => $schedule->price * $request->passenger_count,
            'booking_status' => $request->is_walkin ? 'confirmed' : 'pending',
            'booking_source' => $bookingSource
        ]);

        if ($bookingSource === 'website') {
            $rule = \App\Models\CommissionRule::where('booking_source', 'website')->where('is_active', true)->first();
            if ($rule) {
                $commissionAmount = 0;
                if ($rule->type === 'percentage') {
                    $commissionAmount = ($booking->total_amount * $rule->value) / 100;
                } else {
                    $commissionAmount = $rule->value * $booking->passenger_count;
                }
                
                \App\Models\BookingCommission::create([
                    'booking_id' => $booking->id,
                    'company_id' => $schedule->bus->bus_company_id,
                    'rule_id' => $rule->id,
                    'commission_amount' => $commissionAmount
                ]);
            }
        }

        if (!empty($seats)) {
            \DB::table('seat_locks')->where('schedule_id', $schedule->id)->whereIn('seat_number', $seats)->delete();
        }

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
