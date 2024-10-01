@extends('adminlte::page')

@section('title', 'Gestión de Préstamos')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
          <!-- Botón para abrir el modal -->
        <a href="#" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#prestamoModal" data-placement='right'>
            {{ __('Crear Nuevo Préstamo') }}
</a>
    </div>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success mb-4">
            <p>{{ $message }}</p>
        </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    
    <div class="card">
        <div class="card-body">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Filtros</h2>
                <form method="GET" action="{{ route('prestamos.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" class="flex justify-between items-center w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm font-medium shadow-sm focus:outline-none">
                                Nombre del Profesor
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute bg-white border border-gray-300 mt-2 rounded-lg shadow-lg z-10 p-3 w-full">
                                <input type="text" name="personal_name" id="personal_name" class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('personal_name') }}">
                            </div>
                        </div>
            
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" class="flex justify-between items-center w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm font-medium shadow-sm focus:outline-none">
                                Fecha Desde
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute bg-white border border-gray-300 mt-2 rounded-lg shadow-lg z-10 p-3 w-full">
                                <input type="date" name="start_date" id="start_date" class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('start_date') }}">
                            </div>
                        </div>
            
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" class="flex justify-between items-center w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm font-medium shadow-sm focus:outline-none">
                                Fecha Hasta
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute bg-white border border-gray-300 mt-2 rounded-lg shadow-lg z-10 p-3 w-full">
                                <input type="date" name="end_date" id="end_date" class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('end_date') }}">
                            </div>
                        </div>
            
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" class="flex justify-between items-center w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm font-medium shadow-sm focus:outline-none">
                                Estado
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute bg-white border border-gray-300 mt-2 rounded-lg shadow-lg z-10 p-3 w-full">
                                <select name="estado" id="estado" class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Seleccionar Estado</option>
                                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="desactivo" {{ request('estado') == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                                </select>
                            </div>
                        </div>
            
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" class="flex justify-between items-center w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm font-medium shadow-sm focus:outline-none">
                                Número de Serie
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute bg-white border border-gray-300 mt-2 rounded-lg shadow-lg z-10 p-3 w-full">
                                <input type="text" name="serial_number" id="serial_number" class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ request('serial_number') }}">
                            </div>
                        </div>
                    </div>
            
                    <div class="mt-6 flex justify-center space-x-4">
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors">Buscar</button>
                        <a href="{{ route('prestamos.index') }}" class="px-5 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-colors">Limpiar Filtros</a>
                    </div>
                </form>
            </div>
            
            <!-- Necesitarás Alpine.js para el comportamiento de los desplegables -->
            <script src="//unpkg.com/alpinejs" defer></script>
            
            
            <!-- Separador -->
            <hr class="my-6 border-t-2 border-gray-200">
    
            <!-- Tabla de Préstamos -->
            <div class="table-responsive">
                <table id="prestamosTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Personal</th>
                            <th>Fecha de Préstamo</th>
                            <th>Fecha de Devolución</th>
                            <th>Fecha de Devolución Marcada</th>
                            <th>Observación</th>
                            <th>Recurso</th>
                            <th>Estado</th> <!-- Nueva columna para mostrar el estado -->
                            <th>Opciones</th> <!-- Nueva columna para las opciones -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestamos as $prestamo)
                            @foreach ($prestamo->detalleprestamos as $detalle)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $prestamo->personal->nombres ?? 'N/A' }} {{ $prestamo->personal->a_paterno ?? '' }}</td>
                                    <td>{{ $prestamo->fecha_prestamo }}</td>
                                    <td>{{ $detalle->fecha_devolucion }}</td>
                                    <td>{{ $prestamo->fecha_devolucion_real }}</td>
                                    <td>{{ $prestamo->observacion }}</td>
                                    <td>{{ $detalle->recurso->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <!-- Mostrar el estado (activo o desactivo) -->
                                        @if ($prestamo->estado == 'activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Desactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Botón de opciones: Marcar como devuelto si el estado es activo -->
                                        @if ($prestamo->estado == 'activo')
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#devolucionModal-{{ $detalle->id }}">
                                                Marcar como devuelto
                                            </button>
                                        @else
                                            <span class="badge badge-secondary">Devuelto</span>
                                        @endif
                                    </td>
                                </tr>
                                
<!-- Modal para marcar como devuelto -->
<div class="modal fade" id="devolucionModal-{{ $detalle->id }}" tabindex="-1" aria-labelledby="devolucionModalLabel-{{ $detalle->id }}" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center min-h-screen">
        <div class="modal-content bg-white rounded-lg shadow-lg border border-gray-300">
            <div class="modal-header bg-blue-600 text-white flex justify-between items-center p-4 rounded-t-lg">
                <h5 class="modal-title font-semibold" id="devolucionModalLabel-{{ $detalle->id }}">{{ __('Marcar como devuelto') }}</h5>
                <button type="button" class="text-white hover:text-gray-200" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="devolucionForm-{{ $detalle->id }}" action="{{ route('prestamos.markAsReturned', $detalle->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre del recurso -->
                    <div class="form-group mb-4">
                        <label for="nombreRecurso-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Nombre del recurso') }}</label>
                        <input type="text" id="nombreRecurso-{{ $detalle->id }}" class="form-control w-full mt-1 bg-gray-100 border border-gray-300 rounded-lg" value="{{ $detalle->recurso->nombre }}" readonly>
                    </div>

                    <!-- Número de serie -->
                    <div class="form-group mb-4">
                        <label for="nroSerie-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Número de serie') }}</label>
                        <input type="text" id="nroSerie-{{ $detalle->id }}" class="form-control w-full mt-1 bg-gray-100 border border-gray-300 rounded-lg" value="{{ $detalle->recurso->nro_serie }}" readonly>
                    </div>

                    <!-- Estado del recurso -->
                    <div class="form-group mb-4">
                        <label for="estado-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Estado del recurso') }}</label>
                        <select name="estado" id="estado-{{ $detalle->id }}" class="form-control w-full mt-1 bg-white border border-gray-300 rounded-lg" onchange="actualizarEstado({{ $detalle->id }})">
                            <option value="1">{{ __('Disponible') }}</option>
                            <option value="4">{{ __('Dañado') }}</option>
                        </select>
                    </div>

                    <!-- Observación (opcional) -->
                    <div class="form-group mb-4">
                        <label for="observacion-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Observación (opcional)') }}</label>
                        <textarea name="observacion" id="observacion-{{ $detalle->id }}" class="form-control w-full mt-1 bg-white border border-gray-300 rounded-lg" rows="3" placeholder="{{ __('Ingrese observación sobre el estado del recurso al devolverlo') }}"></textarea>
                    </div>

                    <!-- Botón de confirmar devolución -->
                    <div class="flex justify-end">
                        <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700" onclick="confirmarDevolucion('{{ $detalle->id }}', '{{ $detalle->recurso->nombre }}', '{{ $detalle->recurso->nro_serie }}')">
                            {{ __('Confirmar devolución') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Modal de Creación/Edición de Préstamos -->
<div class="modal fade" id="prestamoModal" tabindex="-1" aria-labelledby="prestamoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg flex items-center justify-center min-h-screen">
        <div class="modal-content bg-white rounded-lg shadow-lg border border-gray-300">
            <div class="modal-header bg-blue-600 text-white flex justify-between items-center p-4 rounded-t-lg">
                <h5 class="modal-title font-semibold text-lg" id="prestamoModalLabel">{{ __('Crear Préstamo') }}</h5>
                <button type="button" class="text-white hover:text-gray-200" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body p-6">
                <!-- Inicio del formulario -->
                <form action="{{ route('prestamos.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Personal -->
                        <div class="form-group">
                            <label for="id_personal" class="block text-gray-700 font-medium">{{ __('Personal') }}</label>
                            <select name="idPersonal" class="form-control select2 w-full mt-1" id="id_personal" required>
                                <option value="">{{ __('Seleccione la persona') }}</option>
                                @foreach ($personals as $personal)
                                    <option value="{{ $personal->id }}">{{ $personal->nombres }} {{ $personal->a_paterno }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha de Préstamo -->
                        <div class="form-group">
                            <label for="fecha_prestamo" class="block text-gray-700 font-medium">{{ __('Fecha Prestamo') }}</label>
                            <input type="text" name="fecha_prestamo" class="form-control w-full mt-1 bg-gray-100 border border-gray-300 rounded-lg" value="{{ now()->format('d/m/Y') }}" id="fecha_prestamo" readonly>
                        </div>

                        <!-- Recursos -->
                        <div class="form-group">
                            <label for="recursos" class="block text-gray-700 font-medium">{{ __('Recursos') }}</label>
                            <div id="recursos-container" class="space-y-4">
                                <!-- Aquí se agregarán dinámicamente los recursos -->
                            </div>
                            <button type="button" id="add-resource" class="mt-4 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-plus"></i> {{ __('Añadir Recurso') }}
                            </button>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Guardar') }}</button>
                        </div>
                    </div>
                </form>
                <!-- Fin del formulario -->
            </div>
        </div>
    </div>
</div>




    

    {!! $prestamos->withQueryString()->links() !!}
@stop



@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        new DataTable('#prestamosTable', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });
    </script>
    <script>
       $(document).ready(function() {
    // Inicialización para el selector de Personal
    $('#id_personal').select2({
        placeholder: "Seleccione la persona",
        allowClear: true,
        width: '100%', // Asegura que el Select2 ocupe todo el ancho del contenedor
        tags: false // Permite buscar pero no agregar nuevas opciones
    });

    // Inicialización para el selector de Recursos
    $('select[name="idRecurso[]"]').select2({
        placeholder: "Seleccione un recurso",
        allowClear: true,
        width: '100%',
        tags: false // También aquí se busca pero no se crean nuevos recursos
    });
});

// Inicializar select2 en recursos agregados dinámicamente
$('#recursos-container').on('click', '#add-resource', function() {
    $('select[name="idRecurso[]"]').select2({
        placeholder: "Seleccione un recurso",
        allowClear: true,
        width: '100%',
        tags: false
    });
});

        </script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const recursosContainer = document.getElementById('recursos-container');
    const addResourceButton = document.getElementById('add-resource');
    const modalCloseButton = document.getElementById('modal-close'); // Asegúrate de tener un botón para cerrar el modal
    let recursosAgregados = 0;
    let recursosDisponibles = {{ $recursosDisponiblesCount }}; // Cantidad de recursos disponibles
    let selectedResources = new Set(); // Para almacenar los recursos seleccionados

    addResourceButton.addEventListener('click', function() {
        if (recursosAgregados < recursosDisponibles) {
            recursosAgregados++;

            const newField = document.createElement('div');
            newField.classList.add('border', 'border-gray-300', 'rounded-lg', 'shadow-sm', 'overflow-hidden', 'mb-4');
            newField.innerHTML = `
                <div class="p-4 bg-gray-100 cursor-pointer flex justify-between items-center" id="resource-header-${recursosAgregados}">
                    <h3 class="font-medium text-gray-700">{{ __('Recurso') }} ${recursosAgregados}</h3>
                    <div class="flex space-x-2">
                        <button type="button" class="text-blue-600 hover:text-blue-800 toggle-resource" id="toggle-resource-${recursosAgregados}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                        <button type="button" class="flex items-center text-red-600 hover:text-red-800 remove-resource" id="remove-resource-${recursosAgregados}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4 resource-content" id="resource-content-${recursosAgregados}" style="display: block;">
                    <div class="form-group">
                        <label for="idRecurso-${recursosAgregados}" class="block text-gray-700 font-medium">{{ __('Seleccione un recurso') }}</label>
                        <select name="idRecurso[]" id="idRecurso-${recursosAgregados}" class="form-control select2 w-full mt-1" required>
                            <option value="">{{ __('Seleccione un recurso') }}</option>
                            @foreach($recursos as $recurso)
                                @if($recurso->estado == 1)
                                    <option value="{{ $recurso->id }}">{{ $recurso->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-4">
                        <label for="fecha_devolucion-${recursosAgregados}" class="block text-gray-700 font-medium">{{ __('Fecha de devolución') }}</label>
                        <input type="date" name="fecha_devolucion[]" id="fecha_devolucion-${recursosAgregados}" class="form-control w-full mt-1 bg-gray-100 border border-gray-300 rounded-lg" min="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
            `;

            // Añadir el nuevo campo al contenedor de recursos
            recursosContainer.appendChild(newField);

            // Event Listener para el botón de colapsar/expandir
            const toggleButton = newField.querySelector(`#toggle-resource-${recursosAgregados}`);
            const resourceContent = newField.querySelector(`#resource-content-${recursosAgregados}`);

            toggleButton.addEventListener('click', function () {
                resourceContent.style.display = resourceContent.style.display === "block" ? "none" : "block";
            });

            // Event Listener para el botón de eliminar recurso
            const removeButton = newField.querySelector(`#remove-resource-${recursosAgregados}`);
            removeButton.addEventListener('click', function () {
                const selectElement = newField.querySelector(`#idRecurso-${recursosAgregados}`);
                // Eliminar el recurso de la lista de recursos seleccionados
                selectedResources.delete(selectElement.value);
                newField.remove();
                recursosAgregados--;
                // Actualizar opciones en otros selects
                updateResourceOptions();
            });

            // Event Listener para el cambio en el select de recursos
            const selectElement = newField.querySelector(`#idRecurso-${recursosAgregados}`);
            selectElement.addEventListener('change', function () {
                const selectedValue = this.value;
                if (selectedValue) {
                    selectedResources.add(selectedValue); // Agregar el recurso a la lista de seleccionados
                    updateResourceOptions(); // Actualizar las opciones en los otros selects
                }
            });

            // Actualizar las opciones en los selects al eliminar un recurso
            updateResourceOptions();

        } else {
            // Mensaje si no hay más recursos disponibles
            Swal.fire({
                icon: 'warning',
                title: 'Recursos no disponibles',
                text: 'No se pueden agregar más recursos, no hay disponibles.',
                timer: 2500,
                showConfirmButton: false
            });
        }
    });

    // Función para actualizar las opciones en los selects de recursos
    function updateResourceOptions() {
        const allSelects = recursosContainer.querySelectorAll('select[id^="idRecurso-"]');

        // Primero, habilitar todas las opciones
        allSelects.forEach(select => {
            const options = select.querySelectorAll('option');
            options.forEach(option => {
                option.style.display = 'block'; // Mostrar todas las opciones inicialmente
            });
        });

        // Deshabilitar opciones que ya están seleccionadas en otros selects
        selectedResources.forEach(resourceId => {
            allSelects.forEach(select => {
                const optionToHide = Array.from(select.options).find(option => option.value === resourceId);
                if (optionToHide) {
                    optionToHide.style.display = 'none'; // Ocultar opciones ya seleccionadas
                }
            });
        });
    }

    // Event Listener para cerrar el modal
    modalCloseButton.addEventListener('click', function () {
        // Restablecer el conjunto de recursos seleccionados
        selectedResources.clear();
        // Reiniciar el contenedor de recursos
        recursosContainer.innerHTML = '';
        recursosAgregados = 0; // Restablecer contador de recursos
    });
});



</script>
<script>
    // Verificar si hay un mensaje de éxito en la sesión
    @if(Session::has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ Session::get('success') }}',
            timer: 1500,
            showConfirmButton: false
        });
    @endif
</script>
@stop
<script>
function confirmarDevolucion(detalleId, nombreRecurso, nroSerie) {
    const estado = document.getElementById(`estado-${detalleId}`).value;
    let estadoTexto = estado == 1 ? 'Disponible' : 'Dañado';

    let mensaje = `<strong>Recurso:</strong> ${nombreRecurso} <br> <strong>Número de serie:</strong> ${nroSerie}`;

    if (estado == 4) { // Si el estado es "Dañado"
        mensaje += `<br><strong>¡Atención!</strong> Estás marcando este recurso como <strong>DAÑADO</strong>.`;
    }

    Swal.fire({
        title: 'Confirmar devolución',
        html: `${mensaje}`, // Muestra el nombre del recurso y el número de serie en negrita
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, envía el formulario
            document.getElementById(`devolucionForm-${detalleId}`).submit();
        }
    });
}
</script>