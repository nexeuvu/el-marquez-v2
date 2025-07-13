<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ServicesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Service::where('status', true)
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Nombre' => is_array($item->name) ? implode(', ', $item->name) : $item->name,
                    'Descripción' => $item->description,
                    'Precio (S/.)' => number_format($item->price, 2),
                    'Estado' => $item->status ? 'Activo' : 'Inactivo',
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Nombre',
            'Descripción',
            'Precio (S/.)',
            'Estado',
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

        // Forzar autoajuste de columnas (opcional si ShouldAutoSize no basta)
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            // Encabezado estilizado
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1A73E8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Bordes en todo el rango
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
