<?php

namespace App\Exports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RoomsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Room::where('status', true)
            ->orderBy('number')
            ->select('id', 'number', 'room_type', 'price', 'status')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Número' => $item->number,
                    'Tipo de Habitación' => $item->room_type,
                    'Precio (S/.)' => number_format($item->price, 2),
                    'Estado' => $item->status ? 'Activo' : 'Inactivo'
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Número',
            'Tipo de Habitación',
            'Precio (S/.)',
            'Estado'
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
            // Encabezado
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
