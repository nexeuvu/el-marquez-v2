<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BookingsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Booking::with(['guest', 'room'])
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Huésped' => $item->guest->name . ' ' . $item->guest->last_name,
                    'Habitación' => $item->room->number,
                    'Fecha de Entrada' => $item->start_date->format('Y-m-d'),
                    'Fecha de Salida' => $item->end_date->format('Y-m-d'),
                    'Precio Pagado (S/.)' => number_format($item->price_pay, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Huésped',
            'Habitación',
            'Fecha de Entrada',
            'Fecha de Salida',
            'Precio Pagado (S/.)',
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
            // Estilo de encabezado (fila 1)
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
