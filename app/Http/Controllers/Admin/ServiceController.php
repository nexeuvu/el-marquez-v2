<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ServicesExport;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    public function index()
    {
        return view('Admin.service.index');
    }

    public function store(Request $request)
    {

        // Normalizar name
        if (is_array($request->name)) {
            $request->merge([
                'name' => implode(', ', $request->name),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // <-- solo string aquí
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Service::create([
                'name' => is_array($request->name) ? implode(',', $request->name) : $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'status' => true, // Activo por defecto
            ]);

            return redirect()->route('admin.service.index')
                ->with('success', 'El servicio fue registrado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $service = Service::findOrFail($id);
            $service->update([
                'name' => is_array($request->name) ? implode(',', $request->name) : $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'status' => true, // Activo por defecto
            ]);

            return redirect()->route('admin.service.index')
                ->with('success', 'El servicio fue actualizado correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->update(['status' => false]); // Eliminación lógica

        return redirect()->route('admin.service.index')
            ->with('success', 'El servicio fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        // Obtener sin ordenar por 'name' directamente en SQL
        $services = Service::all();

        // Ordenar en memoria (PHP) si es necesario
        $services = $services->sortBy(function ($item) {
            return is_array($item->name) ? implode(', ', $item->name) : $item->name;
        });

        $pdf = Pdf::loadView('admin.service.pdf', compact('services'));
        return $pdf->download('reporte_servicios.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ServicesExport, 'reporte_servicios.xlsx');
    }
}
