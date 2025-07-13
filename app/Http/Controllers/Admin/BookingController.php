<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;

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
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_pay' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Booking::create([
                'guest_id' => $request->guest_id,
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_pay' => $request->price_pay,
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
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_pay' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

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
        $booking->delete();

        return redirect()->route('admin.booking.index')
            ->with('success', 'La reserva fue eliminada correctamente.');
    }

    public function exportPdf()
    {
        $bookings = Booking::with(['guest', 'room'])->get();
        $pdf = Pdf::loadView('admin.booking.pdf', compact('bookings'));
        return $pdf->download('reporte_reservas.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BookingsExport, 'reporte_reservas.xlsx');
    }
}
