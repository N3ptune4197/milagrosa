@extends('adminlte::page')

@section('title', 'Personals Management')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-person"></i> Personal</h1>
        <a href="{{ route('personals.create') }}" class="btn btn-primary btn-md" data-placement="left">
            {{ __('Create New') }}
        </a>
    </div>
@stop

@section('content')
    <p class="mb-4">Here you can manage the personals information.</p>

    <table id="personalsTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nombres</th>
                <th>A. Paterno</th>
                <th>A. Materno</th>
                <th>Telefono</th>
                <th>Documento</th>
                <th>Nro Documento</th>
                <th>Cargo</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personals as $personal)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $personal->nombres }}</td>
                    <td>{{ $personal->a_paterno }}</td>
                    <td>{{ $personal->a_materno }}</td>
                    <td>{{ $personal->telefono }}</td>
                    <td>{{ $personal->tipodoc->abreviatura }}</td>
                    <td>{{ $personal->nro_documento }}</td>
                    <td>{{ $personal->cargo }}</td>
                    <td>
                        <form action="{{ route('personals.destroy', $personal->id) }}" method="POST">
                            <a class="btn btn-sm btn-primary" href="{{ route('personals.show', $personal->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                            <a class="btn btn-sm btn-success" href="{{ route('personals.edit', $personal->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                <i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}
                            </button>
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
        new DataTable('#personalsTable', {
            responsive: true,
        });
    </script>
@stop
