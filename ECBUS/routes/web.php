<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
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
    $partners = \App\Models\Operator::where('status', 1)->orderBy('name')->get();
    return view('partners', compact('partners'));
})->name('partners');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

use App\Http\Controllers\BookingController;
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/my-booking', [BookingController::class, 'myBookings'])->name('booking');

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminPartnerController;
use App\Http\Controllers\AdminRouteController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::patch('/bookings/{booking}', [AdminController::class, 'updateBookingStatus'])->name('bookings.update');
    Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules');
    Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [AdminController::class, 'updateSchedule'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy');
    Route::get('/schedules/{schedule}/seats', [AdminController::class, 'manageSeats'])->name('schedules.seats');
    Route::post('/schedules/{schedule}/seats', [AdminController::class, 'updateSeats'])->name('schedules.seats.update');

    Route::get('/locations', [AdminController::class, 'locations'])->name('locations');
    Route::post('/locations', [AdminController::class, 'storeLocation'])->name('locations.store');
    Route::put('/locations/{location}', [AdminController::class, 'updateLocation'])->name('locations.update');
    Route::delete('/locations/{location}', [AdminController::class, 'destroyLocation'])->name('locations.destroy');

    // CRM Modules
    Route::get('/partners', [AdminPartnerController::class, 'index'])->name('partners');
    Route::post('/partners', [AdminPartnerController::class, 'store'])->name('partners.store');
    Route::put('/partners/{partner}', [AdminPartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

    Route::get('/routes', [AdminRouteController::class, 'index'])->name('routes');
    Route::post('/routes', [AdminRouteController::class, 'store'])->name('routes.store');
    Route::put('/routes/{route}', [AdminRouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{route}', [AdminRouteController::class, 'destroy'])->name('routes.destroy');

    Route::get('/reviews', [App\Http\Controllers\AdminReviewController::class, 'index'])->name('reviews');
    Route::put('/reviews/{review}', [App\Http\Controllers\AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/buses', [AdminController::class, 'buses'])->name('buses');
        Route::post('/buses', [AdminController::class, 'storeBus'])->name('buses.store');
        Route::put('/buses/{bus}', [AdminController::class, 'updateBus'])->name('buses.update');
        Route::delete('/buses/{bus}', [AdminController::class, 'destroyBus'])->name('buses.destroy');
    });
});
