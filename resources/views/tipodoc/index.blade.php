@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-person-vcard"></i> Documentos de identidad</h1>
                
        <a href="{{ route('tipodocs.create') }}" class="btn btn-primary btn-md" data-placement="left">
            {{ __('Crear Nuevo') }}
        </a>
    </div>

@stop

@section('content')

    <p class="mb-4">Aquí se mostrarán los tipos de documentos disponibles.</p>

    <table id="tipodocsTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Descripcion</th>
                <th>Abreviatura</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tipodocs as $tipodoc)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $tipodoc->descripcion }}</td>
                    <td>{{ $tipodoc->abreviatura }}</td>
                    <td>
                        <form action="{{ route('tipodocs.destroy', $tipodoc->id) }}" method="POST">
                            <a class="btn btn-sm btn-primary" href="{{ route('tipodocs.show', $tipodoc->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                            <a class="btn btn-sm btn-success" href="{{ route('tipodocs.edit', $tipodoc->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de que desea eliminar este documento?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        new DataTable('#tipodocsTable', {
            responsive: true,
        });
    </script>
@stop
