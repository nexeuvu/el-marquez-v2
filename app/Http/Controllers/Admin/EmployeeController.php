<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeesExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('Admin.employee.index');
    }

    public function create()
    {
        return view('admin.employee.create');
    }

    public function consultarDni(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|digits:8',
            'document_type' => 'required|in:DNI,CE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        $dni = $request->input('dni');
        $documentType = $request->input('document_type');
        $url = "https://api.apis.net.pe/v2/reniec/dni?numero={$dni}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('APIS_NET_PE_TOKEN'),
                'Accept' => 'application/json',
            ])->withOptions([
                'verify' => false, // Solo en local
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Consulta DNI empleado', [
                    'dni' => $dni,
                    'document_type' => $documentType,
                    'response' => $data,
                ]);

                return response()->json([
                    'document_type' => $documentType,
                    'dni' => $data['numeroDocumento'] ?? $dni,
                    'name' => $data['nombres'] ?? '',
                    'last_name' => $this->normalizeApellidos($data),
                    'digito_verificador' => $data['digitoVerificador'] ?? '',
                ]);
            } else {
                return response()->json([
                    'error' => 'No se pudo consultar el documento.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error al consultar DNI empleado', [
                'dni' => $dni,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function normalizeApellidos(array $data): string
    {
        $paterno = $data['apellidoPaterno'] ?? '';
        $materno = $data['apellidoMaterno'] ?? '';
        return trim("{$paterno} {$materno}");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:DNI,CE|max:20',
            'dni' => 'required|digits:8|unique:employees,dni',
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'shift' => 'required|string|max:100',
        ]);

        try {
            $validator->validate();

            Employee::create([
                'document_type' => $request->document_type,
                'dni' => $request->dni,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'role' => $request->role,
                'shift' => $request->shift,
                'status' => true,
            ]);

            return redirect()->route('admin.employee.index')
                ->with('success', 'El empleado fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:DNI,CE|max:20',
            'dni' => 'required|digits:8|unique:employees,dni,' . $id,
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'shift' => 'required|string|max:100',
        ]);

        try {
            $validator->validate();

            $employee = Employee::findOrFail($id);
            $employee->update([
                'document_type' => $request->document_type,
                'dni' => $request->dni,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'role' => $request->role,
                'shift' => $request->shift,
            ]);

            return redirect()->route('admin.employee.index')
                ->with('success', 'El empleado se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['status' => false]);

        return redirect()->route('admin.employee.index')
            ->with('success', 'El empleado fue dado de baja correctamente.');
    }

    public function exportPdf()
    {
        $employees = Employee::where('status', true)->orderBy('last_name')->get();
        $pdf = Pdf::loadView('admin.employee.pdf', compact('employees'));
        return $pdf->download('reporte_empleados.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'reporte_empleados.xlsx');
    }
}
