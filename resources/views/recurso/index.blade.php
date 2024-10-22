@extends('adminlte::page')

@section('title', 'Recursos')

@section('content_header')
<div class="d-flex justify-content-between mb-2">
    <h1><i class="bi bi-pc-display-horizontal"></i> Recursos</h1>
</div>
@stop
@section('content_top_nav_right')
@if (Auth::check() && Auth::user()->role === 'admin')
    <div class="text-right mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
            <i class="bi bi-person-add"></i> Ver Usuarios
        </a>
    </div>
@endif
<!-- Dropdown de notificaciones -->
<li class="nav-item dropdown">
    <div class="relative">
        <a class="nav-link cursor-pointer" id="notificationDropdown">
            <i class="far fa-bell"></i>
            <span class="absolute top-0 right-0 block h-5 w-5 rounded-full bg-yellow-400 text-white text-center text-xs">
                {{ $totalNotificaciones }} 
            </span>
        </a>
        <div class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-50" id="notificationMenu">
            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                {{ $totalNotificaciones }} Notificaciones
            </div>
            <div class="overflow-y-auto max-h-64">
               <!-- Notificaciones para hoy -->
                @foreach ($notificacionesHoy as $notificacion)
                @php
                    // Definimos las clases de color para el tiempo restante
                    $timeClass = 'text-green-500'; // Verde (sin atraso)
                    
                    if (isset($notificacion->minutos_atraso) || isset($notificacion->horas_atraso)) {
                        $timeClass = 'text-red-500'; // Rojo (atrasado)
                    } elseif (isset($notificacion->minutos_restantes) && $notificacion->minutos_restantes <= 30) {
                        $timeClass = 'text-yellow-500'; // Amarillo (menos de 30 minutos)
                    }
                @endphp

                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id]) }}" class="block px-4 py-2 text-gray-700 break-words">
                    <div class="text-sm">
                        {{ $notificacion->a_paterno }} debe devolver el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }})
                        @if(isset($notificacion->horas_atraso) && $notificacion->horas_atraso > 0)
                            <span>(Atraso de <span class="{{ $timeClass }}">{{ $notificacion->horas_atraso }} horas y {{ $notificacion->minutos_atraso }} minutos</span>)</span>
                        @elseif(isset($notificacion->minutos_atraso) && $notificacion->minutos_atraso > 0)
                            <span>(Atraso de <span class="{{ $timeClass }}">{{ $notificacion->minutos_atraso }} minutos</span>)</span>
                        @elseif(isset($notificacion->dias_restantes) && $notificacion->dias_restantes > 0)
                            <span>(Faltan <span class="{{ $timeClass }}">{{ $notificacion->dias_restantes }} días y {{ $notificacion->horas_restantes }} horas</span>)</span>
                        @elseif(isset($notificacion->horas_restantes) && $notificacion->horas_restantes > 0)
                            <span>(Faltan <span class="{{ $timeClass }}">{{ $notificacion->horas_restantes }} horas y {{ $notificacion->minutos_restantes }} minutos</span>)</span>
                        @elseif(isset($notificacion->minutos_restantes) && $notificacion->minutos_restantes > 0)
                            <span>(Faltan <span class="{{ $timeClass }}">{{ $notificacion->minutos_restantes }} minutos</span>)</span>
                        @else
                            <span>(Tiempo restante hoy)</span>
                        @endif
                    </div>
                </a>
                @endforeach

                <!-- Notificaciones atrasadas -->
                @foreach ($notificacionesAtrasadas as $notificacion)
                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id]) }}" class="block px-4 py-2 text-gray-700 break-words">
                    <div class="text-sm">
                        {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}) atrasado por <span class="text-red-500">{{ $notificacion->dias_atraso }} días</span>.
                    </div>
                </a>
                @endforeach
            </div>
            <div class="px-4 py-2 border-t">
                <button class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="openModal()">Ver todas las notificaciones</button>
            </div>
        </div>
    </div>
</li>

<!-- Modal para ver todas las notificaciones -->
<div class="fixed inset-0 hidden bg-gray-800 bg-opacity-75 flex items-center justify-center z-50" id="allNotificationsModal">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
        <div class="flex justify-between items-center p-4 border-b">
            <h5 class="text-lg font-semibold">Todas las Notificaciones</h5>
            <button class="text-gray-600 hover:text-gray-900" onclick="closeModal()">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto max-h-96">
            <ul class="space-y-4">
               <!-- Título para Notificaciones de Hoy -->
                @if(count($notificacionesHoy))
                <h6 class="text-md font-semibold text-gray-700 mb-2">Notificaciones de Hoy</h6>
                @foreach ($notificacionesHoy as $notificacion)
                    <a href="#" class="block px-4 py-2 text-gray-700 break-words">
                        <div class="text-sm">
                            {{ $notificacion->a_paterno }} debe devolver el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}) hoy.
                        </div>
                        <span class="text-xs text-gray-500 float-right">
                            @if (isset($notificacion->minutos_atraso))
                                Debía devolver hace 
                                <span class="text-red-500">{{ $notificacion->horas_atraso }} horas</span> y 
                                <span class="text-red-500">{{ $notificacion->minutos_atraso }} minutos</span>.
                            @elseif (isset($notificacion->minutos_restantes) && $notificacion->minutos_restantes <= 30 && $notificacion->horas_restantes == 0)
                                Faltan 
                                <span class="text-yellow-500">{{ $notificacion->minutos_restantes }} minutos</span>.
                            @elseif ($notificacion->horas_restantes == 0 && isset($notificacion->minutos_restantes))
                                Faltan 
                                <span class="text-green-500">{{ $notificacion->minutos_restantes }} minutos</span>.
                            @else
                                Faltan 
                                <span class="text-green-500">{{ $notificacion->horas_restantes }} horas</span> y 
                                <span class="text-green-500">{{ $notificacion->minutos_restantes }} minutos</span>.
                            @endif
                        </span>
                    </a>
                @endforeach
                @else
                <li class="bg-gray-100 p-3 rounded-lg">No hay notificaciones para hoy.</li>
                @endif
                
                <!-- Título para Notificaciones Atrasadas -->
                @if(count($notificacionesAtrasadas))
                <h6 class="text-md font-semibold text-gray-700 mb-2 mt-4">Notificaciones Atrasadas</h6>
                @foreach ($notificacionesAtrasadas as $notificacion)
                    <a href="#" class="block px-4 py-2 text-gray-700 break-words">
                        <div class="text-sm">
                            {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}).
                        </div>
                        <span class="text-xs text-red-500 float-right">
                            Atrasado por <span class="text-red-500">{{ $notificacion->dias_atraso }} días</span>.
                        </span>
                    </a>
                @endforeach
                @else
                <li class="bg-gray-100 p-3 rounded-lg">No hay notificaciones atrasadas.</li>
                @endif
            </ul>
        </div>
        <div class="p-4 border-t text-right">
            <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal()">Cerrar</button>
        </div>
    </div>
</div>




<script>
function mostrarResumen(mensaje) {
    Swal.fire({
        title: 'Resumen de Notificación',
        text: mensaje,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}
</script>


<!-- Script para el comportamiento del modal -->
<script>
// Abre o cierra el menú de notificaciones
document.getElementById('notificationDropdown').addEventListener('click', function() {
    const menu = document.getElementById('notificationMenu');
    menu.classList.toggle('hidden');
});

// Abre el modal de notificaciones
function openModal() {
    document.getElementById('allNotificationsModal').classList.remove('hidden');
    document.getElementById('notificationMenu').classList.add('hidden'); // Cierra el menú al abrir el modal
}

// Cierra el modal de notificaciones
function closeModal() {
    document.getElementById('allNotificationsModal').classList.add('hidden');
}

// Cerrar el menú de notificaciones si se hace clic fuera de él
window.addEventListener('click', function(e) {
    const menu = document.getElementById('notificationMenu');
    if (!document.getElementById('notificationDropdown').contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>
@stop
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="mb-0">Aquí puedes agregar, ver, editar, eliminar la información sobre los Recursos.</p>
    <button type="button" class="btn btn-primary text-white py-3 px-4" data-bs-toggle="modal" data-bs-target="#recursoModal" onclick="clearForm()">
        {{ __('Crear Nuevo') }}
    </button>
</div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="recursosTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                    <thead class="bg-[#9c1515] text-white">
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
                                    @if ($recurso->estado == 1)
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Disponible</span>
                                    @elseif($recurso->estado == 2)
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">Prestado</span>
                                    @elseif($recurso->estado == 3)
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">En
                                            Mantenimiento</span>
                                    @elseif($recurso->estado == 4)
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Dañado</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-300 rounded-full">Desconocido</span>
                                    @endif
                                </td>

                                <td>{{ $recurso->fecha_registro->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="confirmEdit('{{ $recurso->nombre }}', {{ $recurso->id }}, {{ $recurso->estado }})">
                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                    </button>
                                    <form action="{{ route('recursos.destroy', $recurso->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="confirmDelete(event, this.form, '{{ $recurso->nombre }}')">
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

    <!-- MODAL DE RECURSOS -->
<div class="modal fade" id="recursoModal" tabindex="-1" aria-labelledby="recursoModalLabel" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center" role="document">
        <div class="modal-content border-2 border-maroon rounded-lg">
            <div class="modal-header bg-vino text-white">
                <h5 class="modal-title w-100 text-center" id="modalTitleRecurso">{{ __('Agregar / Editar Recurso') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-crema">
                <form id="recursoForm" method="POST" action="{{ route('recursos.store') }}">
                    @csrf
                    <!-- Campo oculto para el ID del recurso -->
                    <input type="hidden" id="recurso_id" name="recurso_id">
                    <!-- Campo oculto para el método del formulario (PUT o POST) -->
                    <input type="hidden" id="_method" name="_method" value="POST">

                    <!-- Número de serie -->
                    <div class="mb-4">
                        <label for="nro_serie" class="block text-sm font-medium text-gray-700">{{ __('Número de Serie') }}</label>
                        <input type="text" class="block w-full mt-1 py-2 pl-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm" name="nro_serie" id="nro_serie" placeholder="Número de Serie" required>
                        @if ($errors->has('nro_serie'))
                            <span class="text-red-500 text-sm">{{ $errors->first('nro_serie') }}</span>
                        @endif
                    </div>

                    <!-- Categoría y Marca -->
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="categoria" class="block text-sm font-medium text-gray-700">{{ __('Categoría') }}</label>
                            <select name="id_categoria" id="categoria" class="selectpicker block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm" data-live-search="true" required>
                                <option value="">{{ __('Seleccione una categoría') }}</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex-1">
                            <label for="marca" class="block text-sm font-medium text-gray-700">{{ __('Marca') }}</label>
                            <select name="id_marca" id="marca" class="selectpicker block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm" data-live-search="true" required>
                                <option value="">{{ __('Seleccione una marca') }}</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Campo de estado (solo aparece al editar) -->
                    <div id="estadoField" class="hidden mb-4">
                        <label for="estado" class="block text-sm font-medium text-gray-700">{{ __('Estado del Recurso') }}</label>
                        <select name="estado" id="estado" class="selectpicker block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm">
                            <option value="1">{{ __('Disponible') }}</option>
                            <option value="3">{{ __('En Mantenimiento') }}</option>
                            <option value="4">{{ __('Dañado') }}</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end mt-4 space-x-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Guardar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para cambiar el título del modal y mostrar el campo de estado -->
<script>
    document.getElementById('recursoModal').addEventListener('show.bs.modal', function (event) {
    var modalTitle = document.getElementById('modalTitleRecurso');
    var recurso_id = document.getElementById('recurso_id').value; 

    if (recurso_id && recurso_id !== '') {
        modalTitle.textContent = 'Editar Recurso';
    } else {
        modalTitle.textContent = 'Agregar Recurso';
    }
});

</script>



@stop
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            // Inicializar DataTable
            new DataTable('#recursosTable', {
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
                },
                select: true, // Permite seleccionar filas
                hover: true, // Añade efecto hover a las filas
                pagingType: 'simple', // Paginación más limpia
            });

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
                    timer: 2500,
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

        // Función para editar un recurso
        function editRecurso(id) {
            $.ajax({
                url: '/recursos/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    // Configurar el formulario para edición
                    $('#recursoForm').attr('action', '/recursos/' + id);
                    $('#recursoForm').find('input[name="_method"]').remove();
                    $('#recursoForm').append('<input type="hidden" name="_method" value="PUT">');

                    // Llenar el formulario con los datos del recurso
                    $('#recurso_id').val(response.id);
                    $('#nro_serie').val(response.nro_serie);

                    // Verifica si los datos vienen correctamente
                    console.log("Datos recibidos:", response);
                    console.log("Estado recibido:", response.estado);

                    // Establecer el valor seleccionado en los select de categoría y marca
                    $('#categoria').val(response.id_categoria).change();
                    $('#marca').val(response.id_marca).change();

                    // Refrescar visualmente los select de Bootstrap Select
                    $('#categoria').selectpicker('refresh');
                    $('#marca').selectpicker('refresh');

                    // Manejar el estado, incluso si la opción no está en el select
                    if (response.estado == 2) {
                        // Si el estado es "Prestado", agregar la opción temporalmente
                        $('#estado').append(new Option('Prestado', 2));
                    }

                    // Establecer el estado recibido
                    $('#estado').val(response.estado).change();

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


        
        // Función para confirmar la edición
function confirmEdit(nombreRecurso, id, estado) {
    // Verifica si el recurso está prestado
    if (estado === 2) {
        // Mostrar SweetAlert si el estado es "Prestado"
        Swal.fire({
            icon: 'warning',
            title: 'Recurso Prestado',
            text: 'El recurso está prestado y no se puede editar.',
            confirmButtonText: 'Aceptar'
        });
        return; // Detiene la ejecución si el recurso está prestado
    }

    // Si el recurso no está prestado, continuar con la confirmación de edición
    Swal.fire({
        title: '¿Desea editarlo?',
        html: 'A partir de ahora este registro cambiará.',
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

        // Función para confirmar la eliminación
        function confirmDelete(e, form, nombre) {
            e.preventDefault(); // Evitar el envío inmediato del formulario

            Swal.fire({
                title: "¿Está seguro que desea eliminarlo?",
                html: 'Este registro ya no volverá.',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, ¡elimínalo!",
                cancelButtonText: "Cancelar"
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
                            Swal.fire({
                                title: "¡Eliminado!",
                                text: "El recurso ha sido eliminado.",
                                icon: "success"
                            }).then(() => {
                                location.reload(); // Recargar la página
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = xhr.responseJSON.message ||
                                "Ocurrió un error inesperado.";
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "No se puede eliminar este recurso porque esta relacionado con otros registros",
                                footer: '<p>¡Salvados por la alerta!</p>'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        //Funcion para las trancisiones del filtro
        function toggleFilters() {
            const filtersContainer = document.getElementById('filtersContainer');

            // Añadir o eliminar la clase hidden
            if (filtersContainer.classList.contains('hidden')) {
                filtersContainer.classList.remove('hidden');

                // Iniciar la animación de entrada
                setTimeout(() => {
                    filtersContainer.classList.remove('opacity-0', 'scale-y-0');
                    filtersContainer.classList.add('opacity-100', 'scale-y-100');
                }, 10); // Agregar un pequeño retraso para que Tailwind detecte el cambio
            } else {
                // Animación de salida
                filtersContainer.classList.remove('opacity-100', 'scale-y-100');
                filtersContainer.classList.add('opacity-0', 'scale-y-0');

                // Después de la animación, ocultar el contenedor
                setTimeout(() => {
                    filtersContainer.classList.add('hidden');
                }, 500); // Tiempo igual a la duración de la transición
            }
        }
        // Función para limpiar el formulario
        function clearForm() {
            $('#recursoForm')[0].reset();
            $('#recursoForm').attr('action', '{{ route('recursos.store') }}');
            $('#recursoForm').find('input[name="_method"]').remove();
            $('#estadoField').addClass('hidden');

            // Limpiar y refrescar los selects de Bootstrap Select
            $('#id_categoria').val('').change();
            $('#id_marca').val('').change();
            $('.selectpicker').selectpicker('refresh');
        }
    </script>
    <style>
        .bg-crema {
            background-color: #e3dbc8;
        }
        .bg-vino {
            background-color: #9c1515;
        }
        .text-vino {
            color: #9c1515;
        }
        .btn-vino {
            background-color: #9c1515;
            border-color: #9c1515;
        }
        .btn-vino:hover {
            background-color: #7a1212;
            border-color: #7a1212;
        }
        .btn-crema {
            background-color: #e3dbc8;
            color: #9c1515;
        }
        .hover\:bg-crema:hover {
        background-color: #f5f5dc; /* Color crema al pasar el mouse */
    }
    </style>
@stop
