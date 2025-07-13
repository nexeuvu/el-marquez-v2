<?php

use App\Http\Controllers\Admin\Booking_detailController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\Room_serviceController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\Service_detailController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->middleware(['auth'])->group(function (){
    Route::resource('room', RoomController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.room');
    Route::resource('room_service', Room_serviceController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.room_service');
    Route::resource('guest', GuestController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.guest');
    Route::resource('booking', BookingController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.booking');
    Route::resource('booking_detail', Booking_detailController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.booking_detail');
    Route::resource('service', ServiceController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.service');
    Route::resource('service_detail', Service_detailController::class)->only(['index', 'store', 'update', 'destroy', 'show'])->names('admin.service_detail');
    Route::resource('employee', EmployeeController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.employee');
    // Ruta para consultar DNI
    Route::get('guest/consultar-dni', [GuestController::class, 'consultarDni'])->name('admin.guest.consultar-dni');
    Route::get('employee/consultar-dni', [GuestController::class, 'consultarDni'])->name('admin.employee.consultar-dni');
    // Ruta para consultar RUC
    // Route::get('/proveedor/consultar-ruc', [ProveedorController::class, 'consultarRuc'])->name('admin.proveedor.consultar-ruc');
    Route::get('room/export-pdf', [RoomController::class, 'exportPdf'])->name('admin.room.export-pdf');
    Route::get('room/export-excel', [RoomController::class, 'exportExcel'])->name('admin.room.export-excel');
    Route::get('room_service/export-pdf', [Room_serviceController::class, 'exportPdf'])->name('admin.room_service.export-pdf');
    Route::get('room_service/export-excel', [Room_serviceController::class, 'exportExcel'])->name('admin.room_service.export-excel');
    Route::get('guest/export-pdf', [GuestController::class, 'exportPdf'])->name('admin.guest.export-pdf');
    Route::get('guest/export-excel', [GuestController::class, 'exportExcel'])->name('admin.guest.export-excel');
    Route::get('booking/export-pdf', [BookingController::class, 'exportPdf'])->name('admin.booking.export-pdf');
    Route::get('booking/export-excel', [BookingController::class, 'exportExcel'])->name('admin.booking.export-excel');
    Route::get('booking_detail/export-pdf', [Booking_detailController::class, 'exportPdf'])->name('admin.booking_detail.export-pdf');
    Route::get('booking_detail/export-excel', [Booking_detailController::class, 'exportExcel'])->name('admin.booking_detail.export-excel');
    Route::get('employee/export-pdf', [EmployeeController::class, 'exportPdf'])->name('admin.employee.export-pdf');
    Route::get('employee/export-excel', [EmployeeController::class, 'exportExcel'])->name('admin.employee.export-excel');    
    Route::get('service_detail/export-pdf', [Service_detailController::class, 'exportPdf'])->name('admin.service_detail.export-pdf');
    Route::get('service_detail/export-excel', [Service_detailController::class, 'exportExcel'])->name('admin.service_detail.export-excel');
});