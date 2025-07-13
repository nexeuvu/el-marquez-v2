<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Habitaciones</title>
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
    <h1>Reporte de Habitaciones</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Número</th>
                <th>Tipo de Habitación</th>
                <th>Precio (S/.)</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $index => $room)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $room->number }}</td>
                    <td>{{ $room->room_type }}</td>
                    <td>{{ number_format($room->price, 2) }}</td>
                    <td>{{ $room->status ? 'Activa' : 'Inactiva' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión El Marquez Hotel
    </div>
</body>
</html>
