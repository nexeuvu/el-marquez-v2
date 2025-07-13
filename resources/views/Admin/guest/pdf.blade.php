<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Huéspedes</title>
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
    <h1>Reporte de Huéspedes</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo de Documento</th>
                <th>DNI</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($guests as $index => $guest)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $guest->document_type }}</td>
                    <td>{{ $guest->dni }}</td>
                    <td>{{ $guest->last_name }}</td>
                    <td>{{ $guest->name }}</td>
                    <td>{{ $guest->phone }}</td>
                    <td>{{ $guest->email ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión El Marquez Hotel
    </div>
</body>
</html>
