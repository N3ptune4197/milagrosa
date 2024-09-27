@extends('adminlte::page')

@section('title', 'Gestión de Préstamos')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
          <!-- Botón para abrir el modal -->
        <a href="#" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#prestamoModal" data-placement='left'>
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
    
    <div class="card">
        <div class="card-body">
            <!-- Formulario de Filtros -->
            <form method="GET" action="{{ route('prestamos.index') }}" class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="personal_name" class="block text-sm font-medium text-gray-700">Nombre del Profesor</label>
                        <input type="text" name="personal_name" id="personal_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('personal_name') }}">
                    </div>
        
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Préstamo (Desde)</label>
                        <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('start_date') }}">
                    </div>
        
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Préstamo (Hasta)</label>
                        <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('end_date') }}">
                    </div>
        
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Seleccionar Estado</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="desactivo" {{ request('estado') == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                        </select>
                    </div>
                </div>
        
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">Buscar</button>
                    <a href="{{ route('prestamos.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm hover:bg-gray-600">Limpiar Filtros</a>
                </div>
            </form>

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
                            <th>Cantidad Total</th>
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
                                    <td>{{ $prestamo->cantidad_total }}</td>
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
                          <!-- Modal para marcar como devuelto -->

                                       <!-- Modal para marcar como devuelto -->

                                                    <!-- Modal para marcar como devuelto -->

                                <div class="modal fade" id="devolucionModal-{{ $detalle->id }}" tabindex="-1" role="dialog" aria-labelledby="devolucionModalLabel-{{ $detalle->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="devolucionModalLabel-{{ $detalle->id }}">Marcar como devuelto</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="devolucionForm-{{ $detalle->id }}" action="{{ route('prestamos.markAsReturned', $detalle->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group">
                                                        <label for="estado-{{ $detalle->id }}">Estado del recurso</label>
                                                        <select name="estado" id="estado-{{ $detalle->id }}" class="form-control">
                                                            <option value="1">Disponible</option>
                                                            <option value="4">Dañado</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="observacion-{{ $detalle->id }}">Observación (opcional)</label>
                                                        <textarea name="observacion" id="observacion-{{ $detalle->id }}" class="form-control" placeholder="Ingrese observación sobre el estado del recurso al devolverlo (por ejemplo, si está dañado)."></textarea>
                                                    </div>

                                                    <button type="submit" class="btn btn-success">Confirmar devolución</button>
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
        <div class="modal-dialog modal-lg flex items-center justify-center" role="document">
            <div class="modal-content rounded-xl border-4 border-black">
                <div class="modal-header bg-blue-500 text-white flex justify-between items-center p-4 border-b-10 border-blue-800">
                    <h5 class="modal-title text-center flex-1 font-bold text-lg" id="prestamoModalLabel">{{ __('Crear Préstamo') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí empieza el formulario -->
                    <form action="{{ route('prestamos.store') }}" method="POST">
                        @csrf
                        <div class="row padding-1 p-1">
                            <div class="col-md-12">
                                <div class="form-group mb-5">
                                    <label for="id_personal" class="form-label">{{ __('Personal') }}</label>
                                    <select name="idPersonal" class="js-example-basic-single rounded-lg form-control block w-full px-4 border border-gray-300 shadow-sm" id="id_personal" required>
                                        <option value="">Seleccione Personal</option>
                                        @foreach ($personals as $personal)
                                            <option value="{{ $personal->id }}">{{ $personal->nombres }} {{ $personal->a_paterno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                

                                <div class="form-group mb-5">
                                    <label for="fecha_prestamo" class="form-label">{{ __('Fecha Prestamo') }}</label>
                                    <input type="text" name="fecha_prestamo" class="form-control" value="{{ now()->format('d/m/Y') }}" id="fecha_prestamo" readonly>
                                </div>

                                <div class="form-group mb-5">
                                    <label for="cantidad_total" class="form-label">{{ __('Cantidad Total') }}</label>
                                    <input type="number" name="cantidad_total" class="form-control" id="cantidad_total" placeholder="Cantidad Total" readonly>
                                </div>


                                <!-- Selección de Recursos -->
                                <div class="form-group">
                                    <div id="recursos-container">
                                        <div class="resource-item row align-items-end ">
                                            <div class="col-md-6">
                                                <label for="recurso[]" class="form-label">{{ __('Recurso') }}</label>

                                                <select name="idRecurso[]" class="form-control select2" required>
                                                    <option value="">Seleccione un recurso</option>
                                                    @foreach($recursos as $recurso)
                                                        <option value="{{ $recurso->id }}" data-cantidad="{{ $recurso->cantidad }}">{{ $recurso->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                
                                            <div class="col-md-4">
                                                <label for="fecha_devolucion[]" class="form-label">{{ __('Fecha de devolución') }}</label>
                                                <input type="date" name="fecha_devolucion[]" class="form-control" min="{{ now()->format('Y-m-d') }}" required>
                                            </div>
                                
                                            <div class="col-md-2 text-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-resource" style="display: none;">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-resource" class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus"></i> {{ __('Añadir Recurso') }}
                                    </button>
                                </div>
                                

                                <!-- Botón de enviar -->
                                <hr>
                                <hr>
                                <div class="col-md-12 mt-2 flex justify-end gap-1">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                                </div>
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
    <!-- Incluye jQuery una sola vez -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Incluye Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Incluye Select2 una sola vez -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Incluye DataTables JS -->
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



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
        // Inicializa Select2 en el campo con ID 'id_personal'
        $('#id_personal').select2({
            width: '100%',
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
        const cantidadTotalInput = document.getElementById('cantidad_total');
        const addResourceButton = document.getElementById('add-resource');
        const maxRecursos = {{ $recursos->count() }};
        let recursosAgregados = recursosContainer.querySelectorAll('select').length;

        function updateCantidadTotal() {
            let cantidadTotal = 0;
            const selects = recursosContainer.querySelectorAll('select');
            selects.forEach(select => {
                const opcionesSeleccionadas = Array.from(select.selectedOptions);
                opcionesSeleccionadas.forEach(opcion => {
                    cantidadTotal += parseInt(opcion.getAttribute('data-cantidad')) || 0;
                });
            });
            cantidadTotalInput.value = cantidadTotal;
        }

        addResourceButton.addEventListener('click', function() {
            if (recursosAgregados < maxRecursos) {
                const newField = document.createElement('div');
                newField.classList.add('resource-item', 'row', 'mb-3');
                newField.innerHTML = `
                    <div class="col-md-6">
                        <select name="idRecurso[]" class="form-control" required>
                            <option value="">Seleccione un recurso</option>
                            @foreach($recursos as $recurso)
                                <option value="{{ $recurso->id }}" data-cantidad="{{ $recurso->cantidad }}">{{ $recurso->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="date" name="fecha_devolucion[]" class="form-control " required>
                    </div>

                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-resource">&times;</button>
                    </div>
                `;
                recursosContainer.appendChild(newField);
                recursosAgregados++;

                if (recursosAgregados >= maxRecursos) {
                    addResourceButton.style.display = 'none';
                }

                newField.querySelector('.remove-resource').addEventListener('click', function () {
                    newField.remove();
                    recursosAgregados--;
                    if (recursosAgregados < maxRecursos) {
                        addResourceButton.style.display = 'inline-block';
                    }
                    updateCantidadTotal();
                });

                updateCantidadTotal();
            }
        });

        recursosContainer.querySelectorAll('.remove-resource').forEach(function (button) {
            button.addEventListener('click', function () {
                this.parentElement.parentElement.remove();
                recursosAgregados--;
                if (recursosAgregados < maxRecursos) {
                    addResourceButton.style.display = 'inline-block';
                }
                updateCantidadTotal();
            });
        });

        updateCantidadTotal();
    });
</script>

@stop