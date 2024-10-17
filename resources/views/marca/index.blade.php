@extends('adminlte::page')

@section('title', 'Marcas')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-tags"></i> Marcas</h1>
                
        <button href="#" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#marcaModal" onclick="clearForm()"><i class="fa-solid fa-plus fa-shake"></i>
            {{ __('Crear Nuevo') }}
        </button>
    </div>
@stop
@section('content_top_nav_right')
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
                <!-- Notificaciones de hoy -->
                @foreach ($notificacionesHoy as $notificacion)
                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id]) }}" class="block px-4 py-2 text-gray-700 break-words">
                        <div class="text-sm">
                            {{ $notificacion->a_paterno }} debe devolver el recurso 
                            @if(isset($notificacion->minutos_atraso))
                                (Atraso de {{ $notificacion->minutos_atraso }} minutos)
                            @endif
                            @if(isset($notificacion->horas_restantes))
                                (Faltan {{ $notificacion->horas_restantes }} horas y {{ $notificacion->minutos_restantes }} minutos)
                            @endif
                            @if(isset($notificacion->dias_restantes))
                                (Faltan {{ $notificacion->dias_restantes }} días)
                            @endif
                        </div>
                    </a>
                @endforeach

                <!-- Notificaciones atrasadas -->
                @foreach ($notificacionesAtrasadas as $notificacion)
                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id_recurso]) }}" class="block px-4 py-2 text-gray-700 break-words">
                        <div class="text-sm">
                            {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}).
                        </div>
                        <span class="text-xs text-red-500 float-right">
                            Se encuentra atrasado por {{ $notificacion->dias_atraso }} días.
                        </span>
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
                                    Debía devolver hace {{ $notificacion->minutos_atraso }} minutos.
                                @else
                                    Faltan {{ $notificacion->horas_restantes }} horas y {{ $notificacion->minutos_restantes }} minutos.
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
                                Atrasado por {{ $notificacion->dias_atraso }} días.
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


<p class="mb-4">Aquí se mostrarán las marcas registradas.</p>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="marcasTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($marcas as $marca)
                        <tr>
                            <td>{{ $marca->id }}</td>
                            <td>{{ $marca->nombre }}</td>
                            <td>{{ $marca->descripcion }}</td>
                            <td>
                                
                                <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST" style="display:inline;">
                                    
                                    <a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="confirmEdit('{{ $marca->nombre }}', {{ $marca->id }})">
                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                    </a>

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event, this.form, '{{ $marca->nombre }}')">
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



<!-- Modal de Creación/Edición de Marcas -->
<div class="modal fade" id="marcaModal" tabindex="-1" aria-labelledby="marcaModalLabel" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center" role="document">
        <div class="modal-content rounded-xl border-4 border-black">
            <div class="modal-header bg-blue-500 text-white flex justify-between items-center p-4 border-b-10 border-blue-800">
                <h5 class="modal-title text-center flex-1 font-bold text-lg" id="marcaModalLabel">{{ __('Agregar / Editar Marca') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="marcaForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre de la Marca" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">{{ __('Descripción') }}</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Descripción de la Marca" required></textarea>
                    </div>
                    
                    <input type="hidden" name="marca_id" id="marca_id">
                    
                    <div class="modal-footer">
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

    <script src="https://kit.fontawesome.com/89c262ed76.js" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        new DataTable('#marcasTable', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });

        // Script para manejar la apertura del modal para edición
        /* document.addEventListener('DOMContentLoaded', function() {
            var marcaModal = document.getElementById('marcaModal');
            marcaModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var nombre = button.getAttribute('data-nombre');
                var descripcion = button.getAttribute('data-descripcion');
                var action = button.getAttribute('data-action');

                var modalTitle = marcaModal.querySelector('.modal-title');
                var form = marcaModal.querySelector('form');
                var inputId = marcaModal.querySelector('input[name="marca_id"]');
                var inputNombre = marcaModal.querySelector('input[name="nombre"]');
                var inputDescripcion = marcaModal.querySelector('textarea[name="descripcion"]');

                if (id) {
                    // Editing mode
                    modalTitle.textContent = '{{ __('Editar Marca') }}';
                    form.action = action;
                    form.querySelector('input[name="_method"]').value = 'PUT'; // Update method
                    inputId.value = id;
                    inputNombre.value = nombre;
                    inputDescripcion.value = descripcion;
                } else {
                    // Creating mode
                    modalTitle.textContent = '{{ __('Agregar Marca') }}';
                    form.action = '{{ route('marcas.store') }}';
                    form.querySelector('input[name="_method"]').value = 'POST'; // Create method
                    inputId.value = '';
                    inputNombre.value = '';
                    inputDescripcion.value = '';
                }
            });
        }); */
    </script>


<!--                        agregar marca                            -->
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

<!--                        Editar marca                            -->

    <script>
        function clearForm() {
            $('#marcaForm')[0].reset();
            $('#marcaForm').attr('action', '{{ route("marcas.store") }}');
            $('#marcaForm').find('input[name="_method"]').remove();
        }

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
                editMarca(id);
            }
        });
    }

        function editMarca(id) {
            $.ajax({
                url: '/marcas/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#marca_id').val(response.id);
                    $('#nombre').val(response.nombre);
                    $('#descripcion').val(response.descripcion);

                    // Cambiar el action del formulario para editar
                    $('#marcaForm').attr('action', '/marcas/' + id);
                    $('#marcaForm').append('<input type="hidden" name="_method" value="PATCH">');
                    $('#marcaModal').modal('show');
                },
                error: function(xhr) {
                    console.error("Error al obtener los datos de la marca: ", xhr.responseText);
                }
            });
        }

    </script>




















<!--                        Eliminar marca                            -->


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
    


@stop
