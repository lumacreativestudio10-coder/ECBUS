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
        $recentBookings = Booking::with('schedule.bus.busCompany')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBookings', 'totalRevenue', 'activeSchedules', 'recentBookings'));
    }

    public function bookings()
    {
        $bookings = Booking::with('schedule.bus.busCompany', 'schedule.route.fromLocation', 'schedule.route.toLocation')->latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update(['booking_status' => $request->booking_status]);

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    public function showBooking(Booking $booking)
    {
        $booking->load('schedule.route.fromLocation', 'schedule.route.toLocation', 'schedule.bus.busCompany');
        return view('admin.show-booking', compact('booking'));
    }

    public function editBooking(Booking $booking)
    {
        $booking->load('schedule.route.fromLocation', 'schedule.route.toLocation', 'schedule.bus.busCompany');
        return view('admin.edit-booking', compact('booking'));
    }

    public function updateBookingDetails(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'boarding_point' => 'nullable|string|max:255',
            'dropping_point' => 'nullable|string|max:255',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking details updated successfully!');
    }

    public function schedules()
    {
        $schedules = Schedule::with('bus.busCompany', 'route.fromLocation', 'route.toLocation')->orderBy('date', 'desc')->get();
        $buses = Bus::with('busCompany', 'busType')->get();
        $routes = \App\Models\Route::with('fromLocation', 'toLocation')->where('status', 1)->get();
        $busCompanies = \App\Models\BusCompany::orderBy('company_name')->get();
        $drivers = \App\Models\User::where('role_id', 4)->where('status', 1)->get();
        $conductors = \App\Models\User::where('role_id', 5)->where('status', 1)->get();
        
        return view('admin.schedules', compact('schedules', 'buses', 'routes', 'busCompanies', 'drivers', 'conductors'));
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
            'status' => 'required|in:scheduled,completed,cancelled',
            'driver_id' => 'nullable|exists:users,id',
            'conductor_id' => 'nullable|exists:users,id',
            'publish_status' => 'nullable|in:draft,published'
        ]);

        if ($request->driver_id) {
            $overlap = Schedule::where('driver_id', $request->driver_id)->where('date', $request->date)->exists();
            if ($overlap) return redirect()->back()->withErrors(['driver_id' => 'Driver is already assigned on this date.']);
        }
        if ($request->conductor_id) {
            $overlap = Schedule::where('conductor_id', $request->conductor_id)->where('date', $request->date)->exists();
            if ($overlap) return redirect()->back()->withErrors(['conductor_id' => 'Conductor is already assigned on this date.']);
        }

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
            'status' => 'required|in:scheduled,completed,cancelled',
            'driver_id' => 'nullable|exists:users,id',
            'conductor_id' => 'nullable|exists:users,id',
            'publish_status' => 'nullable|in:draft,published'
        ]);

        if ($request->driver_id && $request->driver_id != $schedule->driver_id) {
            $overlap = Schedule::where('driver_id', $request->driver_id)->where('date', $request->date)->where('id', '!=', $schedule->id)->exists();
            if ($overlap) return redirect()->back()->withErrors(['driver_id' => 'Driver is already assigned on this date.']);
        }
        if ($request->conductor_id && $request->conductor_id != $schedule->conductor_id) {
            $overlap = Schedule::where('conductor_id', $request->conductor_id)->where('date', $request->date)->where('id', '!=', $schedule->id)->exists();
            if ($overlap) return redirect()->back()->withErrors(['conductor_id' => 'Conductor is already assigned on this date.']);
        }

        $data = $request->all();
        if ($request->bus_id != $schedule->bus_id) {
            $bus = Bus::findOrFail($request->bus_id);
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

    public function manageSeats(Request $request, Schedule $schedule)
    {
        $schedule->load('bus.busCompany', 'route.fromLocation', 'route.toLocation');
        
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

        $targetBooking = null;
        if ($request->has('booking_id')) {
            $targetBooking = Booking::find($request->booking_id);
        }

        $totalRevenue = $bookings->sum('total_amount');
        $commissionAmount = \App\Models\BookingCommission::whereIn('booking_id', $bookings->pluck('id'))->sum('commission_amount');
        $netRevenue = $totalRevenue - $commissionAmount;

        return view('admin.manage-seats', compact('schedule', 'bookedSeats', 'seatDetails', 'targetBooking', 'totalRevenue', 'commissionAmount', 'netRevenue'));
    }

    public function updateSeats(Request $request, Schedule $schedule)
    {
        $request->validate([
            'seat_numbers' => 'required|array',
            'customer_name' => 'required|string',
            'phone_number' => 'required|string'
        ]);

        $existingBookings = Booking::where('schedule_id', $schedule->id)
                                   ->where('booking_status', '!=', 'cancelled')
                                   ->whereNotNull('seat_numbers')
                                   ->get();
                                   
        foreach ($existingBookings as $booking) {
            // Skip checking against the target booking if we are updating it
            if ($request->has('target_booking_id') && $booking->id == $request->target_booking_id) {
                continue;
            }
            if (is_array($booking->seat_numbers)) {
                foreach ($request->seat_numbers as $seat) {
                    if (in_array($seat, $booking->seat_numbers)) {
                        return redirect()->back()->withErrors(['error' => "Seat $seat is already booked!"]);
                    }
                }
            }
        }

        if ($request->has('target_booking_id') && $request->target_booking_id) {
            $booking = Booking::findOrFail($request->target_booking_id);
            $booking->update([
                'seat_numbers' => $request->seat_numbers,
                'booking_status' => 'confirmed'
            ]);
            return redirect()->route('admin.bookings')->with('success', 'Seats successfully assigned to booking!');
        } else {
            Booking::create([
                'schedule_id' => $schedule->id,
                'customer_name' => $request->customer_name,
                'phone' => $request->phone_number,
                'passenger_count' => count($request->seat_numbers),
                'seat_numbers' => $request->seat_numbers,
                'boarding_point' => $request->boarding_point,
                'dropping_point' => $request->dropping_point,
                'total_amount' => $schedule->price * count($request->seat_numbers),
                'booking_status' => 'confirmed'
            ]);
            return redirect()->back()->with('success', 'Seats manually booked successfully!');
        }
    }

    public function printManifest(Schedule $schedule)
    {
        $schedule->load('bus.busCompany', 'route.fromLocation', 'route.toLocation');
        
        $bookings = Booking::where('schedule_id', $schedule->id)
                           ->where('booking_status', '!=', 'cancelled')
                           ->get();
                           
        // Prepare passenger list
        $passengers = [];
        foreach ($bookings as $booking) {
            if ($booking->seat_numbers && is_array($booking->seat_numbers)) {
                foreach ($booking->seat_numbers as $seat) {
                    $passengers[] = [
                        'seat' => $seat,
                        'name' => $booking->customer_name,
                        'phone' => $booking->phone,
                        'ref' => $booking->booking_reference
                    ];
                }
            }
        }
        
        // Sort by seat number if possible (e.g., 1A, 1B, 2A)
        usort($passengers, function($a, $b) {
            return strcmp($a['seat'], $b['seat']);
        });

        return view('admin.manifest_print', compact('schedule', 'passengers'));
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
        $buses = Bus::with('busCompany', 'busType')->latest()->get();
        $busCompanies = \App\Models\BusCompany::orderBy('company_name')->get();
        $busTypes = \App\Models\BusType::orderBy('name')->get();
        return view('admin.buses', compact('buses', 'busCompanies', 'busTypes'));
    }

    private function parseOrGenerateLayout($layoutInput, $totalSeats)
    {
        if (in_array($layoutInput, ['2x2', '2x1', 'sleeper', 'luxury', 'mini'])) {
            $layout = [];
            $cols = $layoutInput === '2x1' ? 3 : 4;
            $rows = ceil($totalSeats / $cols);
            $seatNo = 1;
            for ($r = 1; $r <= $rows; $r++) {
                $rowLayout = [];
                for ($c = 1; $c <= $cols + 1; $c++) {
                    if ($seatNo > $totalSeats) break;
                    if (($layoutInput === '2x1' && $c === 3) || ($layoutInput === '2x2' && $c === 3)) {
                        $rowLayout[] = null; // Aisle
                        continue;
                    }
                    $rowLayout[] = ['label' => (string)$seatNo++, 'type' => 'Normal'];
                }
                $layout[] = $rowLayout;
            }
            return $layout;
        }
        return json_decode($layoutInput, true) ?? [];
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
            'seat_layout' => 'required|string'
        ]);

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
            'seat_layout' => $this->parseOrGenerateLayout($request->seat_layout, $request->total_seats)
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
            'seat_layout' => 'required|string'
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
            'seat_layout' => $this->parseOrGenerateLayout($request->seat_layout, $request->total_seats)
        ]);

        return redirect()->back()->with('success', 'Bus updated successfully!');
    }

    public function destroyBus(Bus $bus)
    {
        $bus->delete();
        return redirect()->back()->with('success', 'Bus deleted successfully!');
    }
}
