<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Detalles de Reservas</title>
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
            max-width: 200px;
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
            font-size: 12px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
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
    <h1>Reporte de Detalles de Reservas</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Huésped</th>
                <th>Habitación</th>
                <th>Noches</th>
                <th>Precio Unitario (S/.)</th>
                <th>Total (S/.)</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach ($bookings as $booking)
                @foreach ($booking->bookingDetails as $detail)
                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>{{ $booking->guest->name }} {{ $booking->guest->last_name }}</td>
                        <td>{{ $detail->room->number ?? '-' }}</td>
                        <td>{{ $detail->number_night }}</td>
                        <td>{{ number_format($detail->unit_price, 2) }}</td>
                        <td>{{ number_format($detail->number_night * $detail->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión El Marquez Hotel
    </div>
</body>
</html>
