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

    <div class="table-responsive">
        <table id="prestamosTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Personal</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <th>Cantidad Total</th>
                    <th>Observación</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prestamos as $prestamo)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $prestamo->personal->nombres ?? 'N/A' }} {{ $prestamo->personal->a_paterno ?? '' }}</td>
                        <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                        <td>{{ $prestamo->fecha_devolucion ? $prestamo->fecha_devolucion->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $prestamo->cantidad_total }}</td>
                        <td>{{ $prestamo->observacion }}</td>
                        <td>
                            <form action="{{ route('prestamos.destroy', $prestamo->id) }}" method="POST">
                                <a class="btn btn-sm btn-primary" href="{{ route('prestamos.show', $prestamo->id) }}">
                                    <i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}
                                </a>
                                <a class="btn btn-sm btn-success" href="{{ route('prestamos.edit', $prestamo->id) }}">
                                    <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('¿Estás seguro de eliminar?')) { this.closest('form').submit(); }">
                                    <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {!! $prestamos->withQueryString()->links() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#prestamosTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es.json' // Añadir traducción al español si es necesario
                }
            });
        });
    </script>
@stop
