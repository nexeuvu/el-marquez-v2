<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PaymentsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Payment::with(['guest', 'booking', 'service', 'room'])
            ->orderByDesc('id') // ordenado por ID de forma descendente
            ->get()
            ->map(function ($payment) {
                return [
                    'ID' => $payment->id,
                    'Huésped' => optional($payment->guest)->name . ' ' . optional($payment->guest)->last_name,
                    'Reserva' => optional($payment->booking)->id ?? '—',
                    'Servicio' => optional($payment->service)->name ?? '—',
                    'Habitación' => optional($payment->room)->number ?? '—',
                    'Fecha de Pago' => optional($payment->payment_date)->format('Y-m-d'),
                    'Método de Pago' => $payment->payment_method,
                    'Total (S/.)' => number_format($payment->total_amount, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Huésped',
            'Reserva',
            'Servicio',
            'Habitación',
            'Fecha de Pago',
            'Método de Pago',
            'Total (S/.)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Autoajuste de altura de filas
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }

        // Autoajuste de ancho de columnas (por si ShouldAutoSize no basta)
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
            // Bordes para toda la tabla
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
