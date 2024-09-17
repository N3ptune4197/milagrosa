@extends('adminlte::page')

@section('title', 'Marcas')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-tags"></i> Marcas</h1>
                
        <a href="#" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#marcaModal">
            {{ __('Crear Nueva') }}
        </a>
    </div>
@stop

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

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
                            <td>{{ ++$i }}</td>
                            <td>{{ $marca->nombre }}</td>
                            <td>{{ $marca->descripcion }}</td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#marcaModal" data-id="{{ $marca->id }}" data-nombre="{{ $marca->nombre }}" data-descripcion="{{ $marca->descripcion }}" data-action="{{ route('marcas.show', $marca->id) }}">
                                    <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                </a>
                                <a class="btn btn-sm btn-success" href="#" data-bs-toggle="modal" data-bs-target="#marcaModal" data-id="{{ $marca->id }}" data-nombre="{{ $marca->nombre }}" data-descripcion="{{ $marca->descripcion }}" data-action="{{ route('marcas.update', $marca->id) }}">
                                    <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                </a>
                                <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de que desea eliminar esta marca?') ? this.closest('form').submit() : false;">
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
                    @method('POST') <!-- Este método se actualizará dinámicamente -->
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

    <script>
        new DataTable('#marcasTable', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });

        // Script para manejar la apertura del modal para edición
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
@stop