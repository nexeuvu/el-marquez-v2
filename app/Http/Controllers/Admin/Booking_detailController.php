<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Booking_detailsExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Booking_detail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Booking_detailController extends Controller
{
    /**
     * Mostrar vista principal de detalles de reserva
     */
    public function index()
    {
        return view('Admin.booking_detail.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'room_id' => 'required|exists:rooms,id',
            'number_night' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        Booking_detail::create($validated);

        return redirect()->route('admin.booking_detail.index')
            ->with('success', 'Detalle de reserva registrado correctamente.');
    }
    

    /**
     * Exportar PDF con detalles de reservas
     */
    public function exportPdf()
    {
        $bookings = Booking::with(['guest', 'bookingDetails.room'])->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.booking_detail.pdf', compact('bookings'));
        return $pdf->download('reporte_detalle_reservas.pdf');
    }

    /**
     * Exportar Excel con detalles de reservas
     */
    public function exportExcel()
    {
        return Excel::download(new Booking_detailsExport, 'reporte_detalle_reservas.xlsx');
    }

    /**
     * Imprimir boleta/resumen de una reserva específica
     */
    public function generateBoletaPrint(Booking $booking)
    {
        $booking->load(['user', 'guest', 'bookingDetails.room']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.booking_detail.summary_pdf', compact('booking'));
        $pdf->setPaper([0, 0, 340.36, $pdf->getDomPDF()->getCanvas()->get_height()], 'portrait');

        return $pdf->stream('resumen-reserva-' . $booking->id . '.pdf');
    }
}
