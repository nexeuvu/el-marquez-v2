<?php

namespace App\Http\Controllers\Admin;

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
        return view('Admin.service-detail-show', compact('serviceDetail'));
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
        $details = Service_detail::with(['service', 'employee', 'booking'])->get();
        $pdf = Pdf::loadView('admin.service_detail.pdf', compact('details'));
        return $pdf->download('reporte_detalle_servicios.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ServiceDetailsExport, 'reporte_detalle_servicios.xlsx');
    }
}
