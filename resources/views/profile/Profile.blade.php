
@extends('adminlte::page')
@include('partials.navbar')
@section('title', 'Calendario')

@section('content_header')
    <div class="flex align-middle items-center ">

        <span class="bi bi-calendar3 text-2xl "></span>


        <h1 class="ml-2 font-semibold">Calendario</h1>
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
@stop
@section('content')


@include('partials.sidebar')

    <div class="mt-3 w-[100%]">


        <div class="section-one flex flex-col lg:flex-row g-3 px-2 pt-5 pb-5 mb-3 border rounded-2xl w-[100%] bg-white ">
            <div class="mb-5 lg:px-4 py-4 w-[100%] lg:w-[85%] xl:w-[80%] lg:shadow-2xl box-border mx-auto border-t-8 border-orange-600/75 rounded-lg overflow-hidden">
                <div class="titulo flex justify-between px-2 flex-center">

                    <p></p>

                    <h2 class="text-center font-montserrat font-bold my-2">
                        <i class="text-red-400 fa-solid fa-user fa-md "></i> <span class="underline underline-offset-2 text-md lg:text-4xl">Configurar Perfil</span>
                    </h2>
                    


                    <i class="fa-solid fa-gear fa-lg flex items-center"></i>
                </div>


                <div class="contenido mt-3">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('POST')

                        <!-- Nombre de usuario -->
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Nombre de usuario') }}<span class="text-red-600">*</span></label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', auth()->user()->name) }}" required autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- EMAILLLL -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo electrónico') }}<span class="text-red-600">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" required autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <!-- Teléfono -->
                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}<span class="text-red-600">*</span></label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', auth()->user()->phone) }}">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}<span class="text-red-600">*</span></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="***********" >

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirmar contraseña -->
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar contraseña') }}<span class="text-red-600">*</span></label>

                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <!-- Botón para actualizar -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Actualizar perfil') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>


    </div>



@stop


<script src="https://kit.fontawesome.com/89c262ed76.js" crossorigin="anonymous"></script>

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://kit.fontawesome.com/89c262ed76.js" crossorigin="anonymous"></script>



    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

@stop






@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!--    // Calendario           -->
<script src=" https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js "></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>

<!--                    DAISYUI             -->
<script src="https://cdn.tailwindcss.com"></script>


    <script>
        function validaNumericos(event) {
        return event.charCode >= 48 && event.charCode <= 57;
    }
    </script>


    <script>
        function toggleInputs(checkbox) {
            const inputs = document.querySelectorAll('.form-control');
            const saveButton = document.getElementById('saveButton');
            
            inputs.forEach(input => {
                input.disabled = !checkbox.checked;
            });

            saveButton.disabled = !checkbox.checked;
        }

        // Validar solo números en el campo del teléfono
        function validaNumericos(event) {
            const charCode = event.which ? event.which : event.keyCode;
            if (charCode < 48 || charCode > 57) {
                return false;
            }
            return true;
        }
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
        
        @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ $errors->first() }}',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#ff4d4d'
            });
        </script>
        @endif


@stop