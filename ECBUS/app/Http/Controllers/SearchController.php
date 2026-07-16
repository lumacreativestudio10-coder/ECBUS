<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Location;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $fromName = $request->get('from');
        $toName = $request->get('to');
        $dateStr = $request->get('date');
        $passengers = $request->get('passengers', 1);

        $query = Schedule::with(['bus.busCompany', 'route.fromLocation', 'route.toLocation', 'route.stops'])
                         ->withSum(['bookings as booked_seats' => function($query) {
                             $query->where('booking_status', '!=', 'cancelled');
                         }], 'passenger_count');

        if ($fromName && $toName) {
            $from = Location::where('name', $fromName)->first();
            $to = Location::where('name', $toName)->first();
            
            if ($from && $to) {
                $query->whereHas('route', function ($q) use ($from, $to) {
                    $q->where('from_location_id', $from->id)
                      ->where('to_location_id', $to->id);
                });
            }
        }

        if ($dateStr) {
            $query->whereDate('date', $dateStr);
        } else {
            $query->whereDate('date', '>=', now()->toDateString());
        }

        $schedules = $query->orderBy('date', 'asc')->orderBy('departure_time', 'asc')->get();

        return view('routes', compact('schedules'));
    }
}
