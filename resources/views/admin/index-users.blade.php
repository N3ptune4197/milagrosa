@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between mb-2">
        <h1><i class="bi bi-person"></i> Usuarios</h1>
    </div>
@stop

@section('content')
@include('partials.sidebar')
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Botón para abrir modal de crear usuario -->
    <p class="mb-0">Aquí puedes agregar, ver, editar, eliminar la información sobre los usuarios.</p>
    <button type="button" class="btn btn-primary text-white py-3 px-4" data-toggle="modal" data-target="#createUserModal">
        {{ __('Crear Nuevo') }}
    </button>
    
</div>
    <!-- Tabla de usuarios -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="personalsTable" class="table table-striped table-bordered mt-2 table-hover" style="width:100%">
                    <thead class="bg-vino text-white">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @if($user->id !== auth()->id()) <!-- Excluyendo al usuario actual -->
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>
                                        <!-- Botón para abrir modal de editar usuario -->
                                        <a href="#" class="btn btn-sm btn-outline-primary" onclick="confirmEdit({{ $user->id }})">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block form-delete-user">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal para editar usuario -->
                                <div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg rounded-lg">
                                            <div class="modal-header bg-vino text-white rounded-top">
                                                <h5 class="modal-title mx-auto font-bold" id="editUserModalLabel">Editar Usuario</h5>
                                                <button type="button" class="btn-close text-white" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-light p-4">
                                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label text-gray-600">Nombre</label>
                                                        <input type="text" class="form-control rounded-md py-2 px-3 border border-gray-300" id="name" name="name" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label text-gray-600">Correo Electrónico</label>
                                                        <input type="email" class="form-control rounded-md py-2 px-3 border border-gray-300" id="email" name="email" value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label text-gray-600">Rol</label>
                                                        <select class="form-control rounded-md py-2 px-3 border border-gray-300" id="role" name="role" required>
                                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4 py-2 me-2" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal para crear usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-lg">
                <div class="modal-header bg-vino text-white rounded-top">
                    <h5 class="modal-title mx-auto font-bold" id="createUserModalLabel">Crear Usuario</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label text-gray-600">Nombre</label>
                            <input type="text" class="form-control rounded-md py-2 px-3 border border-gray-300" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-gray-600">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-md py-2 px-3 border border-gray-300" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label text-gray-600">Contraseña</label>
                            <input type="password" minlength="8" class="form-control rounded-md py-2 px-3 border border-gray-300" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-gray-600">Confirmar Contraseña</label>
                            <input type="password" class="form-control rounded-md py-2 px-3 border border-gray-300" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label text-gray-600">Rol</label>
                            <select class="form-control rounded-md py-2 px-3 border border-gray-300" id="role" name="role" required>
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 py-2 me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Crear Usuario</button>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/89c262ed76.js" crossorigin="anonymous"></script>
    <script>
         new DataTable('#personalsTable', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
            },
        });

        // Confirmación para eliminar usuario
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Enviar el formulario de eliminación
                    }
                })
            });
        });

        // Confirmación para editar usuario
        function confirmEdit(userId) {
            event.preventDefault(); // Evita que el modal se abra automáticamente

            // Muestra la alerta de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Estás a punto de editar este usuario.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, editar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si se confirma, abre el modal manualmente
                    $('#editUserModal' + userId).modal('show');
                }
            });
        }

        // Mostrar SweetAlert al crear usuario
        @if(session('success'))
            Swal.fire({
                title: 'Éxito!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif
    
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
