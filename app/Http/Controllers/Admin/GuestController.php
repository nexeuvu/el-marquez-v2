<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GuestsExport;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class GuestController extends Controller
{
    public function index()
    {
        return view('Admin.guest.index');
    }

    public function create()
    {
        return view('admin.guest.create');
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
                'verify' => false, // Solo para pruebas locales
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Respuesta de la API para documento', [
                    'dni' => $dni,
                    'document_type' => $documentType,
                    'response' => $data
                ]);

                $normalizedData = [
                    'document_type' => $documentType,
                    'dni' => $data['numeroDocumento'] ?? $dni,
                    'name' => $data['nombres'] ?? '',
                    'last_name' => $this->normalizeApellidos($data),
                    'digito_verificador' => $data['digitoVerificador'] ?? '',
                ];

                return response()->json($normalizedData);
            } else {
                $error = $response->json()['error'] ?? 'Respuesta no válida';
                Log::error('Error en la consulta a la API', [
                    'dni' => $dni,
                    'status' => $response->status(),
                    'error' => $error
                ]);
                return response()->json([
                    'error' => 'No se pudo consultar el documento: ' . $error
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Excepción al consultar la API', [
                'dni' => $dni,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'error' => 'Error al consultar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    private function normalizeApellidos(array $data): string
    {
        if (isset($data['apellidos']) && !empty($data['apellidos'])) {
            return $data['apellidos'];
        }

        $apellidoPaterno = $data['apellidoPaterno'] ?? '';
        $apellidoMaterno = $data['apellidoMaterno'] ?? '';
        $apellidos = trim("{$apellidoPaterno} {$apellidoMaterno}");

        if (empty($apellidos)) {
            $apellidos = $data['nombreCompleto'] ?? $data['apellido'] ?? $data['apellidos_completos'] ?? '';
            if (!empty($apellidos) && isset($data['nombres'])) {
                $apellidos = str_replace($data['nombres'], '', $apellidos);
                $apellidos = trim($apellidos);
            }
        }

        return $apellidos;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|in:DNI,CE|max:20',
            'dni' => 'required|string|digits:8|unique:guests,dni',
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $validator->validate();

            Guest::create([
                'document_type' => $request->document_type,
                'dni' => $request->dni,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return redirect()->route('admin.guest.index')
                ->with('success', 'El huésped fue registrado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|in:DNI,CE|max:20',
            'dni' => 'required|string|digits:8|unique:guests,dni,' . $id,
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $validator->validate();

            $guest = Guest::findOrFail($id);
            $guest->update([
                'document_type' => $request->document_type,
                'dni' => $request->dni,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return redirect()->route('admin.guest.index')
                ->with('success', 'El huésped se actualizó correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Guest::findOrFail($id)->delete();
        return redirect()->route('admin.guest.index')
            ->with('success', 'El huésped fue eliminado correctamente.');
    }

    public function exportPdf()
    {
        $guests = Guest::orderBy('last_name')->get();
        $pdf = Pdf::loadView('admin.guest.pdf', compact('guests'));
        return $pdf->download('reporte_huéspedes.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new GuestsExport, 'reporte_huéspedes.xlsx');
    }
}