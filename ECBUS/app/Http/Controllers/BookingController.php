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
            'email' => $request->is_walkin ? 'nullable|email|max:255' : 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'passenger_count' => 'required|integer|min:1',
            'boarding_point' => 'nullable|string|max:255',
            'dropping_point' => 'nullable|string|max:255',
            'seats' => 'nullable|array',
            'is_walkin' => 'nullable|boolean'
        ]);

        $schedule = Schedule::with('bus')->findOrFail($request->schedule_id);
        $seats = $request->seats ?? [];

        // Dynamically calculate available seats to prevent out-of-sync database errors
        $bookedSeatsCount = Booking::where('schedule_id', $schedule->id)
            ->where('booking_status', '!=', 'cancelled')
            ->sum('passenger_count');
        $actualAvailableSeats = $schedule->bus->total_seats - $bookedSeatsCount;

        if ($actualAvailableSeats < $request->passenger_count) {
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

        // Auto-calculate commission ONLY for website bookings using company-specific percentage rate
        if ($bookingSource === 'website') {
            $company = $schedule->bus?->busCompany;
            if ($company) {
                $commissionPercentage = $company->commission_per_seat ?? 0;
                $commissionAmount = ($booking->total_amount * $commissionPercentage) / 100;
                
                // Find or create a commission rule to satisfy foreign key constraint
                $rule = \App\Models\CommissionRule::where('booking_source', 'website')->first();
                if (!$rule) {
                    $rule = \App\Models\CommissionRule::create([
                        'booking_source' => 'website',
                        'type' => 'fixed',
                        'value' => 0,
                        'is_active' => true
                    ]);
                }
                
                \App\Models\BookingCommission::create([
                    'booking_id' => $booking->id,
                    'company_id' => $company->id,
                    'rule_id' => $rule->id,
                    'commission_amount' => $commissionAmount
                ]);
            }
        }

        if (!empty($seats)) {
            \DB::table('seat_locks')->where('schedule_id', $schedule->id)->whereIn('seat_number', $seats)->delete();
        }

        $schedule->decrement('available_seats', $request->passenger_count);

        // Send Notifications to Company Admin (role_id 2) and Staff (role_id 3)
        $company = $schedule->bus?->busCompany;
        if ($company) {
            $usersToNotify = \App\Models\User::where('company_id', $company->id)
                ->whereIn('role_id', [2, 3])
                ->where('status', 1)
                ->get();

            foreach ($usersToNotify as $recipient) {
                if ($recipient->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($recipient->email)->send(new \App\Mail\BookingNotificationMail($booking));
                    } catch (\Exception $e) {
                        \Log::error("Failed sending booking email to {$recipient->email}: " . $e->getMessage());
                    }
                }
                if ($recipient->phone_number) {
                    $fromLoc = $schedule->route->fromLocation->name ?? 'N/A';
                    $toLoc = $schedule->route->toLocation->name ?? 'N/A';
                    $smsMsg = "ECBUS: New booking {$booking->ticket_number} (Seats: {$booking->passenger_count}) on Route: {$fromLoc} to {$toLoc} for {$schedule->date} {$schedule->departure_time}. Fares: LKR " . number_format($booking->total_amount, 2);
                    \App\Services\SmsService::send($recipient->phone_number, $smsMsg);
                }
            }
        }

        // Send Notification to Customer
        if ($booking->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\CustomerTicketMail($booking, $booking->booking_status === 'confirmed'));
            } catch (\Exception $e) {
                \Log::error("Failed sending ticket email to customer {$booking->email}: " . $e->getMessage());
            }
        }
        if ($booking->phone) {
            $fromLoc = $schedule->route->fromLocation->name ?? 'N/A';
            $toLoc = $schedule->route->toLocation->name ?? 'N/A';
            $seatsStr = is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : $booking->seat_numbers;
            $dlUrl = route('booking.ticket', $booking->id);
            
            if ($booking->booking_status === 'confirmed') {
                $custMsg = "ECBUS: Your booking {$booking->ticket_number} is CONFIRMED (Seats: {$seatsStr}) on Route: {$fromLoc} to {$toLoc}. Download ticket: {$dlUrl}";
            } else {
                $custMsg = "ECBUS: Your booking {$booking->ticket_number} is received (Status: PENDING) on Route: {$fromLoc} to {$toLoc}. Download receipt: {$dlUrl}";
            }
            \App\Services\SmsService::send($booking->phone, $custMsg);
        }

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
