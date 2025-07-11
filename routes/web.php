<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\RoomController;
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
    Route::resource('guest', GuestController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.guest');
    Route::resource('booking', BookingController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.booking');
    // Ruta para consultar DNI
    Route::get('guest/consultar-dni', [GuestController::class, 'consultarDni'])->name('admin.guest.consultar-dni');
    // Ruta para consultar RUC
    // Route::get('/proveedor/consultar-ruc', [ProveedorController::class, 'consultarRuc'])->name('admin.proveedor.consultar-ruc');
    Route::get('guest/export-pdf', [GuestController::class, 'exportPdf'])->name('admin.guest.export-pdf');
    Route::get('guest/export-excel', [GuestController::class, 'exportExcel'])->name('admin.guest.export-excel');

});