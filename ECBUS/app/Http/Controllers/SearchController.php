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

        $from = Location::where('name', $fromName)->first();
        $to = Location::where('name', $toName)->first();
        
        $schedules = collect();
        
        if ($from && $to && $dateStr) {
            $schedules = Schedule::with(['bus.operator', 'route.fromLocation', 'route.toLocation'])
                                 ->whereHas('route', function ($query) use ($from, $to) {
                                     $query->where('from_location_id', $from->id)
                                           ->where('to_location_id', $to->id);
                                 })
                                 ->whereDate('date', $dateStr)
                                 ->get();
        }

        return view('routes', compact('schedules'));
    }
}
