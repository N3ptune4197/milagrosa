@extends('adminlte::page')

@section('title', 'Marcas')
@include('partials.navbar')
@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-tags"></i> Marcas</h1>
    </div>
@stop
@section('content')



@include('partials.sidebar')


<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="mb-0">Aquí puedes agregar, ver, editar, eliminar la información sobre las Marcas.</p>
    <button type="button" class="btn btn-primary text-white py-3 px-4" data-bs-toggle="modal" data-bs-target="#marcaModal" onclick="clearForm()">
        {{ __('Crear Nuevo') }}
    </button>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="marcasTable" class="table table-striped table-bordered mt-2 table-hover mb-2" style="width:100%">
                <thead class="bg-[#9c1515] text-white">
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
                                    
                                    <a class="btn btn-sm btn-outline-primary" href="javascript:void(0)" onclick="confirmEdit('{{ $marca->nombre }}', {{ $marca->id }})">
                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                    </a>

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(event, this.form, '{{ $marca->nombre }}')">
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
        <div class="modal-content border-2 border-maroon rounded-lg">
            <div class="modal-header bg-vino text-white rounded-lg">
                <h5 class="modal-title w-100 text-center" id="modalTitleMarca">{{ __('Agregar / Editar Marca') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-crema">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap4.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


    
    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap4.js"></script>

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
    </script>


<script>
    document.getElementById('marcaModal').addEventListener('show.bs.modal', function (event) {
    var modalTitle = document.getElementById('modalTitleMarca'); // Título del modal de marcas
    var marca_id = document.getElementById('marca_id').value; // ID oculto del recurso (marca)

    // Si hay un ID presente, es una edición; si no, es creación
    if (marca_id && marca_id !== '') {
        modalTitle.textContent = 'Editar Marca';
    } else {
        modalTitle.textContent = 'Agregar Marca';
    }
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
    @if ($errors->has('nombre'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Error',
                text: 'El nombre de la marca ya existe. Por favor, ingrese uno nuevo.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#d33'
            }).then(() => {
                // Vuelve a abrir el modal para corregir la entrada
                $('#marcaModal').modal('show');
            });
        });
    </script>
@endif

    
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
