<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pagos</title>
    <style>
        /* Estilos heredados con cambios mínimos */
        :root {
            --primary-color: #1a73e8;
            --header-bg: #e0e0e0;
            --even-row-bg: #f5f5f5;
            --text-color: #333;
            --footer-color: #777;
            --border-color: #ccc;
            --table-font-size: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 15mm;
            color: var(--text-color);
            font-size: var(--table-font-size);
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 140px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        h1 {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid var(--border-color);
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: var(--header-bg);
            text-transform: uppercase;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: var(--even-row-bg);
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .amount {
            font-weight: bold;
            color: #0056b3;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: var(--footer-color);
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('image/el-marquez.png') }}" alt="Logo">
        </div>
        <h1>Reporte de Pagos</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Huésped</th>
                <th>Fecha</th>
                <th>Método</th>
                <th>Reserva</th>
                <th>Servicio</th>
                <th>Habitación</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td class="text-center">#{{ $payment->id }}</td>
                    <td>
                        {{ $payment->guest->name ?? '—' }} {{ $payment->guest->last_name ?? '' }}<br>
                        <small>{{ $payment->guest->dni ?? '' }}</small>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $payment->payment_method }}</td>
                    <td class="text-center">{{ $payment->booking->id ?? '—' }}</td>
                    <td class="text-center">{{ $payment->service->name ?? '—' }}</td>
                    <td class="text-center">{{ $payment->room->number ?? '—' }}</td>
                    <td class="text-right amount">S/ {{ number_format($payment->total_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        <em>No hay pagos registrados.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión Vida Saludable
    </div>
</body>
</html>
