<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room_service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class Room_serviceController extends Controller
{
    /**
     * Mostrar vista de listado de servicios de habitación.
     */
    public function index()
    {
        return view('Admin.room_service.index');
    }

    /**
     * Almacenar un nuevo servicio de habitación.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id',
            'room_id'       => 'required|exists:rooms,id',
            'service_type'  => 'required|string|max:255',
            'service_date'  => 'required|date',
            'observations'  => 'nullable|string',
        ]);

        try {
            $validator->validate();

            Room_service::create($request->only([
                'employee_id',
                'room_id',
                'service_type',
                'service_date',
                'observations',
            ]));

            return redirect()->route('admin.room_service.index')
                ->with('success', 'El servicio fue registrado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    /**
     * Actualizar un servicio de habitación existente.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id',
            'room_id'       => 'required|exists:rooms,id',
            'service_type'  => 'required|string|max:255',
            'service_date'  => 'required|date',
            'observations'  => 'nullable|string',
        ]);

        try {
            $validator->validate();

            $service = Room_service::findOrFail($id);
            $service->update($request->only([
                'employee_id',
                'room_id',
                'service_type',
                'service_date',
                'observations',
            ]));

            return redirect()->route('admin.room_service.index')
                ->with('success', 'El servicio fue actualizado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    /**
     * Eliminar un servicio de habitación.
     */
    public function destroy(string $id)
    {
        $service = Room_service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.room_service.index')
            ->with('success', 'El servicio fue eliminado correctamente.');
    }

    /**
     * Exportar lista a PDF.
     */
    public function exportPdf()
    {
        $room_services = Room_service::with(['employee', 'room'])->get();
        $pdf = Pdf::loadView('admin.room_service.pdf', compact('room_services'));
        return $pdf->download('reporte_servicios_habitacion.pdf');
    }

    /**
     * Exportar lista a Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new RoomServicesExport, 'reporte_servicios_habitacion.xlsx');
    }
}
