<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\PagoRegistrado;
use App\Models\User; // O el modelo del usuario que recibirá la notificación

class PaymentController extends Controller
{
    public function index()
    {
        return view('Admin.payment.index');
    }

    public function store(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'guest_id'        => 'required|exists:guests,id',
            'booking_id'      => 'nullable|exists:bookings,id',
            'service_id'      => 'nullable|exists:services,id', // o room_services según el sistema
            'room_id'         => 'nullable|exists:rooms,id',
            'payment_date'    => 'required|date',
            'total_amount'    => 'required|numeric|min:0',
            'payment_method'  => 'required|string|max:50',
        ]);

        try {
            $validator->validate();

            $payment = Payment::create($request->only([
                'guest_id',
                'booking_id',
                'service_id',
                'room_id',
                'payment_date',
                'total_amount',
                'payment_method',
            ]));

            // ✅ Notificar al usuario autenticado
            if ($user = auth()->user()) {
                $user->notify(new PagoRegistrado($payment));
            }

            return redirect()->route('admin.payment.index')
                ->with('success', 'El pago fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function edit(string $id)
    {
        $payment = Payment::with(['guest', 'room', 'service'])->findOrFail($id);
        
        // Obtener los datos necesarios para los selects
        $guests = \App\Models\Guest::all(); // Asegúrate de importar el modelo Guest
        $rooms = \App\Models\Room::all();   // Asegúrate de importar el modelo Room
        $services = \App\Models\Service::all(); // Asegúrate de importar el modelo Service
        $bookings = \App\Models\Booking::all(); // Asegúrate de importar el modelo Booking
        
        return view('Admin.payment.edit', compact(
            'payment', 
            'guests', 
            'rooms', 
            'services',
            'bookings'
        ));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'guest_id'        => 'required|exists:guests,id',
            'booking_id'      => 'nullable|exists:bookings,id',
            'service_id'      => 'nullable|exists:services,id',
            'room_id'         => 'nullable|exists:rooms,id',
            'payment_date'    => 'required|date',
            'total_amount'    => 'required|numeric|min:0',
            'payment_method'  => 'required|string|max:50',
        ]);

        try {
            $validator->validate();

            $payment = Payment::findOrFail($id);
            $payment->update($request->only([
                'guest_id',
                'booking_id',
                'service_id',
                'room_id',
                'payment_date',
                'total_amount',
                'payment_method',
            ]));

            return redirect()->route('admin.payment.index')
                ->with('success', 'El pago fue actualizado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }


    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payment.index')
            ->with('success', 'El pago fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $payments = Payment::with(['guest', 'booking', 'service', 'room'])->get();
        $pdf = Pdf::loadView('admin.payment.pdf', compact('payments'));
        return $pdf->download('reporte_pagos.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PaymentsExport, 'reporte_pagos.xlsx');
    }
}
