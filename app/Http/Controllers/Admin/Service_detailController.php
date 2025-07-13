<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Service_detailsExport;
use App\Http\Controllers\Controller;
use App\Models\Service_detail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class Service_detailController extends Controller
{
    public function index()
    {
        return view('Admin.service_detail.index');
    }

    public function show($id)
    {
        $serviceDetail = Service_detail::with(['service', 'employee', 'booking'])->findOrFail($id);
        return view('Admin.service_detail.index', ['showComponent' => true, 'detailId' => $id]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'       => 'required|exists:services,id',
            'employee_id'      => 'required|exists:employees,id',
            'booking_id'       => 'required|exists:bookings,id',
            'quantity'         => 'required|integer|min:1',
            'consumption_date' => 'required|date',
            'total'            => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Service_detail::create([
                'service_id'       => $request->service_id,
                'employee_id'      => $request->employee_id,
                'booking_id'       => $request->booking_id,
                'quantity'         => $request->quantity,
                'consumption_date' => $request->consumption_date,
                'total'            => $request->total,
            ]);

            return redirect()->route('admin.service_detail.index')
                ->with('success', 'El detalle del servicio fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'service_id'       => 'required|exists:services,id',
            'employee_id'      => 'required|exists:employees,id',
            'booking_id'       => 'required|exists:bookings,id',
            'quantity'         => 'required|integer|min:1',
            'consumption_date' => 'required|date',
            'total'            => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $detail = Service_detail::findOrFail($id);
            $detail->update([
                'service_id'       => $request->service_id,
                'employee_id'      => $request->employee_id,
                'booking_id'       => $request->booking_id,
                'quantity'         => $request->quantity,
                'consumption_date' => $request->consumption_date,
                'total'            => $request->total,
            ]);

            return redirect()->route('admin.service_detail.index')
                ->with('success', 'El detalle del servicio fue actualizado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $detail = Service_detail::findOrFail($id);
        $detail->delete();

        return redirect()->route('admin.service_detail.index')
            ->with('success', 'El detalle del servicio fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        try {
            // Verifica que existan datos
            $details = Service_detail::with(['service', 'employee', 'booking'])->get();
            
            if($details->isEmpty()) {
                return back()->with('error', 'No hay datos para generar el reporte');
            }

            // Verifica que la vista exista
            if (!view()->exists('admin.service_detail.pdf')) {
                throw new \Exception("La vista del PDF no existe");
            }

            $pdf = Pdf::loadView('admin.service_detail.pdf', compact('details'));
            
            // Para debugging, puedes usar stream() primero
            return $pdf->stream('reporte_detalle_servicios.pdf');
            // Luego cambiar a download() cuando funcione
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar PDF: '.$e->getMessage());
        }
    }

    public function exportExcel()
    {
        return Excel::download(new Service_detailsExport, 'reporte_detalle_servicios.xlsx');
    }
}
