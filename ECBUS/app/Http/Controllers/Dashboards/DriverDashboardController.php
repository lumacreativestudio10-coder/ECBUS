<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $todayTrips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus'])
            ->where('driver_id', auth()->id())
            ->whereDate('date', today())
            ->orderBy('departure_time')
            ->get();

        $completedTrips = Schedule::where('driver_id', auth()->id())
            ->where('status', 'completed')
            ->count();

        $currentTrip = $todayTrips->firstWhere('status', 'in_progress') ?? $todayTrips->firstWhere('status', 'scheduled');
        
        $totalPassengers = 0;
        if ($currentTrip) {
            $currentTrip->load('bookings');
            foreach ($currentTrip->bookings as $booking) {
                if ($booking->booking_status !== 'cancelled') {
                    $totalPassengers += $booking->passenger_count ?? count($booking->seat_numbers ?? []);
                }
            }
        }

        return view('driver.dashboard', compact('todayTrips', 'currentTrip', 'totalPassengers', 'completedTrips'));
    }

    public function myTrips()
    {
        $todayTrips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus'])
            ->where('driver_id', auth()->id())
            ->whereDate('date', today())
            ->orderBy('departure_time')
            ->get();

        $upcomingTrips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus'])
            ->where('driver_id', auth()->id())
            ->whereDate('date', '>', today())
            ->orderBy('date')
            ->orderBy('departure_time')
            ->get();

        return view('driver.my-trips', compact('todayTrips', 'upcomingTrips'));
    }

    public function passengerList(Schedule $schedule)
    {
        if ($schedule->driver_id !== auth()->id()) {
            abort(403);
        }

        $schedule->load(['bookings', 'bus', 'route.fromLocation', 'route.toLocation']);

        return view('driver.passenger-list', compact('schedule'));
    }

    public function routeDetails(Schedule $schedule)
    {
        if ($schedule->driver_id !== auth()->id()) {
            abort(403);
        }

        $schedule->load(['route.fromLocation', 'route.toLocation', 'route.stops', 'bus']);

        return view('driver.route-details', compact('schedule'));
    }

    public function startTrip(Schedule $schedule)
    {
        if ($schedule->driver_id !== auth()->id()) {
            abort(403);
        }

        $schedule->update(['status' => 'in_progress']);

        return redirect()->back()->with('success', 'Trip started successfully!');
    }

    public function completeTrip(Schedule $schedule)
    {
        if ($schedule->driver_id !== auth()->id()) {
            abort(403);
        }

        $schedule->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Trip marked as completed!');
    }

    public function globalPassengerList()
    {
        $trip = Schedule::where('driver_id', auth()->id())->whereDate('date', today())->first();
        if (!$trip) return redirect()->route('driver.my_trips')->with('error', 'No trips today.');
        return redirect()->route('driver.passenger_list', $trip->id);
    }

    public function globalRouteDetails()
    {
        $trip = Schedule::where('driver_id', auth()->id())->whereDate('date', today())->first();
        if (!$trip) return redirect()->route('driver.my_trips')->with('error', 'No trips today.');
        return redirect()->route('driver.route_details', $trip->id);
    }

    public function tripHistory()
    {
        $pastTrips = Schedule::with(['route.fromLocation', 'route.toLocation', 'bus'])
            ->where('driver_id', auth()->id())
            ->where('status', 'completed')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('driver.trip-history', compact('pastTrips'));
    }
}
