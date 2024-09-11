@extends('adminlte::page')

@section('title', 'Personals Management')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-person"></i> Personal</h1>
        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#personalModal">
            {{ __('Crear Nuevo Personal') }}
        </button>
    </div>
@stop

@section('content')
    <p class="mb-4">Here you can manage the personals information.</p>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="personalsTable" class="table table-striped table-bordered mt-2 table-hover mb-3" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nombres</th>
                            <th>A. Paterno</th>
                            <th>A. Materno</th>
                            <th>Telefono</th>
                            <th>Documento</th>
                            <th>Nro Documento</th>
                            <th>Cargo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personals as $personal)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $personal->nombres }}</td>
                                <td>{{ $personal->a_paterno }}</td>
                                <td>{{ $personal->a_materno }}</td>
                                <td>{{ $personal->telefono }}</td>
                                <td>{{ $personal->tipodoc->abreviatura }}</td>
                                <td>{{ $personal->nro_documento }}</td>
                                <td>{{ $personal->cargo }}</td>
                                <td>
                                    <form action="{{ route('personals.destroy', $personal->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('personals.show', $personal->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('personals.edit', $personal->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}
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

   
<!-- Modal de Creación -->
<div class="modal fade" id="personalModal" tabindex="-1" aria-labelledby="personalModalLabel" aria-hidden="true">
    <div class="modal-dialog flex items-center justify-center" role="document">
        <div class="modal-content rounded-xl border-4 border-black">
            <div class="modal-header bg-blue-500 text-white flex justify-between items-center p-4 border-b-10 border-blue-800"> 
                <h5 class="modal-title text-center flex-1 font-bold text-lg" id="personalModalLabel">{{ __('Agregar Personal') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="personalForm" method="POST" action="{{ route('personals.store') }}">
                    @csrf
                    <div class="flex space-x-4">
                        <!-- Columna izquierda -->
                        <div class="flex-1 space-y-4">
                            <div class="mb-4">
                                <label for="id_tipodocs" class="block text-sm font-medium text-gray-700">{{ __('Tipo de Documento') }}</label>
                                <select name="id_tipodocs" id="id_tipodocs" class="mt-1 block w-full bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">{{ __('Seleccione un tipo de documento') }}</option>
                                    @foreach($tipodocs as $tipodoc)
                                        <option value="{{ $tipodoc->id }}">{{ $tipodoc->abreviatura }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('id_tipodocs', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                            </div>

                            <div class="mb-4">
                                <label for="nro_documento" class="block text-sm font-medium text-gray-700">{{ __('Ingresar Número de documento') }}</label>
                                <div class="relative">
                                    <input type="text" name="nro_documento" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('nro_documento') }}" id="nro_documento" placeholder="Nro Documento" onkeypress='return validaNumericos(event)'>
                                    {!! $errors->first('nro_documento', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-1 text-gray-500 hover:text-gray-700" id="buscar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
                                <input type="text" name="telefono" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('telefono') }}" id="telefono" placeholder="Teléfono">
                                {!! $errors->first('telefono', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="flex-1 space-y-4">
                            <div class="mb-4">
                                <label for="nombres" class="block text-sm font-medium text-gray-700">{{ __('Nombres') }}</label>
                                <input type="text" name="nombres" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('nombres') }}" id="nombres" placeholder="Nombres" oninput="this.value = this.value.toUpperCase()">
                                {!! $errors->first('nombres', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                            </div>

                            <div class="mb-4">
                                <label for="a_paterno" class="block text-sm font-medium text-gray-700">{{ __('Apellido Paterno') }}</label>
                                <input type="text" name="a_paterno" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('a_paterno') }}" id="a_paterno" placeholder="Apellido Paterno" oninput="this.value = this.value.toUpperCase()">
                                {!! $errors->first('a_paterno', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                            </div>

                            <div class="mb-4">
                                <label for="a_materno" class="block text-sm font-medium text-gray-700">{{ __('Apellido Materno') }}</label>
                                <input type="text" name="a_materno" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('a_materno') }}" id="a_materno" placeholder="Apellido Materno" oninput="this.value = this.value.toUpperCase()">
                                {!! $errors->first('a_materno', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-1">
                        <label for="cargo" class="block text-sm font-medium text-gray-700">{{ __('Cargo') }}</label>
                        <input type="text" name="cargo" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('cargo') }}" id="cargo" placeholder="Cargo" oninput="this.value = this.value.toUpperCase()">
                        {!! $errors->first('cargo', '<div class="text-red-500 text-xs mt-1">:message</div>') !!}
                    </div>

                    <input type="hidden" name="personal_id" id="personal_id">
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Guardar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('personalForm');
        form.addEventListener('submit', function(event) {
            let isValid = true;

            
            document.querySelectorAll('.text-red-500').forEach(el => el.innerText = '');

          
            let telefono = document.getElementById('telefono').value.trim();
            let cargo = document.getElementById('cargo').value.trim();

            if (!telefono) {
                isValid = false;
                document.getElementById('telefono').nextElementSibling.innerText = 'El teléfono es obligatorio.';
            }

            if (!cargo) {
                isValid = false;
                document.getElementById('cargo').nextElementSibling.innerText = 'El cargo es obligatorio.';
            }

            if (!isValid) {
                event.preventDefault();
                return false; // Evitar que el modal se cierre
            }

            /
        });

        
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                let field = this;
                field.nextElementSibling.innerText = ''; s
            });
        });
    });
</script>



    
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>

    <script>
        $(document).ready(function() {
            new DataTable('#personalsTable', {
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
                },
            });

            $("#id_tipodocs").change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === "1") {
                    $("#nro_documento").attr("maxlength", 8);
                    $("#nro_documento").attr("minlength", 8);
                    $("#nro_documento").attr("placeholder", "Ingresar DNI");
                    $("#nro_documento").attr("readonly", false);
                } else if (selectedValue === "2") {
                    $("#nro_documento").attr("maxlength", 9);
                    $("#nro_documento").attr("minlength", 9);
                    $("#nro_documento").attr("placeholder", "Ingresar Número de Extranjero");
                    $("#nro_documento").attr("readonly", false);
                } else {
                    $("#nro_documento").attr("readonly", true);
                    $("#nro_documento").attr("placeholder", "Seleccionar Tipo de Documento");
                }
            });

            $("#buscar").click(function() {
                var tipoDoc = $("#id_tipodocs").val();
                var documento = $("#nro_documento").val();

                if (tipoDoc === "1") {
                    if (documento.length === 8) {
                        buscarDni(documento);
                    } else {
                        alert("El DNI debe tener 8 dígitos.");
                    }
                } else if (tipoDoc === "2") {
                    if (documento.length === 9) {
                        buscarExtranjero(documento);
                    } else {
                        alert("El número de extranjero debe tener 9 dígitos.");
                    }
                } else {
                    alert("Seleccione un tipo de documento válido.");
                }
            });

            function buscarDni(dni) {
                $.ajax({
                    url: `/buscar-dni/${dni}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $("#nombres").val(response.nombres).prop('readonly', true);
                            $("#a_paterno").val(response.apellidoPaterno).prop('readonly', true);
                            $("#a_materno").val(response.apellidoMaterno).prop('readonly', true);
                        } else {
                            alert("No se encontraron datos para el DNI ingresado.");
                        }
                    },
                    error: function() {
                        alert("Hubo un error al realizar la solicitud.");
                    }
                });
            }

            function buscarExtranjero(cee) {
                $.ajax({
                    url: `https://api.factiliza.com/pe/v1/cee/info/${cee}`,
                    method: 'GET',
                    headers: {
                        "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI3MzciLCJuYW1lIjoiQ2FybG9zIENoZXJvIE1lbmRvemEiLCJlbWFpbCI6ImNhcmxvc2NoZXJvMTM0QGdtYWlsLmNvbSIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.v4e3xsg6OEhF2L9NAELlydgMnHONlnKlejh7IPzz9nA"
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            $("#nombres").val(response.data.name).prop('readonly', true);
                            $("#a_paterno").val(response.data.last_name).prop('readonly', true);
                            $("#a_materno").val(response.data.middle_name).prop('readonly', true);
                        } else {
                            alert("No se encontraron datos para el número de extranjero ingresado.");
                        }
                    },
                    error: function() {
                        alert("Hubo un error al realizar la solicitud.");
                    }
                });
            }
        });
    </script>
@stop


@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>

    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@stop


