<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Préstamos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        /* Estilos para el encabezado */
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
            padding-top: 60px; /* Aumentar separación del encabezado */
            position: relative;
        }

        .header img {
            position: absolute;
            left: 20px;
            top: 10px; /* Mantener imagen cerca del margen superior */
            width: 100px;
        }

        .header div {
            margin-top: 30px; /* Aumentar separación entre la imagen y el texto */
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .meta {
            text-align: right;
            margin-bottom: 10px;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilos para el pie de página */
        .footer {
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 2px solid #000;
            padding-top: 10px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <!-- Encabezado con logo y detalles del colegio -->
    <div class="header">
        <img src="{{ public_path('imagenes/medalla-logo.png') }}" alt="Logo del Colegio">
        <div>
            <h1>I.E N° 11009 Virgen de la Medalla Milagrosa</h1>
            <p>Húsares de Junín 520, José Leonardo Ortiz 14002</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <h2>Reporte de Préstamos</h2>

        <div class="meta">
            <p><strong>Generado por:</strong> {{ Auth::user()->name }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Personal</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <th>Fecha de Devolución Marcada</th>
                    <th>Observación</th>
                    <th>Recurso</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $numero = 1; // Inicializamos el contador
                @endphp
                @foreach ($prestamos as $prestamo)
                    @foreach ($prestamo->detalleprestamos as $detalle)
                        <tr>
                            <td>{{ $numero++ }}</td> <!-- Incrementamos la variable en cada iteración -->
                            <td>{{ $prestamo->personal->nombres ?? 'N/A' }} {{ $prestamo->personal->a_paterno ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($detalle->fecha_devolucion)->format('d/m/Y') }}</td>
                            <td>{{ $prestamo->fecha_devolucion_real ? \Carbon\Carbon::parse($prestamo->fecha_devolucion_real)->format('d/m/Y') : 'No marcada' }}</td>
                            <td>{{ $prestamo->observacion ?? 'Sin observaciones' }}</td>
                            <td>
                                {{ $detalle->recurso->nro_serie ?? 'N/A' }}
                                @if($detalle->recurso->categoria)
                                    ({{ $detalle->recurso->categoria->nombre ?? 'Sin categoría' }})
                                @endif
                            </td>
                            <td>{{ ucfirst($prestamo->estado) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        <p>Este documento fue generado automáticamente por el sistema de préstamos del Colegio. {{ \Carbon\Carbon::now()->year }} &copy; Todos los derechos reservados.</p>
    </div>
</body>

</html>
