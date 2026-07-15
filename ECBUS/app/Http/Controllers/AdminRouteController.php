<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Location;

class AdminRouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['fromLocation', 'toLocation', 'stops'])->latest()->get();
        $locations = Location::orderBy('name')->get();
        return view('admin.routes', compact('routes', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from_location_id' => 'required|exists:locations,id|different:to_location_id',
            'to_location_id' => 'required|exists:locations,id',
            'distance' => 'nullable|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'starting_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'stops' => 'nullable|json'
        ]);
        $data = $request->all();
        $data['status'] = $request->status === 'active';

        $route = Route::create($data);

        if ($request->has('stops') && $request->stops) {
            $stops = json_decode($request->stops, true);
            foreach ($stops as $index => $stop) {
                $route->stops()->create([
                    'stop_name' => $stop['stop_name'],
                    'stop_order' => $index + 1,
                    'time_offset_minutes' => $stop['time_offset_minutes'] ?? 0
                ]);
            }
        }

        return redirect()->back()->with('success', 'Route added successfully!');
    }

    public function update(Request $request, Route $route)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from_location_id' => 'required|exists:locations,id|different:to_location_id',
            'to_location_id' => 'required|exists:locations,id',
            'distance' => 'nullable|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'starting_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'stops' => 'nullable|json'
        ]);
        
        $data = $request->all();
        $data['status'] = $request->status === 'active';

        $route->update($data);

        if ($request->has('stops') && $request->stops) {
            $route->stops()->delete(); // Clear old stops
            $stops = json_decode($request->stops, true);
            foreach ($stops as $index => $stop) {
                $route->stops()->create([
                    'stop_name' => $stop['stop_name'],
                    'stop_order' => $index + 1,
                    'time_offset_minutes' => $stop['time_offset_minutes'] ?? 0
                ]);
            }
        }

        return redirect()->back()->with('success', 'Route updated successfully!');
    }

    public function destroy(Route $route)
    {
        $route->delete(); // Soft delete
        return redirect()->back()->with('success', 'Route deleted successfully!');
    }
}
