@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-tags-fill"></i> Categorías</h1>

                
            <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-md"  data-placement="left">
                {{ __('Crear Nuevo') }}
            </a>
            
    </div>

    
    
@stop

@section('content')

    <p class="mb-4">Aquí se mostrarán las categorías que tendrán los recursos</p>


    <div id="app"></div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">

                <table id="example" class="table table-striped table-bordered mt-2 table-hover mb-3" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                                                    <tr>
                                                        <td>{{ $categoria->id }}</td>
                                                        
                                                    <td >{{ $categoria->nombre }}</td>
                                                    <td >{{ $categoria->descripcion }}</td>
            
                                                        <td>
                                                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
                                                                <a class="btn btn-sm btn-primary " href="{{ route('categorias.show', $categoria->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                                <a class="btn btn-sm btn-success" href="{{ route('categorias.edit', $categoria->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    @viteReactRefresh
    @vite('resources/js/main.jsx')
    
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>

    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    
    <script>
        new DataTable('#example', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });
    </script>
@stop



































