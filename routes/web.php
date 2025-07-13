<?php

use App\Http\Controllers\Admin\Booking_detailController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\Room_serviceController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\Service_detailController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'can:dashboard.view'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->middleware(['auth'])->group(function () {

    // 📦 ROOM
    Route::resource('room', RoomController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.room.index')
        ->names('admin.room');
    Route::get('room/export-pdf', [RoomController::class, 'exportPdf'])->name('admin.room.export-pdf')->middleware('can:admin.room.export-pdf');
    Route::get('room/export-excel', [RoomController::class, 'exportExcel'])->name('admin.room.export-excel')->middleware('can:admin.room.export-excel');

    // 🧹 ROOM SERVICE
    Route::resource('room_service', Room_serviceController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.room_service.index')
        ->names('admin.room_service');
    Route::get('room_service/export-pdf', [Room_serviceController::class, 'exportPdf'])->name('admin.room_service.export-pdf')->middleware('can:admin.room_service.export-pdf');
    Route::get('room_service/export-excel', [Room_serviceController::class, 'exportExcel'])->name('admin.room_service.export-excel')->middleware('can:admin.room_service.export-excel');

    // 👤 GUEST
    Route::resource('guest', GuestController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.guest.index')
        ->names('admin.guest');
    Route::get('guest/export-pdf', [GuestController::class, 'exportPdf'])->name('admin.guest.export-pdf')->middleware('can:admin.guest.export-pdf');
    Route::get('guest/export-excel', [GuestController::class, 'exportExcel'])->name('admin.guest.export-excel')->middleware('can:admin.guest.export-excel');
    Route::get('guest/consultar-dni', [GuestController::class, 'consultarDni'])->name('admin.guest.consultar-dni')->middleware('can:admin.guest.consultar-dni');

    // 🗓 BOOKING
    Route::resource('booking', BookingController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.booking.index')
        ->names('admin.booking');
    Route::get('booking/export-pdf', [BookingController::class, 'exportPdf'])->name('admin.booking.export-pdf')->middleware('can:admin.booking.export-pdf');
    Route::get('booking/export-excel', [BookingController::class, 'exportExcel'])->name('admin.booking.export-excel')->middleware('can:admin.booking.export-excel');

    // 🧾 BOOKING DETAIL
    Route::resource('booking_detail', Booking_detailController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.booking_detail.index')
        ->names('admin.booking_detail');
    Route::get('booking_detail/export-pdf', [Booking_detailController::class, 'exportPdf'])->name('admin.booking_detail.export-pdf')->middleware('can:admin.booking_detail.export-pdf');
    Route::get('booking_detail/export-excel', [Booking_detailController::class, 'exportExcel'])->name('admin.booking_detail.export-excel')->middleware('can:admin.booking_detail.export-excel');

    // 🧍‍♂ EMPLOYEE
    Route::resource('employee', EmployeeController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.employee.index')
        ->names('admin.employee');
    Route::get('employee/export-pdf', [EmployeeController::class, 'exportPdf'])->name('admin.employee.export-pdf')->middleware('can:admin.employee.export-pdf');
    Route::get('employee/export-excel', [EmployeeController::class, 'exportExcel'])->name('admin.employee.export-excel')->middleware('can:admin.employee.export-excel');
    Route::get('employee/consultar-dni', [GuestController::class, 'consultarDni'])->name('admin.employee.consultar-dni')->middleware('can:admin.employee.consultar-dni');

    // 🧴 SERVICE
    Route::resource('service', ServiceController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.service.index')
        ->names('admin.service');
    Route::get('service/export-pdf', [ServiceController::class, 'exportPdf'])->name('admin.service.export-pdf')->middleware('can:admin.service.export-pdf');
    Route::get('service/export-excel', [ServiceController::class, 'exportExcel'])->name('admin.service.export-excel')->middleware('can:admin.service.export-excel');

    // 🔧 SERVICE DETAIL
    Route::resource('service_detail', Service_detailController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show'])
        ->middleware('can:admin.service_detail.index')
        ->names('admin.service_detail');
    Route::get('service_detail/export-pdf', [Service_detailController::class, 'exportPdf'])->name('admin.service_detail.export-pdf')->middleware('can:admin.service_detail.export-pdf');
    Route::get('service_detail/export-excel', [Service_detailController::class, 'exportExcel'])->name('admin.service_detail.export-excel')->middleware('can:admin.service_detail.export-excel');

    // 💳 PAYMENT
    Route::resource('payment', PaymentController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('can:admin.payment.index')
        ->names('admin.payment');
    Route::get('payment/export-pdf', [PaymentController::class, 'exportPdf'])->name('admin.payment.export-pdf')->middleware('can:admin.payment.export-pdf');
    Route::get('payment/export-excel', [PaymentController::class, 'exportExcel'])->name('admin.payment.export-excel')->middleware('can:admin.payment.export-excel');

    // 🔔 NOTIFICATIONS
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead')->middleware('can:admin.notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.markAllAsRead')->middleware('can:admin.notifications.markAllAsRead');
});