<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GuestsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Guest::orderBy('last_name')
            ->select('id', 'document_type', 'dni', 'name', 'last_name', 'phone', 'email')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Tipo de Documento' => $item->document_type,
                    'DNI' => $item->dni,
                    'Nombres' => $item->name,
                    'Apellidos' => $item->last_name,
                    'Teléfono' => $item->phone,
                    'Correo Electrónico' => $item->email ?? '—',
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Tipo de Documento',
            'DNI',
            'Nombres',
            'Apellidos',
            'Teléfono',
            'Correo Electrónico',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Ajuste automático del alto de filas
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }

        return [
            // Encabezado (fila 1)
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Bordes para toda la tabla
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FF000000']],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}
