<?php

namespace App\Exports;

use App\Models\Room_service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Room_servicesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Room_service::with(['employee', 'room'])
            ->orderBy('service_date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Empleado' => $item->employee->name . ' ' . $item->employee->last_name,
                    'Habitación' => $item->room->number,
                    'Tipo de Servicio' => $item->service_type,
                    'Fecha del Servicio' => $item->service_date->format('Y-m-d'),
                    'Observaciones' => $item->observations ?? '—',
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Empleado',
            'Habitación',
            'Tipo de Servicio',
            'Fecha del Servicio',
            'Observaciones'
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
            // Estilo de encabezado
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Bordes para todo el contenido
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FF000000']],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}
