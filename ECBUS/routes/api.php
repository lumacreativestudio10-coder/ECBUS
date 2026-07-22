<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/featured-buses', function () {
    $schedules = \App\Models\Schedule::with([
        'bus.busType',
        'bus.busCompany',
        'route.fromLocation',
        'route.toLocation'
    ])
    ->whereIn('status', ['active', 'scheduled', 'published'])
    ->orderBy('date', 'desc')
    ->take(3)
    ->get();

    $featuredBuses = $schedules->map(function ($schedule) {
        return [
            'id' => $schedule->id,
            'bus_name' => $schedule->bus->name ?? 'Unknown Bus',
            'company_name' => $schedule->bus->busCompany->company_name ?? 'EC Express',
            'bus_type' => $schedule->bus->busType->name ?? 'Luxury AC',
            'seats_available' => $schedule->available_seats,
            'from' => $schedule->route->fromLocation->name ?? 'Unknown',
            'to' => $schedule->route->toLocation->name ?? 'Unknown',
            'departure_time' => \Illuminate\Support\Carbon::parse($schedule->departure_time)->format('h:i A'),
            'duration' => $schedule->route->duration_string ?? '9 Hours',
            'arrival_time' => \Illuminate\Support\Carbon::parse($schedule->arrival_time)->format('h:i A'),
            'price' => number_format($schedule->price, 0),
            'url' => url('/routes?from=' . urlencode($schedule->route->fromLocation->name ?? '') . '&to=' . urlencode($schedule->route->toLocation->name ?? '') . '&date=' . $schedule->date->format('Y-m-d') . '&passengers=1')
        ];
    });

    return response()->json($featuredBuses);
});
