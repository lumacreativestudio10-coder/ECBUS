<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PopularRoute;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;

class AdminPopularRouteController extends Controller
{
    public function index()
    {
        $routes = PopularRoute::with(['fromLocation', 'toLocation'])->latest()->get();
        $locations = Location::orderBy('name')->get();
        return view('admin.popular_routes', compact('routes', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_location_id' => 'required|exists:locations,id|different:to_location_id',
            'to_location_id' => 'required|exists:locations,id',
            'starting_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');
        $data['status'] = $request->status === 'active';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('popular_routes', 'public');
            $data['image'] = $path;
        }

        PopularRoute::create($data);

        return redirect()->back()->with('success', 'Popular Route added successfully!');
    }

    public function update(Request $request, PopularRoute $popularRoute)
    {
        \Illuminate\Support\Facades\Log::info('Update request received', ['has_file' => $request->hasFile('image'), 'files' => $request->allFiles(), 'post' => $request->all()]);
        $request->validate([
            'from_location_id' => 'required|exists:locations,id|different:to_location_id',
            'to_location_id' => 'required|exists:locations,id',
            'starting_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $data = $request->except('image');
        $data['status'] = $request->status === 'active';

        if ($request->hasFile('image')) {
            if ($popularRoute->image) {
                Storage::disk('public')->delete($popularRoute->image);
            }
            $path = $request->file('image')->store('popular_routes', 'public');
            $data['image'] = $path;
        }

        $popularRoute->update($data);

        return redirect()->back()->with('success', 'Popular Route updated successfully!');
    }

    public function destroy(PopularRoute $popularRoute)
    {
        if ($popularRoute->image) {
            Storage::disk('public')->delete($popularRoute->image);
        }
        $popularRoute->delete();
        return redirect()->back()->with('success', 'Popular Route deleted successfully!');
    }
}
