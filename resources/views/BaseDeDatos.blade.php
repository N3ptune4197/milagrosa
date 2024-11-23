
@extends('adminlte::page')
@include('partials.navbar')
@section('title', 'Calendario')

@section('content_header')
    <div class="flex align-middle items-center ">

        <span class="bi bi-calendar3 text-2xl "></span>


        <h1 class="ml-2 font-semibold">Copia de Seguridad de la Base de Datos</h1>
    </div>

@stop
@section('content')


@include('partials.sidebar')

    <div class="mt-3 w-[100%]">


        <div class="section-one flex flex-col lg:flex-row g-3 px-3 pt-5 pb-5 mb-3 border rounded-2xl w-[100%] bg-white ">

            <div class="content w-full flex justify-between px-3 py-4 rounded border">

                <div class="exportar w-[48%] flex flex-col">
                    <span class="text-center font-montserrat font-semibold text-xl mb-4">Exportar Base de Datos</span>
                    <span class="text-sm mb-2 pr-3">Crea una copia de seguridad de tu base de datos para restaurar la información en caso de necesidad.</span>
                    <span class="text-sm border-l-4 pl-2 py-1 rounded bg-orange-100 border-orange-300" ><b class="text-orange-600 text-lg">¡Cuidado!</b> Realiza una revisión previa de la base de datos para evitar errores en la exportación.</span>

                    <div class="button mt-3 mb-3">
                        <button class="font-montserrat font-semibold rounded-lg px-7 py-3 bg-[#1d6700] text-[#ffffff] shadow-xl" onclick="window.location.href='{{ route('exportarBD') }}'"> <i class="fa-solid fa-file-arrow-down fa-lg mr-2 fa-shake"></i> Exportar Base de Datos</button>
                    </div>
                    <span class="text-sm border-l-4 pl-2 py-1 rounded bg-red-100 border-red-600 " ><b class="text-red-600 text-lg">No modifiques el archivo.</b> Cualquier cambio en el archivo de exportación puede causar errores durante la importación.</span>
                </div>
    
                <div class="importar w-[51%] flex flex-col pl-3 border-l-4 border-gray-400">
                    <span class="text-center font-montserrat font-semibold text-xl mb-4">Importar Base de Datos</span>
                    <span class="text-sm mb-2 pr-3">Recupera una copia de seguridad de tu base de datos para volver a un estado anterior.</span>
                    <span class="text-sm border-l-4 pl-2 py-1 rounded bg-orange-100 border-orange-300" ><b class="text-orange-600 text-lg">¡Cuidado!</b> Verifica que el archivo seleccionado corresponda a la base de datos que deseas restaurar.</span>

                    <form action="{{ route('importarBD') }}" method="POST" enctype="multipart/form-data" class="mt-4 ">
                        @csrf

                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Subir Archivo</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file" name="archivo_mysql" accept=".sql" required>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Extensión del archivo:  .sql</p>

                        <button type="submit" class="font-montserrat mt-2 px-7 py-3 bg-red-600 rounded-lg text-white font-semibold shadow-lg"><i class="fa-solid fa-file-arrow-up fa-lg mr-2"></i> Importar Base de Datos</button>
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


    <!--                    DAISYUI             -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
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



@stop