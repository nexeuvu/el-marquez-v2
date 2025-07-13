<?php

namespace App\Exports;

use App\Models\Service_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Service_detailsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Service_detail::with(['service', 'employee', 'booking.guest'])
            ->orderBy('consumption_date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Servicio' => $item->service->name ?? '—',
                    'Empleado' => $item->employee->name . ' ' . $item->employee->last_name,
                    'Huésped (Reserva)' => $item->booking->guest->name . ' ' . $item->booking->guest->last_name,
                    'Cantidad' => $item->quantity,
                    'Fecha de Consumo' => $item->consumption_date->format('Y-m-d'),
                    'Total (S/.)' => number_format($item->total, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Servicio',
            'Empleado',
            'Huésped (Reserva)',
            'Cantidad',
            'Fecha de Consumo',
            'Total (S/.)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Ajustar altura automática de filas
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }

        // Autoajuste manual por cada columna
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            // Encabezado visualmente destacado
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Bordes generales en todo el contenido
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}
