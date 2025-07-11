<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BookingController extends Controller
{
    public function index()
    {
        return view('Admin.booking.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'price_pay' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            // Check if room is available for the selected dates
            $existingBooking = Booking::where('room_id', $request->room_id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                          ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                          ->orWhere(function($q) use ($request) {
                              $q->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                          });
                })
                ->exists();

            if ($existingBooking) {
                throw ValidationException::withMessages([
                    'room_id' => 'La habitación no está disponible para las fechas seleccionadas.'
                ]);
            }

            Booking::create([
                'guest_id' => $request->guest_id,
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_pay' => $request->price_pay,
                'status' => true,
            ]);

            return redirect()->route('admin.booking.index')
                ->with('success', 'La reserva fue registrada correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price_pay' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            // Check if room is available for the selected dates (excluding current booking)
            $existingBooking = Booking::where('room_id', $request->room_id)
                ->where('id', '!=', $id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                          ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                          ->orWhere(function($q) use ($request) {
                              $q->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                          });
                })
                ->exists();

            if ($existingBooking) {
                throw ValidationException::withMessages([
                    'room_id' => 'La habitación no está disponible para las fechas seleccionadas.'
                ]);
            }

            $booking = Booking::findOrFail($id);
            $booking->update([
                'guest_id' => $request->guest_id,
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_pay' => $request->price_pay,
            ]);

            return redirect()->route('admin.booking.index')
                ->with('success', 'La reserva fue actualizada correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => false]);

        return redirect()->route('admin.booking.index')
            ->with('success', 'La reserva fue cancelada correctamente.');
    }

    public function exportPdf()
    {
        $bookings = Booking::with(['guest', 'room'])
                        ->where('status', true)
                        ->orderBy('start_date')
                        ->get();
        $pdf = Pdf::loadView('admin.booking.pdf', compact('bookings'));
        return $pdf->download('reporte_reservas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BookingsExport, 'reporte_reservas.xlsx');
    }
}
