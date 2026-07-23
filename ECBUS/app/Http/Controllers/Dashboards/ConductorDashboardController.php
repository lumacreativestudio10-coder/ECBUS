<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ConductorDashboardController extends Controller
{
    public function index()
    {
        $todayTrips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus.busType', 'driver', 'bookings'])
            ->where('conductor_id', auth()->id())
            ->whereDate('date', today())
            ->orderBy('departure_time')
            ->get();

        $currentTrip = $todayTrips->first();

        $totalPassengers = 0;
        $boardedPassengers = 0;
        $pendingPassengers = 0;
        $noShowPassengers = 0;
        $verifiedTickets = 0;

        if ($currentTrip) {
            foreach ($currentTrip->bookings as $booking) {
                if ($booking->booking_status !== 'cancelled') {
                    if ($booking->is_verified) {
                        $verifiedTickets += count($booking->seat_numbers ?? []);
                    }
                    
                    $seats = $booking->seat_numbers ?? [];
                    $statuses = $booking->boarding_statuses ?? [];
                    foreach ($seats as $seat) {
                        $totalPassengers++;
                        $status = $statuses[$seat] ?? 'pending';
                        if ($status === 'boarded') {
                            $boardedPassengers++;
                        } else if ($status === 'pending') {
                            $pendingPassengers++;
                        } else if ($status === 'no_show') {
                            $noShowPassengers++;
                        }
                    }
                }
            }
        }

        return view('conductor.dashboard', compact('todayTrips', 'currentTrip', 'totalPassengers', 'boardedPassengers', 'pendingPassengers', 'verifiedTickets', 'noShowPassengers'));
    }

    public function todayTrips()
    {
        $trips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus', 'driver'])
            ->where('conductor_id', auth()->id())
            ->whereDate('date', today())
            ->orderBy('departure_time')
            ->get();

        return view('conductor.today-trips', compact('trips'));
    }

    public function passengerList(Schedule $schedule)
    {
        // Ensure this schedule belongs to the conductor
        if ($schedule->conductor_id !== auth()->id()) {
            abort(403);
        }

        $schedule->load(['bookings', 'bus', 'route.fromLocation', 'route.toLocation']);

        return view('conductor.passenger-list', compact('schedule'));
    }

    public function ticketVerification()
    {
        return view('conductor.ticket-verification');
    }

    public function searchTicket(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        $booking = \App\Models\Booking::whereHas('schedule', function ($q) {
                $q->where('conductor_id', auth()->id())
                  ->whereDate('date', today());
            })
            ->where(function ($q) use ($query) {
                $q->where('booking_reference', $query)
                  ->orWhere('phone', $query);
            })
            ->with(['schedule.route.fromLocation', 'schedule.route.toLocation', 'schedule.bus'])
            ->first();

        return view('conductor.ticket-verification', compact('booking', 'query'));
    }

    public function verifyTicket(\App\Models\Booking $booking)
    {
        // Ensure this booking belongs to today's schedule for this conductor
        if ($booking->schedule->conductor_id !== auth()->id() || $booking->schedule->date !== today()->toDateString()) {
            abort(403);
        }

        $booking->update(['is_verified' => true]);

        return redirect()->back()->with('success', 'Ticket successfully verified.');
    }

    public function markBoarded(Request $request, \App\Models\Booking $booking)
    {
        $request->validate([
            'seat_number' => 'required|string',
            'status' => 'required|in:pending,boarded,no_show'
        ]);

        // Authorization check - the schedule must belong to this conductor
        if ($booking->schedule->conductor_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $statuses = $booking->boarding_statuses ?? [];
        $statuses[$request->seat_number] = $request->status;
        
        $booking->boarding_statuses = $statuses;
        $booking->save();

        return redirect()->back()->with('success', 'Boarding status updated.');
    }

    public function tripHistory()
    {
        $trips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus'])
            ->where('conductor_id', auth()->id())
            ->whereDate('date', '<', today())
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('conductor.trip-history', compact('trips'));
    }

    public function globalPassengerList()
    {
        // Try today's trip first, then fall back to nearest upcoming trip
        $trip = Schedule::where('conductor_id', auth()->id())
            ->whereDate('date', today())
            ->first();
        
        if (!$trip) {
            $trip = Schedule::where('conductor_id', auth()->id())
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('departure_time')
                ->first();
        }

        if (!$trip) return redirect()->route('conductor.today_trips')->with('error', 'No assigned trips found.');
        return redirect()->route('conductor.passenger_list', $trip->id);
    }
}
