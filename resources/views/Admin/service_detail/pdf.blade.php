<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Detalles de Servicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10mm;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .logo img {
            max-width: 180px;
            height: auto;
        }
        h1 {
            text-align: center;
            color: #1a73e8;
            font-size: 20px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #999;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
        }
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('image/el-marquez.png') }}" alt="Logo">
        </div>
    </div>
    <h1>Reporte de Detalles de Servicio</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Empleado</th>
                <th>Reserva</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Total (S/.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->service->name ?? 'N/A' }}</td>
                    <td>{{ $detail->employee->name ?? 'N/A' }}</td>
                    <td>{{ $detail->booking->code ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail->consumption_date)->format('d/m/Y') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión El Marquez Hotel
    </div>
</body>
</html>
