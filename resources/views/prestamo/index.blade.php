@extends('adminlte::page')

@section('title', 'Gestión de Préstamos')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-file-earmark-text"></i> {{ __('Préstamos') }}</h1>
        <a href="{{ route('prestamos.create') }}" class="btn btn-primary btn-md" data-placement="left">
            {{ __('Crear Nuevo') }}
        </a>
    </div>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success mb-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    
    <div class="card">
        <div class="card-body">
            <!-- Formulario de Filtros -->
            <form method="GET" action="{{ route('prestamos.index') }}" class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="personal_name" class="block text-sm font-medium text-gray-700">Nombre del Profesor</label>
                        <input type="text" name="personal_name" id="personal_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('personal_name') }}">
                    </div>
        
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Préstamo (Desde)</label>
                        <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('start_date') }}">
                    </div>
        
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Préstamo (Hasta)</label>
                        <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('end_date') }}">
                    </div>
        
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Seleccionar Estado</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="desactivo" {{ request('estado') == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                        </select>
                    </div>
                </div>
        
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">Buscar</button>
                    <a href="{{ route('prestamos.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm hover:bg-gray-600">Limpiar Filtros</a>
                </div>
            </form>

            <!-- Separador -->
            <hr class="my-6 border-t-2 border-gray-200">
    
            <!-- Tabla de Préstamos -->
            <div class="table-responsive">
                <table id="prestamosTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Personal</th>
                            <th>Fecha de Préstamo</th>
                            <th>Fecha de Devolución</th>
                            <th>Fecha de Devolución Marcada</th>
                            <th>Cantidad Total</th>
                            <th>Observación</th>
                            <th>Recurso</th>
                            <th>Estado</th> <!-- Nueva columna para mostrar el estado -->
                            <th>Opciones</th> <!-- Nueva columna para las opciones -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestamos as $prestamo)
                            @foreach ($prestamo->detalleprestamos as $detalle)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $prestamo->personal->nombres ?? 'N/A' }} {{ $prestamo->personal->a_paterno ?? '' }}</td>
                                    <td>{{ $prestamo->fecha_prestamo }}</td>
                                    <td>{{ $prestamo->fecha_devolucion }}</td>
                                    <td>{{ $prestamo->fecha_devolucion_real }}</td>
                                    <td>{{ $prestamo->cantidad_total }}</td>
                                    <td>{{ $prestamo->observacion }}</td>
                                    <td>{{ $detalle->recurso->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <!-- Mostrar el estado (activo o desactivo) -->
                                        @if ($prestamo->estado == 'activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Desactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Botón de opciones: Marcar como devuelto si el estado es activo -->
                                        @if ($prestamo->estado == 'activo')
                                            <form action="{{ route('prestamos.markAsReturned', $prestamo->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres marcar como devuelto el recurso?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success ">Marcar como devuelto</button>
                                            </form>
                                        @else
                                            <span class="badge badge-secondary">Devuelto</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    {!! $prestamos->withQueryString()->links() !!}
@stop



@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @viteReactRefresh
    @vite('resources/js/main.jsx')

    
    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        new DataTable('#prestamosTable', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });



    </script>
@stop