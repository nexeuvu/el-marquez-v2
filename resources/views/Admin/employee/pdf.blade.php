<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleados</title>
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
    <h1>Reporte de Empleados</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo de Documento</th>
                <th>DNI</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Rol</th>
                <th>Turno</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $index => $employee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee->document_type }}</td>
                    <td>{{ $employee->dni }}</td>
                    <td>{{ $employee->last_name }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->role }}</td>
                    <td>{{ $employee->shift }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión El Marquez Hotel
    </div>
</body>
</html>
