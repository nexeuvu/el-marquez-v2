<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Employee::where('status', true)
            ->select('id', 'document_type', 'dni', 'name', 'last_name', 'role', 'shift', 'status')
            ->orderBy('last_name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'document_type' => $item->document_type,
                    'dni' => $item->dni,
                    'name' => $item->name,
                    'last_name' => $item->last_name,
                    'role' => $item->role,
                    'shift' => $item->shift,
                    'status' => $item->status ? 'Activo' : 'Inactivo'
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Tipo Documento',
            'DNI',
            'Nombres',
            'Apellidos',
            'Cargo',
            'Turno',
            'Estado'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para encabezado (fila 1)
        $highestColumn = $sheet->getHighestColumn();

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']]
            ],
            // Bordes para toda la tabla
            "A1:{$highestColumn}" . $sheet->getHighestRow() => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FF000000']]
                ]
            ],
        ];
    }
}
