@extends('adminlte::page')

@section('title', 'Préstamos')
@include('partials.navbar')
@section('content_header')
<div class="d-flex justify-content-between mb-2">
    <h1><i class="bi bi-hourglass-split"></i> Prestamos</h1>
</div>
@stop
@section('content')


@include('partials.sidebar')


<p class="mb-0">Aquí puedes agregar y ver la información sobre los Prestamos.</p>

    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <!-- Botón Exportar a la izquierda -->
                <div class="relative inline-block text-left">
                    <button type="button" class="btn btn-secondary font-semibold px-5 py-2 text-white dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Exportar
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('prestamos.exportPdf', request()->query()) }}">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </a>
                    </div>
                </div>
            
                <!-- Botón Crear Nuevo Préstamo a la derecha -->
                <a href="#" class="btn btn-primary text-white font-semibold px-4 py-3   "
                    data-bs-toggle="modal" data-bs-target="#prestamoModal" data-placement="right">
                    Crear Nuevo Préstamo
                </a>
            </div>
            
           <!-- Botón para abrir los filtros flotantes -->
            <div class="fixed top-11 right-4 z-50">
                <button id="openFilters" onclick="toggleFilters()" class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Contenedor de filtros flotantes -->
            <div id="filtersContainer" class="fixed top-11 right-0 w-80 h-screen bg-white shadow-xl transform transition-transform translate-x-full z-50 overflow-y-auto p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-700">Filtros</h2>
                    <!-- Botón para cerrar los filtros -->
                    <button id="closeFilters" onclick="toggleFilters()" class="text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Filtros desplegables -->
                <div>
                    <form method="GET" action="{{ route('prestamos.index') }}">
                        <div class="grid grid-cols-1 gap-4 items-end">
                            
                           <!-- Nombre del Personal -->
                    <select id="personal_name" name="personal_name"
                    class="selectpicker font-bold block w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm shadow-sm focus:outline-none"
                    data-live-search="true" data-width="100%" data-size="3">
                    <option value="" disabled {{ request('personal_name') ? '' : 'selected' }}>Seleccione un personal</option>
                    @foreach ($personals as $personal)
                    <option value="{{ $personal->id }}" {{ request('personal_name') == $personal->id ? 'selected' : '' }}>
                        {{ $personal->nombres }} {{ $personal->a_paterno }}
                    </option>
                    @endforeach
                    </select>

                            <!-- Fecha Desde -->
                            <div class="relative">
                                <label for="start_date" class="block text-sm text-gray-600 mb-1">Desde</label>
                                <input type="date" name="start_date" id="start_date"
                                    class="block w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm shadow-sm focus:outline-none"
                                    value="{{ request('start_date') }}">
                            </div>

                            <!-- Fecha Hasta -->
                            <div class="relative">
                                <label for="end_date" class="block text-sm text-gray-600 mb-1">Hasta</label>
                                <input type="date" name="end_date" id="end_date"
                                    class="block w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm shadow-sm focus:outline-none"
                                    value="{{ request('end_date') }}">
                            </div>

                            <!-- Estado -->
                            <div class="relative">
                                <label for="estado" class="block text-sm text-gray-600 mb-1">Estado</label>
                                <select name="estado" id="estado" class="selectpicker font-bold block w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm shadow-sm focus:outline-none"
                                    data-live-search="true" data-size="3">
                                    <option value="">Seleccionar Estado</option>
                                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="desactivo" {{ request('estado') == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                                </select>
                            </div>

                            <!-- Número de Serie -->
                            <div class="relative">
                                <label for="serial_number" class="block text-sm text-gray-600 mb-1">Número de Serie</label>
                                <select id="serial_number" name="serial_number"
                                    class="selectpicker font-bold block w-full bg-gray-100 p-2 rounded-lg text-gray-700 text-sm shadow-sm focus:outline-none"
                                    data-live-search="true" data-width="100%" data-size="3">
                                    <option value="" selected>Seleccione un número de serie</option>
                                    @php
                                        $nroSerieUnicos = [];
                                    @endphp
                                    @foreach ($prestamos as $prestamo)
                                        @foreach ($prestamo->detalleprestamos as $detalle)
                                            @php
                                                $nroSerie = $detalle->recurso->nro_serie ?? null;
                                            @endphp
                                            @if ($nroSerie && !in_array($nroSerie, $nroSerieUnicos))
                                                <option value="{{ $nroSerie }}">{{ $nroSerie }}</option>
                                                @php
                                                    $nroSerieUnicos[] = $nroSerie;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <!-- Botones de acción dentro de los filtros -->
                        <div class="mt-6 flex justify-center space-x-4">
                            <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                                Buscar
                            </button>
                            <a href="{{ route('prestamos.index') }}" class="px-5 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-colors">
                                Limpiar Filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Separador -->
            <hr class="my-6 border-t-2 border-gray-200">
            
            <!-- Tabla de Préstamos -->
            <div class="table-responsive">
                <table id="prestamosTable" class="table table-striped table-bordered mt-2 table-hover mb-2" style="width:100%">
                    <thead class="bg-[#9c1515] text-white">
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
                        @php
                            use Carbon\Carbon;
                            $fechaActual = Carbon::now();
                        @endphp
                    
                        @foreach ($prestamos as $prestamo)
                            @foreach ($prestamo->detalleprestamos as $detalle)
                                @php
                                    // Convertir la fecha_devolucion a un objeto Carbon
                                    $fechaDevolucion = Carbon::parse($detalle->fecha_devolucion);
                                    // Verificar si la fecha actual es mayor que la fecha de devolución
                                    $atrasado = $fechaActual->gt($fechaDevolucion);
                                @endphp
                    
                                <tr id="loan-{{ $prestamo->id }}-{{ $detalle->id }}"> <!-- Asignando ID único -->
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $prestamo->personal->nombres ?? 'N/A' }} {{ $prestamo->personal->a_paterno ?? '' }}</td>
                                    <td>{{ $prestamo->fecha_prestamo }}</td>
                                    <td>{{ $detalle->fecha_devolucion }}</td>
                                    <td>{{ $prestamo->fecha_devolucion_real }}</td>
                                    <td>{{ $prestamo->observacion }}</td>
                                    <td>
                                        {{ $detalle->recurso->nro_serie ?? 'N/A' }}
                                        @if($detalle->recurso->categoria)
                                            ({{ $detalle->recurso->categoria->nombre ?? 'Sin categoría' }})
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Mostrar el estado (activo o desactivo) -->
                                        @if ($prestamo->estado == 'activo')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Activo</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Desactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Botón de opciones: Marcar como devuelto si el estado es activo -->
                                        @if ($prestamo->estado == 'activo')
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#devolucionModal-{{ $detalle->id }}">
                                                Marcar como devuelto
                                            </button>
                                        @else
                                            <span class="badge badge-secondary">Devuelto</span>
                                        @endif
                                    </td>
                                </tr>
                    
                                
<!-- Modal para marcar como devuelto -->
<div class="modal fade" id="devolucionModal-{{ $detalle->id }}" tabindex="-1" role="dialog" aria-labelledby="devolucionModalLabel-{{ $detalle->id }}" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center min-h-screen">
        <div class="modal-content bg-white rounded-lg shadow-lg border border-gray-300">
            <div class="modal-header bg-vino text-white flex justify-between items-center p-4 rounded-t-lg">
                <h5 class="modal-title font-semibold" id="devolucionModalLabel-{{ $detalle->id }}">{{ __('Marcar como devuelto') }}</h5>
                <button type="button" class="text-white hover:text-gray-200" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body p-6 bg-crema">
                <form id="devolucionForm-{{ $detalle->id }}" action="{{ route('prestamos.markAsReturned', $detalle->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Categoría del recurso -->
                    <div class="form-group mb-4">
                        <label for="categoríaRecurso-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Categoría del recurso') }}</label>
                        <input type="text" id="categoríaRecurso-{{ $detalle->id }}" class="form-control w-full mt-1 bg-gray-100 border border-gray-300 rounded-lg" value="{{ $detalle->recurso->categoria->nombre }}" readonly>
                    </div>

                    <!-- Número de serie -->
                    <div class="form-group mb-4">
                        <label for="nroSerie-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Número de serie') }}</label>
                        <input type="text" id="nroSerie-{{ $detalle->id }}" class="form-control w-full mt-1 bg-gray-100 border border-gray-300 rounded-lg" value="{{ $detalle->recurso->nro_serie }}" readonly>
                    </div>

                    <!-- Estado del recurso -->
                    <div class="form-group mb-4">
                        <label for="estado-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Estado del recurso') }}</label>
                        <select name="estado" id="estado-{{ $detalle->id }}" class="form-control w-full mt-1 bg-white border border-gray-300 rounded-lg" required>
                            <option value="1" {{ $detalle->recurso->estado == 1 ? 'selected' : '' }}>{{ __('Disponible') }}</option>
                            <option value="4" {{ $detalle->recurso->estado == 4 ? 'selected' : '' }}>{{ __('Dañado') }}</option>
                        </select>
                    </div>

                    <!-- Observación (opcional) -->
                    <div class="form-group mb-4">
                        <label for="observacion-{{ $detalle->id }}" class="block text-gray-700 font-medium">{{ __('Observación (opcional)') }}</label>
                        <textarea name="observacion" id="observacion-{{ $detalle->id }}" class="form-control w-full mt-1 bg-white border border-gray-300 rounded-lg" rows="3" placeholder="{{ __('Ingrese observación sobre el estado del recurso al devolverlo') }}"></textarea>
                    </div>

                    <!-- Botón de confirmar devolución -->
                    <div class="flex justify-end">
                        <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700" onclick="confirmarDevolucion('{{ $detalle->id }}', '{{ $detalle->recurso->nro_serie }}', '{{ $detalle->recurso->categoria->nombre }}')">
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
        <div class="modal-content border-2 border-maroon rounded-lg">
            <div class="modal-header bg-vino text-white">
                <h5 class="modal-title w-100 text-center font-semibold text-lg" id="prestamoModalLabel">{{ __('Crear Préstamo') }}</h5>
                <button type="button" class="text-white hover:text-gray-200" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body bg-crema p-6">
                <!-- Inicio del formulario -->
                <form action="{{ route('prestamos.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Personal -->
                        <div class="form-group">
                            <label for="id_personal" class="block text-gray-700 font-medium">{{ __('Personal') }}</label>
                            <select name="idPersonal" class="form-control selectpicker w-full mt-1" id="id_personal" required data-live-search="true" data-size="3">
                                <option value="">{{ __('Seleccione un Personal') }}</option>
                                @foreach ($personals as $personal)
                                    <option value="{{ $personal->id }}" >{{ $personal->nombres }} {{ $personal->a_paterno }}</option>
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
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Guardar') }}</button>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    
    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
@stop
    
@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        new DataTable('#prestamosTable', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const recursosContainer = document.getElementById('recursos-container');
    const addResourceButton = document.getElementById('add-resource');
    const modalCloseButton = document.getElementById('modal-close'); // Asegúrate de tener un botón para cerrar el modal
    let recursosAgregados = 0;
let recursosDisponibles = {{ $recursosDisponiblesCount }};
let selectedResources = new Set(); // Para almacenar los recursos seleccionados

// Añadir evento para añadir un recurso
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
                    <select name="idRecurso[]" id="idRecurso-${recursosAgregados}" 
                            class="form-control selectpicker w-full mt-1" 
                            data-live-search="true" 
                            data-dropup-auto="false" 
                            data-size="3" 
                            required>
                        <option value="">{{ __('Seleccione un recurso') }}</option>
                        @foreach($categorias as $categoria)
                            @foreach($recursos as $recurso)
                                @if($recurso->estado == 1 && $recurso->id_categoria == $categoria->id)
                                    <option value="{{ $recurso->id }}" data-subtext="{{ $categoria->nombre }}">
                                        {{ $recurso->nro_serie }}
                                    </option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-4">
                    <label for="fecha_devolucion-${recursosAgregados}" class="block text-gray-700 font-medium">{{ __('Fecha de devolución') }}</label>
                    <input type="datetime-local" name="fecha_devolucion[]" id="fecha_devolucion-${recursosAgregados}" class="form-control w-full mt-1 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 ease-in-out" min="{{ now()->format('Y-m-d') }}T{{ now()->format('H:i') }}" required>
                </div>
            </div>
        `;

        // Añadir el nuevo campo al contenedor de recursos
        recursosContainer.appendChild(newField);

        // Inicializar el selectpicker para el nuevo select agregado
        $(`#idRecurso-${recursosAgregados}`).selectpicker({
            liveSearch: true,
            dropupAuto: false,
            size: 5,
            mobile: false 
        });

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
            selectedResources.delete(selectElement.value);
            newField.remove();
            recursosAgregados--;
            updateResourceOptions(); // Actualizar opciones al eliminar
        });

        // Event Listener para el cambio en el select de recursos
        const selectElement = newField.querySelector(`#idRecurso-${recursosAgregados}`);
        selectElement.addEventListener('change', function () {
            const previousValue = [...selectedResources].find(resource => resource === this.getAttribute('data-previous-value'));
            const selectedValue = this.value;

            // Verificar si el recurso ya está seleccionado
            if (selectedResources.has(selectedValue)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Recurso ya seleccionado',
                    text: 'Este recurso ya ha sido agregado.',
                    timer: 2500,
                    showConfirmButton: false
                });

                // Restablecer el select al valor anterior
                this.value = this.getAttribute('data-previous-value');
                $(this).selectpicker('refresh');
            } else {
                // Si hay un recurso previamente seleccionado, eliminarlo del conjunto
                if (previousValue) {
                    selectedResources.delete(previousValue);
                }

                // Agregar el nuevo valor seleccionado al conjunto
                if (selectedValue) {
                    selectedResources.add(selectedValue);
                    this.setAttribute('data-previous-value', selectedValue);
                }

                updateResourceOptions(); // Actualizar las opciones en otros selects
            }
        });

        // Actualizar las opciones en los selects cuando se agrega un recurso
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

// Función para actualizar las opciones de recursos en todos los selects
function updateResourceOptions() {
    // Obtener todos los selects de recursos
    const resourceSelects = document.querySelectorAll('select[name="idRecurso[]"]');

    // Recorrer cada select
    resourceSelects.forEach(function(select) {
        const currentValue = select.value; // Guardar valor actual seleccionado
        const options = select.querySelectorAll('option'); // Obtener todas las opciones

        // Recorrer opciones y deshabilitar las seleccionadas en otros selects
        options.forEach(function(option) {
            if (selectedResources.has(option.value) && option.value !== currentValue) {
                option.disabled = true; // Deshabilitar opciones seleccionadas en otros selects
            } else {
                option.disabled = false; // Habilitar opciones no seleccionadas
            }
        });

        // Refrescar selectpicker para aplicar los cambios
        $(select).selectpicker('refresh');
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
function confirmarDevolucion(detalleId, nroSerie, categoriaNombre) {
    // Obtener el estado del recurso
    const estado = document.getElementById(`estado-${detalleId}`).value;
    let estadoTexto = estado == 1 ? 'Disponible' : 'Dañado';

    // Crear el mensaje a mostrar
    let mensaje = `<strong>Categoría:</strong> ${categoriaNombre} <br> <strong>Número de serie:</strong> ${nroSerie}`;

    // Si el recurso está marcado como "Dañado"
    if (estado == 4) {
        mensaje += `<br><strong>¡Atención!</strong> Estás marcando este recurso como <strong>DAÑADO</strong>.`;
    }

    // Mostrar el SweetAlert
    Swal.fire({
        title: 'Confirmar devolución',
        html: `${mensaje}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí se envía el formulario después de la confirmación
            document.getElementById(`devolucionForm-${detalleId}`).submit();
        }
    });
}


</script>
<script>
     function toggleFilters() {
        const filtersContainer = document.getElementById('filtersContainer');
        filtersContainer.classList.toggle('translate-x-full');
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Verificar si la URL contiene un parámetro de ID de préstamo
        const urlParams = new URLSearchParams(window.location.search);
        const loanId = urlParams.get('highlight');
    
        if (loanId) {
            // Seleccionar la fila que tiene el ID del préstamo que se pasó
            const loanRow = document.getElementById(loanId);
    
            if (loanRow) {
                // Añadir una clase para resaltar la fila
                loanRow.classList.add('bg-yellow-300');
    
                // Eliminar la clase de resaltado después de unos segundos (opcional)
                setTimeout(() => {
                    loanRow.classList.remove('bg-yellow-300');
                }, 3000); // 3 segundos
            }
        }
    });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('fecha_devolucion-${recursosAgregados}');
        
        function updateMinDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Mes en formato de 2 dígitos
            const day = String(now.getDate()).padStart(2, '0');
            const hour = String(now.getHours()).padStart(2, '0');
            const minute = String(now.getMinutes()).padStart(2, '0');
            
            // Establecer el valor mínimo de fecha y hora como el actual
            const minDateTime = `${year}-${month}-${day}T${hour}:${minute}`;
            input.min = minDateTime;
            
            // Si la fecha seleccionada es hoy, ajustar la hora mínima
            input.addEventListener('input', function() {
                const selectedDateTime = new Date(input.value);
                const today = new Date(year, now.getMonth(), now.getDate());
                
                if (selectedDateTime >= today && selectedDateTime < now) {
                    // Si el usuario selecciona una hora anterior a la actual en el mismo día
                    input.value = minDateTime; // Ajustar a la hora mínima permitida
                }
            });
        }

        updateMinDateTime();
    });
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
    