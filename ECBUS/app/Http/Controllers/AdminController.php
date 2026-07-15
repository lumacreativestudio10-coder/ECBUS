<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Location;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('booking_status', 'confirmed')->sum('total_amount');
        $activeSchedules = Schedule::whereDate('date', '>=', now())->count();
        $recentBookings = Booking::with('schedule.bus.operator')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBookings', 'totalRevenue', 'activeSchedules', 'recentBookings'));
    }

    public function bookings()
    {
        $bookings = Booking::with('schedule.bus.operator', 'schedule.route.fromLocation', 'schedule.route.toLocation')->latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update(['booking_status' => $request->booking_status]);
        return redirect()->back()->with('success', 'Booking status updated!');
    }

    public function schedules()
    {
        $schedules = Schedule::with('bus.operator', 'route.fromLocation', 'route.toLocation')->orderBy('date', 'desc')->get();
        $buses = Bus::with('operator', 'busType')->get();
        $routes = \App\Models\Route::with('fromLocation', 'toLocation')->where('status', 1)->get();
        
        return view('admin.schedules', compact('schedules', 'buses', 'routes'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $data = $request->all();
        $bus = Bus::findOrFail($request->bus_id);
        $data['available_seats'] = $bus->total_seats;

        Schedule::create($data);

        return redirect()->back()->with('success', 'Schedule added successfully!');
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'date' => 'required|date',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $data = $request->all();
        // Recalculate available seats if bus changes
        if ($request->bus_id != $schedule->bus_id) {
            $bus = Bus::findOrFail($request->bus_id);
            // Rough estimate: we should ideally subtract booked seats but this is a simple update.
            // A robust way would count existing bookings, but let's just reset to total for now
            // or just leave it. Let's reset to total_seats - booked_count
            $bookedCount = \App\Models\Booking::where('schedule_id', $schedule->id)->where('booking_status', '!=', 'cancelled')->sum('passenger_count');
            $data['available_seats'] = max(0, $bus->total_seats - $bookedCount);
        }

        $schedule->update($data);

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    public function destroySchedule(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Schedule deleted successfully!');
    }

    public function manageSeats(Schedule $schedule)
    {
        $schedule->load('bus.operator', 'route.fromLocation', 'route.toLocation');
        
        $bookings = Booking::where('schedule_id', $schedule->id)->where('booking_status', '!=', 'cancelled')->get();
        
        $bookedSeats = [];
        $seatDetails = [];
        
        foreach ($bookings as $booking) {
            if ($booking->seat_numbers && is_array($booking->seat_numbers)) {
                foreach ($booking->seat_numbers as $seat) {
                    $bookedSeats[] = $seat;
                    $seatDetails[$seat] = $booking;
                }
            }
        }

        return view('admin.manage-seats', compact('schedule', 'bookedSeats', 'seatDetails'));
    }

    public function updateSeats(Request $request, Schedule $schedule)
    {
        $request->validate([
            'seat_numbers' => 'required|array',
            'passenger_name' => 'required|string',
            'phone_number' => 'required|string'
        ]);

        $existingBookings = Booking::where('schedule_id', $schedule->id)
                                   ->where('status', '!=', 'Cancelled')
                                   ->whereNotNull('seat_numbers')
                                   ->get();
                                   
        foreach ($existingBookings as $booking) {
            if (is_array($booking->seat_numbers)) {
                foreach ($request->seat_numbers as $seat) {
                    if (in_array($seat, $booking->seat_numbers)) {
                        return redirect()->back()->withErrors(['error' => "Seat $seat is already booked!"]);
                    }
                }
            }
        }

        Booking::create([
            'schedule_id' => $schedule->id,
            'passenger_name' => $request->passenger_name,
            'phone' => $request->phone_number,
            'passenger_count' => count($request->seat_numbers),
            'seat_numbers' => $request->seat_numbers,
            'total_amount' => count($request->seat_numbers) * $schedule->price,
            'status' => 'Confirmed'
        ]);

        return redirect()->back()->with('success', 'Seats manually booked successfully!');
    }

    // Locations Management
    public function locations()
    {
        $locations = Location::orderBy('name')->get();
        return view('admin.locations', compact('locations'));
    }

    public function storeLocation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:locations']);
        Location::create($request->all());
        return redirect()->back()->with('success', 'Location added successfully!');
    }

    public function updateLocation(Request $request, Location $location)
    {
        $request->validate(['name' => 'required|string|max:255|unique:locations,name,' . $location->id]);
        $location->update($request->all());
        return redirect()->back()->with('success', 'Location updated successfully!');
    }

    public function destroyLocation(Location $location)
    {
        $location->delete();
        return redirect()->back()->with('success', 'Location deleted successfully!');
    }

    // Buses Management
    public function buses()
    {
        $buses = Bus::with('operator', 'busCompany', 'busType')->latest()->get();
        $operators = \App\Models\Operator::orderBy('name')->get();
        $busCompanies = \App\Models\BusCompany::orderBy('company_name')->get();
        $busTypes = \App\Models\BusType::orderBy('name')->get();
        return view('admin.buses', compact('buses', 'operators', 'busCompanies', 'busTypes'));
    }

    public function storeBus(Request $request)
    {
        $request->validate([
            'bus_company_id' => 'required|exists:bus_companies,id',
            'bus_type_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'bus_number' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seat_layout' => 'required|json'
        ]);

        // Find or create the BusType
        $busType = \App\Models\BusType::firstOrCreate(
            ['name' => $request->bus_type_name]
        );

        Bus::create([
            'bus_company_id' => $request->bus_company_id,
            'bus_type_id' => $busType->id,
            'name' => $request->name,
            'bus_number' => $request->bus_number,
            'registration_number' => $request->registration_number,
            'total_seats' => $request->total_seats,
            'seat_layout' => json_decode($request->seat_layout, true)
        ]);

        return redirect()->back()->with('success', 'Bus added successfully!');
    }

    public function updateBus(Request $request, Bus $bus)
    {
        $request->validate([
            'bus_company_id' => 'required|exists:bus_companies,id',
            'bus_type_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'bus_number' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seat_layout' => 'required|json'
        ]);

        $busType = \App\Models\BusType::firstOrCreate(
            ['name' => $request->bus_type_name]
        );

        $bus->update([
            'bus_company_id' => $request->bus_company_id,
            'bus_type_id' => $busType->id,
            'name' => $request->name,
            'bus_number' => $request->bus_number,
            'registration_number' => $request->registration_number,
            'total_seats' => $request->total_seats,
            'seat_layout' => json_decode($request->seat_layout, true)
        ]);

        return redirect()->back()->with('success', 'Bus updated successfully!');
    }

    public function destroyBus(Bus $bus)
    {
        $bus->delete();
        return redirect()->back()->with('success', 'Bus deleted successfully!');
    }
}
