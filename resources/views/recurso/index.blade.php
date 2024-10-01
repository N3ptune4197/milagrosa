@extends('adminlte::page')

@section('title', 'Recursos')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-pc-display-horizontal"></i> Recursos</h1>
        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recursoModal" onclick="clearForm()">
            {{ __('Crear Nuevo') }}
        </button>
    </div>
@stop

@section('content')
    <p class="mb-4">Aquí puedes gestionar los recursos registrados.</p>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="recursosTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nro Serie</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recursos as $recurso)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $recurso->nro_serie }}</td>
                                <td>{{ $recurso->categoria->nombre ?? 'N/A' }}</td>
                                <td>{{ $recurso->marca->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if($recurso->estado == 1)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Disponible</span>
                                    @elseif($recurso->estado == 2)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">Prestado</span>
                                    @elseif($recurso->estado == 3)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">En Mantenimiento</span>
                                    @elseif($recurso->estado == 4)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Dañado</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-300 rounded-full">Desconocido</span>
                                    @endif
                                </td>
                                
                                <td>{{ $recurso->fecha_registro->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="confirmEdit('{{ $recurso->nombre }}', {{ $recurso->id }})">
                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                    </button>
                                    <form action="{{ route('recursos.destroy', $recurso->id) }}" method="POST" style="display:inline-block;">
                                        
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(event, this.form, '{{ $recurso->nombre}}')">
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
<!-- MODAL -->
<div class="modal fade" id="recursoModal" tabindex="-1" aria-labelledby="recursoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- Header del modal con Tailwind -->
            <div class="modal-header bg-primary text-white text-center">
                <h5 class="modal-title" id="recursoModalLabel">{{ __('Agregar / Editar Recurso') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-6">
                <form id="recursoForm" method="POST" action="{{ route('recursos.store') }}" class="space-y-4">
                    @csrf
                    <!-- Campo oculto para el ID del recurso -->
                    <input type="hidden" id="recurso_id" name="recurso_id">
                    <!-- Campo oculto para el método del formulario (PUT o POST) -->
                    <input type="hidden" id="_method" name="_method" value="POST">

                    <!-- Número de serie -->
                    <div class="flex flex-col mb-4">
                        <label for="nro_serie" class="font-semibold">{{ __('Numero de Serie') }}</label>
                        <input type="text" class="form-input border border-gray-300 rounded-lg px-3 py-2" name="nro_serie" id="nro_serie" placeholder="Número de Serie" required>
                        @if($errors->has('nro_serie'))
                            <span class="text-red-500 text-sm">{{ $errors->first('nro_serie') }}</span>
                        @endif
                    </div>

                    <!-- Categoría y Marca -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="categoria" class="font-semibold">{{ __('Categoría') }}</label>
                            <select name="id_categoria" id="categoria" class="form-select border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">{{ __('Seleccione una categoría') }}</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="marca" class="font-semibold">{{ __('Marca') }}</label>
                            <select name="id_marca" id="marca" class="form-select border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">{{ __('Seleccione una marca') }}</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Campo de estado (solo aparece al editar) -->
                    <div id="estadoField" class="hidden">
                        <label for="estado" class="font-semibold">{{ __('Estado del Recurso') }}</label>
                        <select name="estado" id="estado" class="form-select border border-gray-300 rounded-lg px-3 py-2">
                            <option value="1">{{ __('Disponible') }}</option>
                            <option value="3">{{ __('En Mantenimiento') }}</option>
                            <option value="4">{{ __('Dañado') }}</option>
                        </select>
                    </div>

                    <div class="flex justify-end mt-4 space-x-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        

@stop
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


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
    <script src="https://kit.fontawesome.com/89c262ed76.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
        function editRecurso(id) {
            $.ajax({
                url: '/recursos/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    // Configurar el formulario para edición
                    $('#recursoForm').attr('action', '/recursos/' + id);
                    $('#recursoForm').find('input[name="_method"]').remove(); // Eliminar cualquier campo _method existente
                    $('#recursoForm').append('<input type="hidden" name="_method" value="PUT">');
    
                    // Llenar el formulario con los datos del recurso
                    $('#recurso_id').val(response.id);
                    $('#nro_serie').val(response.nro_serie);
                    $('#categoria').val(response.id_categoria);
                    $('#marca').val(response.id_marca);
                    $('#estado').val(response.estado);
    
                    // Mostrar el campo de estado solo al editar
                    $('#estadoField').removeClass('hidden');
    
                    // Mostrar el modal
                    $('#recursoModal').modal('show');
                },
                error: function(xhr) {
                    console.error("Error al obtener los datos del recurso: ", xhr.responseText);
                }
            });
        }
    
        function clearForm() {
            $('#recursoForm')[0].reset();
            $('#recursoForm').attr('action', '{{ route("recursos.store") }}');
            $('#recursoForm').find('input[name="_method"]').remove(); // Asegurarse de que no haya campo _method
            $('#estadoField').addClass('hidden'); // Ocultar el campo de estado al crear un nuevo recurso
        }


        // Inicializar DataTable
        $(document).ready(function() {
            new DataTable('#recursosTable', {
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
                },
            });
        });




        function confirmEdit(nombre, id) {
            Swal.fire({
                title: '¿Desea editarlo?',
                html: 'A partir de ahora <b>"' + nombre + '"</b> cambiará <i class="fa-regular fa-face-flushed"></i>.',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, ¡editarlo!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, llama a la función de edición
                    editRecurso(id);
                }
            });
        }



        function confirmDelete(e, form, nombre) {
        e.preventDefault(); // Evitar el envío inmediato del formulario

        Swal.fire({
            title: "¿Está seguro que desea eliminarlo?",
            html: '<b>"' + nombre + '"</b> ya no volverá <i class="fa-regular fa-face-sad-tear"></i> ',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<i class="fa-regular fa-face-frown"></i> Sí, ¡elimínalo!',
            cancelButtonText: '<i class="fa-regular fa-face-laugh-beam"></i> Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la solicitud AJAX
                $.ajax({
                    url: form.action,
                    type: 'POST', // Cambiar a POST para enviar el CSRF token
                    data: {
                        _method: 'DELETE', // Esto es importante para que Laravel lo reconozca
                        _token: $('meta[name="csrf-token"]').attr('content') // Obtener el token CSRF
                    },
                    success: function(response) {
                        // Si se eliminó correctamente
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "La categoría ha sido eliminada.",
                            icon: "success"
                        }).then(() => {
                            // Redireccionar o actualizar la página según lo necesites
                            location.reload(); // Por ejemplo, recargar la página
                        });
                    },
                    error: function(xhr) {
                        // Si hay un error, mostrar la alerta de error
                        let errorMessage = xhr.responseJSON.message || "Ocurrió un error inesperado.";
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: errorMessage, // Mensaje del servidor
                            footer: '<p>¡Salvados por la alerta! <i class="fa-regular fa-face-grin-beam-sweat"></i></p>'
                        });
                    }
                });
            }
        });
    }



        
    </script>
    
    <script>
        $(document).ready(function() {
            // Si hay un error en la validación de 'nro_serie'
            @if ($errors->has('nro_serie'))
                // Muestra el SweetAlert
                Swal.fire({
                    title: 'Error',
                    text: 'El número de serie ya existe. Por favor, ingrese uno nuevo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    // Abrir el modal de nuevo
                    $('#recursoModal').modal('show');
                });
            @endif

            // Mostrar SweetAlert si hay un mensaje de éxito
            @if (session('success'))
                Swal.fire({
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#66b366'
                });
            @endif

            // Mostrar SweetAlert si hay un mensaje de error general
            @if (session('error'))
                Swal.fire({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>


@stop