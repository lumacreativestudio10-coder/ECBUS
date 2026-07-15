<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Location;

class AdminRouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['fromLocation', 'toLocation'])->latest()->get();
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
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        $data['status'] = $request->status === 'active';

        Route::create($data);

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
            'status' => 'required|in:active,inactive'
        ]);
        
        $data = $request->all();
        $data['status'] = $request->status === 'active';

        $route->update($data);

        return redirect()->back()->with('success', 'Route updated successfully!');
    }

    public function destroy(Route $route)
    {
        $route->delete(); // Soft delete
        return redirect()->back()->with('success', 'Route deleted successfully!');
    }
}
