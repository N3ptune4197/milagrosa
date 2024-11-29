@extends('adminlte::page')

@section('title', 'Personal')
@include('partials.navbar')
@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-person"></i> Personal</h1>
    </div>
@stop
@section('content')


@include('partials.sidebar')


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
                <table id="personalsTable" class="table table-striped table-bordered my-3 table-hover" style="width:100%">
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
                                <input type="text" name="telefono" id="telefono" onkeypress='return validaNumericos(event)' minlength="9" maxlength="9" class="block w-full mt-1 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 py-2 pl-2 focus:ring-indigo-500 sm:text-sm" placeholder="Teléfono">
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
                    </select>
                </div>

                <!-- Campo 'Otro Cargo' (se muestra solo si se selecciona "Otro") -->
                <div class="mb-4" id="otroCargoDiv" style="display: none;">
                    <label for="otro_cargo" class="block text-sm font-medium text-gray-700">{{ __('Especifique otro cargo') }}</label>
                    <input type="text" name="otro_cargo" id="otro_cargo" class="block w-full mt-1 bg-gray-50 border border-gray-300 py-2 pl-2 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Especifique otro cargo">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap4.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.bootstrap4.css">





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



<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>


<script>
    new DataTable('#personalsTable', {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
        },
        layout: {
        topStart: {
            buttons: ['copy', 'excel', 'pdf', 'colvis']
        }
    }
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

            if (selectedValue === "Documento Nacional de Identidad") {
                $("#nro_documento").attr("maxlength", 8);
                $("#nro_documento").attr("minlength", 8);
                $("#nro_documento").attr("placeholder", "Ingresar DNI");
                $("#nro_documento").attr("readonly", false);
            } else if (selectedValue === "Documento de Extranjeria") {
                $("#nro_documento").attr("maxlength", 9);
                $("#nro_documento").attr("minlength", 9);
                $("#nro_documento").attr("placeholder", "Ingresar Número de Carnet de Extranjería");
                $("#nro_documento").attr("readonly", false);
            } else {
                $("#nro_documento").attr("readonly", true);
                $("#nro_documento").attr("placeholder", "Seleccionar Tipo de Documento");
            }
        });

        $("#buscar").click(function() {
            var tipoDoc = $("#tipodoc").val();
            var documento = $("#nro_documento").val();

            if (tipoDoc === "Documento Nacional de Identidad") {
                if (documento.length === 8) {
                    buscarDni(documento);
                } else {
                    Swal.fire("Error", "El DNI debe tener 8 dígitos.", "error");
                }
            } else if (tipoDoc === "Documento de Extranjeria") {
                if (documento.length === 9) {
                    buscarExtranjero(documento);
                } else {
                    Swal.fire("Error", "El número de Carnet de Extranjería debe tener 9 dígitos.", "error");
                }
            } else {
                Swal.fire("Error", "Seleccione un tipo de documento válido.", "error");
            }
        });

        function buscarDni(dni) {
    if ($("#tipodoc").val() !== "Documento Nacional de Identidad" || dni.length !== 8) {
        Swal.fire("Error", "El DNI debe tener 8 dígitos y debe seleccionar 'Documento Nacional de Identidad'.", "error");
        limpiarCampos();
        return;
    }

    $.ajax({
        url: `/buscar-dni/${dni}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $("#nombres").val(response.nombres).prop('readonly', true).addClass('bg-gray-300 text-gray-500 cursor-not-allowed');
                $("#a_paterno").val(response.apellidoPaterno).prop('readonly', true).addClass('bg-gray-300 text-gray-500 cursor-not-allowed');
                $("#a_materno").val(response.apellidoMaterno).prop('readonly', true).addClass('bg-gray-300 text-gray-500 cursor-not-allowed');
            } else {
                Swal.fire("Información", "No se encontraron datos para el DNI ingresado.", "info");
                limpiarCampos();
            }
        },
        error: function() {
            Swal.fire("Error", "Hubo un error al realizar la solicitud.", "error");
            limpiarCampos();
        }
    });
}

        function buscarExtranjero(cee) {
            $.ajax({
                url: `https://api.factiliza.com/pe/v1/cee/info/${cee}`,
                method: 'GET',
                headers: {
                    "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNzk4MSIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.TAa8diFlvFPb5wAPtRvEhX2Fy4T79VACkIwsiVKtyZw"
                },
                success: function(response) {
                    if (response.status === 200 && cee.length === 9) {
                        $("#nombres").val(response.data.nombres).prop('readonly', true);
                        $("#a_paterno").val(response.data.apellido_paterno).prop('readonly', true);
                        $("#a_materno").val(response.data.apellido_materno).prop('readonly', true);
                    } else {
                        Swal.fire("Información", "Número de extranjero no válido.", "info");
                        limpiarCampos();
                    }
                },
                error: function() {
                    Swal.fire("Error", "Error al buscar número de extranjero.", "error");
                    limpiarCampos();
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
    }).then(() => {
        // Reabre el modal si hay un error
        $('#personalModal').modal('show');
    });
</script>
@endif


<script>
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