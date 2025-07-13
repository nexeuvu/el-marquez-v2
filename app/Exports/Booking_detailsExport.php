<?php

namespace App\Exports;

use App\Models\Booking_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Booking_detailsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Booking_detail::with(['booking.guest', 'room'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Huésped' => $item->booking->guest->name . ' ' . $item->booking->guest->last_name,
                    'Habitación' => $item->room->number,
                    'Noches' => $item->number_night,
                    'Precio Unitario (S/.)' => number_format($item->unit_price, 2),
                    'Total (S/.)' => number_format($item->number_night * $item->unit_price, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Huésped',
            'Habitación',
            'Noches',
            'Precio Unitario (S/.)',
            'Total (S/.)'
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
            // Estilo encabezado
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Bordes generales
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FF000000']],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}
