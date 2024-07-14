<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', \App\Http\Controllers\Customer\HomeController::class)->name('index');
Route::get('/rooms', [\App\Http\Controllers\Customer\RoomController::class, 'index'])->name('rooms.index');
Route::get('/reservations/{room}', [\App\Http\Controllers\Customer\ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations/{room}', [\App\Http\Controllers\Customer\ReservationController::class, 'store'])->name('reservations.store');

Route::get('/payment/{booking}', [\App\Http\Controllers\Customer\PaymentController::class, 'show'])->name('payment.show');

Route::prefix('admin')->name('admin.')->middleware('redirect.if.unauthenticated')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboards.index');
    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class)->except('show');
    Route::resource('rooms.rooms-images', \App\Http\Controllers\Admin\RoomImageController::class)
        ->only('index', 'create', 'store', 'destroy')
        ->parameters(['rooms-images' => 'image', 'rooms' => 'room']);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only('index');
    Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class)->except('show');
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only('index', 'edit', 'update', 'destroy');
    Route::resource('suggestions', \App\Http\Controllers\Admin\SuggestionController::class)->only('index', 'edit', 'update');
    Route::resource('bookings.payments', \App\Http\Controllers\Admin\PaymentController::class)->only('update');
    Route::resource('calendars', \App\Http\Controllers\Admin\BookingCalendarController::class)
        ->only('index')
        ->parameter('calendars', 'booking');

    Route::resource('reports', \App\Http\Controllers\Admin\ReportController::class)->only('index', 'create');

    Route::resource('profile', \App\Http\Controllers\Admin\ProfileController::class)->only('create', 'store');
    Route::patch('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'password'])->name('profile.password');
});

Route::prefix('customer')->name('customer.')->middleware('redirect.if.unauthenticated')->group(function () {
    Route::resource('bookings', \App\Http\Controllers\Customer\BookingController::class)->only('index');
    Route::resource('suggestions', \App\Http\Controllers\Customer\SuggestionController::class)->except('show', 'destroy');

    Route::resource('profile', \App\Http\Controllers\Customer\ProfileController::class)->only('create', 'store');
    Route::patch('profile/password', [\App\Http\Controllers\Customer\ProfileController::class, 'password'])->name('profile.password');
});

Route::prefix('api/v1/admin')->name('api.v1.admin.')->group(function () {
   Route::resource('calendars', \App\Http\Controllers\Api\V1\Admin\BookingCalendarController::class)->only('index');
});

Route::view('/login', 'auth.login')->name('login.index');
Route::view('/register', 'auth.register')->name('register.index');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.store');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register.store');

Route::get('/logout', function () {
    auth()->logout();

    return redirect(\route('index'));
})->name('logout.store');
