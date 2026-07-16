<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $locations = \App\Models\Location::orderBy('name')->get();
    $reviews = \App\Models\Review::where('status', 'published')->latest()->take(6)->get();
    return view('welcome', compact('locations', 'reviews'));
})->name('home');

use App\Http\Controllers\SearchController;
Route::get('/routes', [SearchController::class, 'search'])->name('routes');

use App\Http\Controllers\ReviewController;
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/partners', function () {
    $partners = \App\Models\BusCompany::where('status', 1)->orderBy('company_name')->get();
    return view('partners', compact('partners'));
})->name('partners');

Route::get('/about', function () {
    return view('about');
})->name('about');

use App\Http\Controllers\ContactController;
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBusCompanyController;
use App\Http\Controllers\AdminRouteController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminContactMessageController;
use App\Http\Controllers\ProfileController;

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/my-booking', [BookingController::class, 'myBookings'])->name('booking');
Route::get('/booking/{booking}/ticket', [BookingController::class, 'downloadTicket'])->name('booking.ticket');

// Authentication Routes (Guest accessible)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

// Shared routes callback to be applied to all role prefixes
$sharedRoutes = function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // CRM Modules
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->name('bookings.edit');
    Route::put('/bookings/{booking}/details', [AdminController::class, 'updateBookingDetails'])->name('bookings.updateDetails');
    Route::patch('/bookings/{booking}', [AdminController::class, 'updateBookingStatus'])->name('bookings.update');
    
    Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules');
    Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [AdminController::class, 'updateSchedule'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy');
    Route::get('/schedules/{schedule}/seats', [AdminController::class, 'manageSeats'])->name('schedules.seats');
    Route::post('/schedules/{schedule}/seats', [AdminController::class, 'updateSeats'])->name('schedules.seats.update');
    Route::get('/schedules/{schedule}/manifest', [AdminController::class, 'printManifest'])->name('schedules.manifest');

    Route::get('/locations', [AdminController::class, 'locations'])->name('locations');
    Route::post('/locations', [AdminController::class, 'storeLocation'])->name('locations.store');
    Route::put('/locations/{location}', [AdminController::class, 'updateLocation'])->name('locations.update');
    Route::delete('/locations/{location}', [AdminController::class, 'destroyLocation'])->name('locations.destroy');

    Route::get('/bus-companies', [AdminBusCompanyController::class, 'index'])->name('bus_companies');
    Route::post('/bus-companies', [AdminBusCompanyController::class, 'store'])->name('bus_companies.store');
    Route::put('/bus-companies/{busCompany}', [AdminBusCompanyController::class, 'update'])->name('bus_companies.update');
    Route::delete('/bus-companies/{busCompany}', [AdminBusCompanyController::class, 'destroy'])->name('bus_companies.destroy');

    Route::get('/routes', [AdminRouteController::class, 'index'])->name('routes');
    Route::post('/routes', [AdminRouteController::class, 'store'])->name('routes.store');
    Route::put('/routes/{route}', [AdminRouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{route}', [AdminRouteController::class, 'destroy'])->name('routes.destroy');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews', [AdminReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/buses', [AdminController::class, 'buses'])->name('buses');
    Route::post('/buses', [AdminController::class, 'storeBus'])->name('buses.store');
    Route::put('/buses/{bus}', [AdminController::class, 'updateBus'])->name('buses.update');
    Route::delete('/buses/{bus}', [AdminController::class, 'destroyBus'])->name('buses.destroy');

    Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('contact_messages');
    Route::patch('/contact-messages/{message}', [AdminContactMessageController::class, 'updateStatus'])->name('contact_messages.update');
    Route::delete('/contact-messages/{message}', [AdminContactMessageController::class, 'destroy'])->name('contact_messages.destroy');
    
    // Manage Users Module
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.updateStatus');
};

// ==========================================
// ROLE-BASED ROUTE GROUPS
// ==========================================

// 1. Super Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:1'])->group(function() use ($sharedRoutes) {
    Route::get('/dashboard', [App\Http\Controllers\Dashboards\SuperAdminDashboardController::class, 'index'])->name('dashboard');
    $sharedRoutes();
});

// 2. Company Admin
Route::prefix('company')->name('company.')->middleware(['auth', 'role:2'])->group(function() use ($sharedRoutes) {
    Route::get('/dashboard', [App\Http\Controllers\Dashboards\CompanyDashboardController::class, 'index'])->name('dashboard');
    $sharedRoutes();
});

// 3. Staff
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:3'])->group(function() use ($sharedRoutes) {
    Route::get('/dashboard', [App\Http\Controllers\Dashboards\StaffDashboardController::class, 'index'])->name('dashboard');
    $sharedRoutes();
});

// 4. Driver
Route::prefix('driver')->name('driver.')->middleware(['auth', 'role:4'])->group(function() use ($sharedRoutes) {
    Route::get('/dashboard', [App\Http\Controllers\Dashboards\DriverDashboardController::class, 'index'])->name('dashboard');
    $sharedRoutes();
});

// 5. Conductor
Route::prefix('conductor')->name('conductor.')->middleware(['auth', 'role:5'])->group(function() use ($sharedRoutes) {
    Route::get('/dashboard', [App\Http\Controllers\Dashboards\ConductorDashboardController::class, 'index'])->name('dashboard');
    $sharedRoutes();
});
