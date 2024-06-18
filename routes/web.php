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

Route::get('/', function () {
    return 'home';
})->name('index');

Route::prefix('admin')->name('admin.')->middleware('redirect.if.unauthenticated')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboards.index');
    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class)->except('show');
    Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class)->except('show');
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->except('show');
    Route::resource('bookings.payments', \App\Http\Controllers\Admin\PaymentController::class)->only('update');
    Route::resource('calendars', \App\Http\Controllers\Admin\BookingCalendarController::class)
        ->only('index')
        ->parameter('calendars', 'booking');
});

Route::prefix('api/v1/admin')->name('api.v1.admin.')->group(function () {
   Route::resource('calendars', \App\Http\Controllers\Api\V1\Admin\BookingCalendarController::class)->only('index');
});

Route::view('/login', 'auth.login')->name('login.index');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.store');

Route::get('/logout', function () {
    auth()->logout();

    return redirect(\route('index'));
})->name('logout.store');
