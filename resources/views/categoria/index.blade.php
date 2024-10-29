@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-tags-fill"></i> <span class="font-semibold">Categorías</span></h1>
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


@include('partials.sidebar')


<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="mb-0">Aquí puedes agregar, ver, editar, eliminar la información sobre las Categorias.</p>
    <button type="button" class="btn btn-primary text-white py-3 px-4" data-bs-toggle="modal" data-bs-target="#categoriaModal" onclick="clearForm()">
        {{ __('Crear Nuevo') }}
    </button>
</div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-4">
                <table id="example" class="table table-striped table-bordered mt-2 table-hover mb-2" style="width:100%">
                    <thead class="bg-[#9c1515] text-white">
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
                                        <a class="btn btn-sm btn-outline-primary" href="javascript:void(0)" onclick="confirmEdit('{{ $categoria->nombre }}', {{ $categoria->id }})"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(event, this.form, '{{ $categoria->nombre}}')">
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
        <div class="modal-content rounded-lg border-2 border-maroon"> <!-- Bordes redondeados y borde color vino -->
            
            <!-- Cabecera del modal -->
            <div class="modal-header bg-vino text-white flex justify-between items-center p-4 border-b-10 border-blue-800"> <!-- Fondo vino y texto blanco -->
                <h5 class="modal-title w-100 text-center" id="modalTitleCategoria">{{ __('Agregar / Editar Categoría') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Cuerpo del modal -->
            <div class="modal-body bg-crema"> <!-- Fondo crema -->
                <form id="categoriaForm" class="formulario-agregar" method="POST" action="{{ route('categorias.store') }}">
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
<script>
    // Cambiar el título del modal según si se va a crear o editar
    document.getElementById('categoriaModal').addEventListener('show.bs.modal', function (event) {
        var modalTitle = document.getElementById('modalTitleCategoria');
        var categoria_id = document.getElementById('categoria_id').value; // Verificar si hay un ID de categoría

        if (categoria_id) {
            modalTitle.textContent = 'Editar Categoría'; // Cambiar el título a "Editar Categoría"
        } else {
            modalTitle.textContent = 'Agregar Categoría'; // Cambiar el título a "Agregar Categoría"
        }
    });
</script>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
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




<!--    SWEETALERT2              -->

    @if (session('success'))
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#66b366'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#66b366'
            });
        </script>
    @endif















<script>
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
                editCategoria(id);
            }
        });
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
