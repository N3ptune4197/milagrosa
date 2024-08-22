@extends('adminlte::page')

@section('title', 'Recursos')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-pc-display-horizontal"></i> Recursos</h1>
        <a href="{{ route('recursos.create') }}" class="btn btn-primary btn-md" data-placement="left">
            {{ __('Crear Nuevo') }}
        </a>
    </div>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="recursosTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Modelo</th>
                            <th>Nro Serie</th>
                            <th>Marca</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recursos as $recurso)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $recurso->nombre }}</td>
                                <td>{{ $recurso->categoria->nombre ?? 'N/A' }}</td>
                                <td>{{ $recurso->estadoDescripcion }}</td> <!-- Cambié para usar el accessor -->
                                <td>{{ $recurso->fecha_registro->format('d/m/Y') }}</td>
                                <td>{{ $recurso->modelo }}</td>
                                <td>{{ $recurso->nro_serie }}</td>
                                <td>{{ $recurso->marca->nombre ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('recursos.destroy', $recurso->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('recursos.show', $recurso->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('recursos.edit', $recurso->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de que desea eliminar este recurso?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {!! $recursos->withQueryString()->links() !!}
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script>
        new DataTable('#recursosTable', {
            responsive: true,
        });
    </script>
@stop
