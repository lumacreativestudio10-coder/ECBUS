<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function pass($msg) { echo "✅ " . $msg . "\n"; }
function fail($msg) { echo "❌ " . $msg . "\n"; }

echo "=== ECBUS FULL SYSTEM TEST ===\n\n";

// 1. Test Roles & Users
$roles = ['Super Admin', 'Company Admin', 'Company Staff', 'Driver', 'Conductor'];
foreach ($roles as $r) {
    $role = App\Models\Role::where('name', $r)->first();
    if ($role) pass("Role '$r' exists (ID: {$role->id})");
    else fail("Role '$r' is missing");
}

$superadmin = App\Models\User::where('email', 'superadmin@ecbus.com')->first();
if ($superadmin && $superadmin->role_id == 1) pass("Super Admin user exists"); else fail("Super Admin missing");

$driver = App\Models\User::where('email', 'driver@royalbus.com')->first();
if ($driver && $driver->role_id == 4) pass("Driver user exists"); else fail("Driver missing");

$conductor = App\Models\User::where('email', 'conductor@royalbus.com')->first();
if ($conductor && $conductor->role_id == 5) pass("Conductor user exists"); else fail("Conductor missing");

echo "\n=== BUS & ROUTE MANAGEMENT ===\n";
$company = App\Models\BusCompany::first();
if ($company) pass("Bus Company exists: " . $company->company_name); else fail("Bus Company missing");

$bus = App\Models\Bus::first();
if ($bus && $bus->bus_number) pass("Bus exists: " . $bus->bus_number . " (Seats: " . $bus->total_seats . ")"); else fail("Bus missing");

$route = App\Models\Route::first();
if ($route) pass("Route exists: " . $route->fromLocation->name . " to " . $route->toLocation->name); else fail("Route missing");

echo "\n=== SCHEDULE & BOOKING MODULE ===\n";
$schedule = App\Models\Schedule::first();
if ($schedule) pass("Schedule exists for date: " . $schedule->date); else fail("Schedule missing");

if ($schedule && $schedule->driver_id && $schedule->conductor_id) {
    pass("Driver and Conductor are correctly assigned to Schedule.");
} else {
    fail("Crew assignment missing on Schedule.");
}

$bookings = App\Models\Booking::where('schedule_id', $schedule->id)->get();
if ($bookings->count() > 0) pass("Bookings exist for the schedule (Total: " . $bookings->count() . ")"); else fail("No bookings found");

$totalSeatsBooked = 0;
foreach ($bookings as $b) {
    $totalSeatsBooked += count($b->seat_numbers ?? []);
}
pass("Total Seats Booked: $totalSeatsBooked");

echo "\n=== DASHBOARD LOGIC (CONDUCTOR) ===\n";
auth()->login($conductor);
$conductorController = app()->make(App\Http\Controllers\Dashboards\ConductorDashboardController::class);
$view = $conductorController->index();
$data = $view->getData();
if (isset($data['totalPassengers']) && $data['totalPassengers'] == 6) pass("Conductor: Total Passengers logic works"); else fail("Conductor: Total Passengers mismatch");
if (isset($data['boardedPassengers']) && $data['boardedPassengers'] == 3) pass("Conductor: Boarded logic works"); else fail("Conductor: Boarded mismatch");
if (isset($data['pendingPassengers']) && $data['pendingPassengers'] == 2) pass("Conductor: Pending logic works"); else fail("Conductor: Pending mismatch");

echo "\n=== DASHBOARD LOGIC (DRIVER) ===\n";
auth()->login($driver);
$driverController = app()->make(App\Http\Controllers\Dashboards\DriverDashboardController::class);
$view = $driverController->index();
$data = $view->getData();
if (isset($data['totalPassengers']) && $data['totalPassengers'] == 6) pass("Driver: Total Passengers logic works"); else fail("Driver: Total Passengers mismatch");
if (isset($data['todayTrips']) && count($data['todayTrips']) > 0) pass("Driver: Trips are loading"); else fail("Driver: No trips loaded");

echo "\n=== DASHBOARD LOGIC (ADMIN) ===\n";
auth()->login($superadmin);
$adminController = app()->make(App\Http\Controllers\AdminController::class);
$view = $adminController->dashboard();
$data = $view->getData();
if (isset($data['totalRevenue'])) pass("Admin: Total Revenue is " . $data['totalRevenue']); else fail("Admin: Revenue calculation failed");
if (isset($data['totalBookings'])) pass("Admin: Total Bookings is " . $data['totalBookings']); else fail("Admin: Bookings calculation failed");

echo "\n=== END OF TEST ===\n";
