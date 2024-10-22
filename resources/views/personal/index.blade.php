@extends('adminlte::page')

@section('title', 'Personal')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-person"></i> Personal</h1>
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
    <p class="mb-0">Aquí puedes agregar, ver, editar, eliminar la información sobre el Personal.</p>
    <button type="button" class="btn btn-primary text-white py-3 px-4" data-bs-toggle="modal" data-bs-target="#personalModal" onclick="clearForm()">
        {{ __('Crear Nuevo') }}
    </button>
</div>
    <!-- Tabla de Personal -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="personalsTable" class="table table-striped table-bordered mt-2 table-hover mb-2" style="width:100%">
                    <thead class="bg-[#9c1515] text-white">
                        <tr>
                            <th>No</th>
                            <th>Tipo Documento</th>
                            <th>Nro Documento</th>
                            <th>Nombres</th>
                            <th>A. Paterno</th>
                            <th>A. Materno</th>
                            <th>Teléfono</th>
                            <th>Cargo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personals as $personal)
                            <tr class="hover:bg-crema">
                                <td>{{ $personal->id }}</td>
                                <td>{{$personal->tipodoc === "Documento Nacional de Identidad" ? "DNI" : "DDE" }}</td>
                                <td>{{ $personal->nro_documento }}</td>
                                <td>{{ $personal->nombres }}</td>
                                <td>{{ $personal->a_paterno }}</td>
                                <td>{{ $personal->a_materno }}</td>
                                <td>{{ $personal->telefono }}</td>
                                <td>{{ $personal->cargo }}</td>
                                <td>
                                    <form action="{{ route('personals.destroy', $personal->id) }}" method="POST">

                                        <a class="btn btn-sm btn-outline-primary" href="javascript:void(0)" onclick="confirmEdit('{{ $personal->nombres }}', {{ $personal->id }}, '{{ $personal->a_paterno}}', '{{ $personal->a_materno}}' )">
                                            <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                        </a>

                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(event, this.form, '{{ $personal->nombres}}', '{{ $personal->a_paterno}}', '{{ $personal->a_materno}}')">
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


 
<!-- Modal de Creación y Edición -->
<div class="modal fade" id="personalModal" tabindex="-1" aria-labelledby="personalModalLabel" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center" role="document">
        <div class="modal-content border-2 border-maroon rounded-lg">
            <div class="modal-header bg-vino text-white">
                <h5 class="modal-title w-100 text-center" id="modalTitle">{{ __('Agregar / Editar Personal') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-crema">
                <form id="personalForm" method="POST" action="{{ route('personals.store') }}">
                    @csrf

                    <div class="flex space-x-4">
                        <!-- Columna izquierda -->
                        <div class="flex-1 space-y-4">
                            <!-- Tipo de Documento -->
                            <div class="mb-4">
                                <label for="tipodoc" class="block text-sm font-medium text-gray-700">{{ __('Tipo de Documento') }}</label>
                                <select name="tipodoc" id="tipodoc" class="form-control selectpicker @error('tipodoc') is-invalid @enderror">
                                    <option value="Documento Nacional de Identidad">DNI</option>
                                    <option value="Documento de Extranjeria">DDE</option>
                                </select>
                                {!! $errors->first('tipodoc', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                            </div>

                            <!-- Número de Documento -->
                            <div class="mb-4">
                                <label for="nro_documento" class="block text-sm font-medium text-gray-700">{{ __('Número de documento') }}</label>
                                <div class="relative">
                                    <input type="text" name="nro_documento" id="nro_documento" onkeypress='return validaNumericos(event)' class="block w-full mt-1 py-2 pl-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nro Documento">
                                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-1 text-gray-500 hover:text-gray-700" id="buscar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="mb-4">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
                                <input type="text" name="telefono" id="telefono" onkeypress='return validaNumericos(event)' minlength="9" maxlength="9" required class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 py-2 pl-2 focus:ring-indigo-500 sm:text-sm" placeholder="Teléfono">
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="flex-1 space-y-4">
                            <!-- Nombres -->
                            <div class="mb-4">
                                <label for="nombres" class="block text-sm font-medium text-gray-700">{{ __('Nombres') }}</label>
                                <input type="text" name="nombres" id="nombres" class="block w-full mt-1 py-2 pl-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nombres" oninput="this.value = this.value.replace(/\d+/g, '').toUpperCase()">
                            </div>

                            <!-- Apellido Paterno -->
                            <div class="mb-4">
                                <label for="a_paterno" class="block text-sm font-medium text-gray-700">{{ __('Apellido Paterno') }}</label>
                                <input type="text" name="a_paterno" id="a_paterno" class="block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Apellido Paterno" oninput="this.value = this.value.replace(/\d+/g, '').toUpperCase()">
                            </div>

                            <!-- Apellido Materno -->
                            <div class="mb-4">
                                <label for="a_materno" class="block text-sm font-medium text-gray-700">{{ __('Apellido Materno') }}</label>
                                <input type="text" name="a_materno" id="a_materno" class="block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Apellido Materno" oninput="this.value = this.value.replace(/\d+/g, '').toUpperCase()">
                            </div>
                        </div>
                    </div>

                   <!-- Selección de Cargo -->
                   <div class="mb-4">
                    <label for="cargo" class="block text-sm font-medium text-gray-700">{{ __('Cargo') }}</label>
                    <select name="cargo" id="cargo" class="selectpicker block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="mostrarOtroCargo(this)">
                        <option value="Profesor">{{ __('Profesor') }}</option>
                        <option value="Personal Administrativo">{{ __('Personal Administrativo') }}</option>
                        <option value="Otro">{{ __('Otro') }}</option>
                    </select>
                </div>

                <!-- Campo 'Otro Cargo' (se muestra solo si se selecciona "Otro") -->
                <div class="mb-4" id="otroCargoDiv" style="display: none;">
                    <label for="otro_cargo" class="block text-sm font-medium text-gray-700">{{ __('Especifique otro cargo') }}</label>
                    <input required type="text" name="otro_cargo" id="otro_cargo" class="block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Especifique otro cargo" >
                </div>

                    <!-- Input hidden para ID del personal -->
                    <input type="hidden" name="personal_id" id="personal_id">

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
    function mostrarOtroCargo(selectElement) {
        var otroCargoDiv = document.getElementById('otroCargoDiv');
        if (selectElement.value === 'Otro') {
            otroCargoDiv.style.display = 'block';
        } else {
            otroCargoDiv.style.display = 'none';
        }
    }
     // Función para cambiar el título del modal según si es creación o edición
     document.getElementById('personalModal').addEventListener('show.bs.modal', function (event) {
        var modalTitle = document.getElementById('modalTitle');
        var personal_id = document.getElementById('personal_id').value;

        if (personal_id) {
            modalTitle.textContent = 'Editar Personal';
        } else {
            modalTitle.textContent = 'Agregar Personal';
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
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@stop



<!-- Scripts -->
@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script src="https://kit.fontawesome.com/89c262ed76.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    new DataTable('#personalsTable', {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
        },
    });
    $('select').selectpicker();
</script>




<script>
    function validaNumericos(event) {
        return event.charCode >= 48 && event.charCode <= 57;
    }

    $(document).ready(function() {
        $("#tipodoc").change(function() {
            var selectedValue = $(this).val();

            if (selectedValue === "Documento Nacional de Identidad") { // ID para DNI
                $("#nro_documento").attr("maxlength", 8);
                $("#nro_documento").attr("minlength", 8);
                $("#nro_documento").attr("placeholder", "Ingresar DNI");
                $("#nro_documento").attr("readonly", false); // Limpiar la longitud máxima si no está seleccionado


            } else if (selectedValue === "Documento de Extranjeria") { // ID para extranjero
                $("#nro_documento").attr("maxlength", 9);
                $("#nro_documento").attr("minlength", 9);
                $("#nro_documento").attr("placeholder", "Ingresar Número de Extranjero");
                $("#nro_documento").attr("readonly", false); // Limpiar la longitud máxima si no está seleccionado
            } else {
                $("#nro_documento").attr("readonly", true); // Limpiar la longitud máxima si no está seleccionado
                $("#nro_documento").attr("placeholder", "Seleccionar Tipo de Documento");
            }
        });

        $("#buscar").click(function() {
            var tipoDoc = $("#tipodoc").val();
            var documento = $("#nro_documento").val();

            if (tipoDoc === "Documento Nacional de Identidad") { // DNI
                if (documento.length === 8) {
                    buscarDni(documento);
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "¡Cuidado!",
                        text: "El dni debe tener 8 dígitos",
                    });
                }
            } else if (tipoDoc === "Documento de Extranjeria") { // Extranjero
                if (documento.length === 9) {
                    buscarExtranjero(documento);
                } else {
                    
                    Swal.fire({
                        icon: "warning",
                        title: "¡Cuidado!",
                        text: "El documento de extranjería debe tener 9 dígitos",
                    });
                }
            } else {
            }
        });

        function buscarDni(dni) {
            $.ajax({
                url: `/buscar-dni/${dni}`, // Ruta que apunta a tu controlador
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $("#nombres").val(response.nombres).prop('readonly', true).addClass('bg-gray-300 text-gray-500 cursor-not-allowed');
                        $("#a_paterno").val(response.apellidoPaterno).prop('readonly', true).addClass('bg-gray-300 text-gray-500 cursor-not-allowed');
                        $("#a_materno").val(response.apellidoMaterno).prop('readonly', true).addClass('bg-gray-300 text-gray-500 cursor-not-allowed');

                    }else {
                        Swal.fire({
                            icon: "error",
                            title: "DNI no encontrado",
                            text: "No se encontraron datos para el dni ingresado!",
                        });
                    }
                },
                error: function() {
                    
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Hubo un error al realizar la solicitud",
                    });
                }
            });
        }

        function buscarExtranjero(cee) {
            $.ajax({
                url: `https://api.factiliza.com/pe/v1/cee/info/${cee}`,
                method: 'GET',
                headers: {
                    "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNzg1MSIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.aUC04TeTppjiWLTK0a62C5SbMcGgP_ZpGZzRt0rea74"
                },
                success: function(response) {
                    if (response.status === 200) {
                        $("#nombres").val(response.data.nombres).prop('readonly', true);
                        $("#a_paterno").val(response.data.apellido_paterno).prop('readonly', true);
                        $("#a_materno").val(response.data.apellido_materno).prop('readonly', true);
                    } else {
                        
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "No se encontraron datos para el número de extranjero ingresado",
                        });
                            $("#nombres").val('').prop('readonly', false);
                            $("#a_paterno").val('').prop('readonly', false);
                            $("#a_materno").val('').prop('readonly', false);
                        }
                    },
                error: function() {
                    
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se encontraron datos para el número de extranjero ingresado",
                    });
                }
            });
        }

        // Asegúrate de que la longitud máxima y el placeholder se establezcan correctamente al cargar la página
        $("#tipodoc").trigger("change");
    });
</script>

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
        icon: 'warning',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#66b366'
    })
</script>
@endif


<script>
/*     $("#buscar").click(function() {
        let dni = $("#nro_documento").val();

        if (dni.length === 8) {
            $.ajax({
                url: `/buscar-dni/${dni}`, // Ruta que apunta a tu controlador
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $("#nombres").val(response.nombres);
                        $("#a_paterno").val(response.apellidoPaterno);
                        $("#a_materno").val(response.apellidoMaterno);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "DNI no encontrado",
                            text: "No se encontraron datos para el dni ingresado!",
                        });
                    }
                },
                error: function() {
                    alert("No se encontraron datos para el dni ingresado.");
                }
            });
        }
    }); */
 

        function editPersonal(id) {
                $.ajax({
                    url: '/personals/' + id + '/edit',
                    type: 'GET',
                    success: function(response) {
                        $('#personalForm').attr('action', '/personals/' + id);
                        $('#personalForm').append('<input type="hidden" name="_method" value="PATCH">');

                        $('#personal_id').val(response.id);
                        $('#tipodoc').val(response.tipodoc);
                        $('#nro_documento').val(response.nro_documento);
                        $('#telefono').val(response.telefono);
                        $('#nombres').val(response.nombres);
                        $('#a_paterno').val(response.a_paterno);
                        $('#a_materno').val(response.a_materno);
                        $('#cargo').val(response.cargo);

                        $('#personalModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error("Error al obtener los datos del personal: ", xhr.responseText);
                    }
                });
            }

            function clearForm() {
                $('#personalForm')[0].reset();
                $('#personalForm').attr('action', '{{ route("personals.store") }}');
                $('#personalForm').find('input[name="_method"]').remove();
                $('#nombres, #a_paterno, #a_materno').prop('readonly', false);
            }



    function confirmEdit(nombre, id, a_paterno, a_materno) {
        Swal.fire({
            title: '¿Desea editarlo?',
            html: 'A partir de ahora <b>"' + nombre + " " + a_paterno + " " + a_materno + '"</b> cambiará <i class="fa-regular fa-face-flushed"></i>.',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, ¡editarlo!",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, llama a la función de edición
                editPersonal(id);
            }
        });
    }







    function confirmDelete(e, form, nombre, a_paterno, a_materno) {
        e.preventDefault(); 

        Swal.fire({
            title: "¿Está seguro que desea eliminarlo?",
            html: '<b>"' + nombre + ' ' + a_paterno + ' ' + a_materno + '"</b> ya no volverá <i class="fa-regular fa-face-sad-tear"></i> ',
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
                            text: "El personal ha sido eliminada.",
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
                            title: "Error",
                            text: errorMessage, // Mensaje del servidor
                            footer: '<p>¡Salvados por la alerta! <i class="fa-regular fa-face-grin-beam-sweat"></i></p>'
                        });
                    }
                });
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