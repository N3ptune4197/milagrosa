<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Préstamos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo_colegio.png') }}" alt="Logo" class="logo">
        <h2>Reporte de Préstamos</h2>
        <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        <p>Dirección: Av. Principal 123, Ciudad</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Personal</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Fecha Devolución Real</th>
                <th>Observación</th>
                <th>Recurso</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prestamos as $prestamo)
                @foreach ($prestamo->detalleprestamos as $detalle)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $prestamo->personal->nombres ?? 'N/A' }} {{ $prestamo->personal->a_paterno ?? '' }}</td>
                        <td>{{ $prestamo->fecha_prestamo }}</td>
                        <td>{{ $detalle->fecha_devolucion }}</td>
                        <td>{{ $prestamo->fecha_devolucion_real }}</td>
                        <td>{{ $prestamo->observacion }}</td>
                        <td>{{ $detalle->recurso->nro_serie ?? 'N/A' }} ({{ $detalle->recurso->categoria->nombre ?? 'Sin categoría' }})</td>
                        <td>{{ $prestamo->estado == 'activo' ? 'Activo' : 'Devuelto' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
