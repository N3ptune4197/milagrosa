@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-tags-fill"></i> <span class="font-semibold">Categorías</span></h1>
        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoriaModal" onclick="clearForm()">
            {{ __('Crear Nueva Categoría') }}
        </button>
    </div>
@stop

@section('content')

    <p class="mb-4">Aquí se mostrarán las categorías que tendrán los recursos</p>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-4">
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
                                <td>{{ $categoria->nombre }}</td>
                                <td>{{ $categoria->descripcion }}</td>
                                <td>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('categorias.show', $categoria->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                        <a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="editCategoria({{ $categoria->id }})"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de eliminar esta categoría?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Creación/Edición de Categorías -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center" role="document">
        <div class="modal-content rounded-xl border-4 border-black">
            <!-- Cabecera del modal -->
            <div class="modal-header bg-blue-500 text-white flex justify-between items-center p-4 border-b-10 border-blue-800">
                <h5 class="modal-title text-center flex-1 font-bold text-lg" id="categoriaModalLabel">{{ __('Agregar / Editar Categoría') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="categoriaForm" method="POST" action="{{ route('categorias.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
                        <input type="text" name="nombre" id="nombre" class="block w-full mt-1 py-2 pl-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nombre de la Categoría" required>
                    </div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">{{ __('Descripción') }}</label>
                        <textarea name="descripcion" id="descripcion" class="block w-full mt-1 py-2 pl-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3" placeholder="Descripción de la Categoría" required></textarea>
                    </div>
                    <!-- Input oculto para ID de la categoría -->
                    <input type="hidden" name="categoria_id" id="categoria_id">
                    
                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Guardar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Creación/Edición de Categorías -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center" role="document">
        <div class="modal-content rounded-xl border-4 border-black">
            <!-- Cabecera del modal -->
            <div class="modal-header bg-blue-500 text-white flex justify-between items-center p-4 border-b-10 border-blue-800">
                <h5 class="modal-title text-center flex-1 font-bold text-lg" id="categoriaModalLabel">{{ __('Agregar / Editar Categoría') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="categoriaForm" method="POST" action="{{ route('categorias.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
                        <input type="text" name="nombre" id="nombre" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nombre de la Categoría" required>
                    </div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">{{ __('Descripción') }}</label>
                        <textarea name="descripcion" id="descripcion" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3" placeholder="Descripción de la Categoría" required></textarea>
                    </div>
                    <!-- Input oculto para ID de la categoría -->
                    <input type="hidden" name="categoria_id" id="categoria_id">
                    
                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Guardar') }}</button>
                    </div>
                </form>
            </div>
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

    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
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

    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        function clearForm() {
            $('#categoriaForm')[0].reset();
            $('#categoriaForm').attr('action', '{{ route("categorias.store") }}');
            $('#categoriaForm').find('input[name="_method"]').remove();
        }

        // Llenar el modal para editar una categoría
        function editCategoria(id) {
            $.ajax({
                url: '/categorias/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#categoria_id').val(response.id);
                    $('#nombre').val(response.nombre);
                    $('#descripcion').val(response.descripcion);

                    // Cambiar el action del formulario para editar
                    $('#categoriaForm').attr('action', '/categorias/' + id);
                    $('#categoriaForm').append('<input type="hidden" name="_method" value="PATCH">');
                    $('#categoriaModal').modal('show');
                },
                error: function(xhr) {
                    console.error("Error al obtener los datos de la categoría: ", xhr.responseText);
                }
            });
        }
    </script>
    
    <script>
        new DataTable('#example', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });
    </script>
@stop












