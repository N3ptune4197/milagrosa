<?php
    $conexion = new PDO("mysql:host=localhost;dbname=milagrosa","root","");
    $PDO = $conexion;


    $statement = $PDO->prepare("SELECT COUNT(*) FROM categorias");
    $statement->execute();
    $cantidadCategoria = $statement->fetch(PDO::FETCH_ASSOC);

    
    $statement2 = $PDO->prepare("SELECT COUNT(*) FROM personals");
    $statement2->execute();
    $cantidadPersonal = $statement2->fetch(PDO::FETCH_ASSOC);

    
    $statement3 = $PDO->prepare("SELECT COUNT(*) FROM recursos");
    $statement3->execute();
    $cantidadRecursos = $statement3->fetch(PDO::FETCH_ASSOC);

    
    $statement4 = $PDO->prepare("SELECT COUNT(*) FROM detalleprestamos AS dp INNER JOIN prestamos AS p ON p.id = dp.idprestamo WHERE p.estado = 'activo'");

    $statement4->execute();
    $cantidadPrestamos = $statement4->fetch(PDO::FETCH_ASSOC);

?>




@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="flex align-middle items-center ">

        <span class="bi bi-grid-fill text-2xl "></span>


        <h1 class="ml-2 font-semibold">Dashboard</h1>
    </div>
@stop

@section('content')
    <div class="mt-3">

        <div class="section-one flex flex-col lg:flex-row g-3 bg-white px-2 pt-3 pb-5 mb-3 border rounded-xl gap-2">


            <div class="flex flex-col g-3 pl-3 mb-7 pt-3 lg:w-[45%] xl:w-[55%]">
                <div class="titulo mb-2">
                    <h2 class="text-2xl ml-3 md:ml-14 lg:text-2xl font-montserrat font-bold mb-3 text-center text-gray-600">Inicio Rápido</h2>
                </div>

                <div class="contenedor-pl flex flex-row flex-wrap gap-4 md:gap-1 lg:gap-3 w-[100%]">

                    <a class="block w-full sm:w-[45%] md:1/2 lg:w-[95%] xl:w-[47%] no-underline" href="{{ route('categorias.index') }}">
                        <div class="rounded-lg border-l-[5px] py-1 border-blue-500 bg-blue-100 shadow-lg h-full flex items-center">
                            <span class="bi bi-tags-fill text-4xl m-3 flex-shrink-0 text-blue-900"></span>
                            <div class="flex-1 text-right p-3">
                                <span class="block text-gray-800">Categorias</span>
                                <span class="text-gray-800 text-3xl font-bold"><?php echo $cantidadCategoria['COUNT(*)'] ?></span>
                            </div>
                        </div>
                    </a>
                    
        
                    
                    <a class="block w-full sm:w-[45%] md:1/2 lg:w-[95%] xl:w-[47%] no-underline" href="{{ route('personals.index') }}">
                        <div class="rounded-lg border-l-[5px] py-1 border-red-500 bg-red-100 shadow-lg h-full flex items-center">
                            <span class="bi bi-people-fill text-4xl m-3 flex-shrink-0 text-red-900 "></span>
                            <div class="flex-1 text-right p-3">
                                <span class="block text-gray-800">Personal</span>
                                <span class="text-gray-800 text-3xl font-bold"><?php echo $cantidadPersonal['COUNT(*)'] ?></span>
                            </div>
                        </div>
                    </a>
        
        
        
                    <a href="{{ route('recursos.index') }}" class="block w-full sm:w-[45%] md:1/2 lg:w-[95%] xl:w-[47%] no-underline">
                        <div class="rounded-lg border-l-[5px] py-1 border-amber-500 bg-amber-100 shadow-lg h-full flex items-center">
                            <span class="bi bi-box-fill text-4xl m-3 flex-shrink-0 text-amber-900"></span>
                            <div class="flex-1 text-right p-3">
                                <span class="block text-gray-800">Recursos</span>
                                <span class="text-gray-800 text-3xl font-bold"><?php echo $cantidadRecursos['COUNT(*)'] ?></span>
                            </div>
                        </div>
                    </a>
        
        
        
                    <a href="{{ route('prestamos.index') }}" class="block w-full sm:w-[45%] md:1/2 lg:w-[95%] xl:w-[47%] no-underline">
                        <div class="rounded-lg border-l-[5px] py-1 border-green-500 bg-green-100 shadow-lg h-full flex items-center">
                            <span class="bi bi-clock-fill text-4xl m-3 flex-shrink-0 text-green-900"></span>
                            <div class="flex-1 text-right p-3">
                                <span class="block text-gray-800">Préstamos Activos</span>
                                <span class="text-gray-800 text-3xl font-bold"><?php echo $cantidadPrestamos['COUNT(*)'] ?></span>
                            </div>
                        </div>
                    </a>
        
        
        
                </div>

                

                <h2 class="text-2xl mt-5 font-montserrat font-bold text-center text-gray-500 mb-3">Préstamos Totales</h2>
                <div id="barras2" class="w-[100%] min-h-[430px] items-start "></div>

    
            </div>

            <div class="echarts w-[100%] lg:w-[55%] xl:w-[42%] border-t-4 pl-2 pt-4 lg:border-t-0 lg:border-l-4 lg:pt-3 ">
                

                <div class="graficos w-[100%] mb-3">
                    <div id="barras1" class="w-[100%] min-h-[430px] items-start"></div>
                </div>
            </div>

        </div>





        <div class="calendario mb-5">
            <div id="calendar"></div>
        </div>


    </div>
@stop


@section('content_top_nav_right')
<!-- Dropdown de notificaciones -->
<li class="nav-item dropdown">
    <div class="relative">
        <a class="nav-link cursor-pointer" id="notificationDropdown">
            <i class="far fa-bell"></i>
            <span class="absolute top-0 right-0 block h-5 w-5 rounded-full bg-yellow-400 text-white text-center text-xs">
                {{ $totalNotificaciones }} 
            </span>
        </a>
        <div class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-50" id="notificationMenu">
            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                {{ $totalNotificaciones }} Notificaciones
            </div>
            <div class="overflow-y-auto max-h-64">
                <!-- Notificaciones de hoy -->
                @foreach ($notificacionesHoy as $notificacion)
                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id]) }}" class="block px-4 py-2 text-gray-700 break-words">
                        <div class="text-sm">
                            {{ $notificacion->a_paterno }} debe devolver el recurso 
                            @if(isset($notificacion->minutos_atraso))
                                (Atraso de {{ $notificacion->minutos_atraso }} minutos)
                            @endif
                            @if(isset($notificacion->horas_restantes))
                                (Faltan {{ $notificacion->horas_restantes }} horas y {{ $notificacion->minutos_restantes }} minutos)
                            @endif
                            @if(isset($notificacion->dias_restantes))
                                (Faltan {{ $notificacion->dias_restantes }} días)
                            @endif
                        </div>
                    </a>
                @endforeach

                <!-- Notificaciones atrasadas -->
                @foreach ($notificacionesAtrasadas as $notificacion)
                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id_recurso]) }}" class="block px-4 py-2 text-gray-700 break-words">
                        <div class="text-sm">
                            {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}).
                        </div>
                        <span class="text-xs text-red-500 float-right">
                            Se encuentra atrasado por {{ $notificacion->dias_atraso }} días.
                        </span>
                    </a>
                @endforeach
            </div>
            <div class="px-4 py-2 border-t">
                <button class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="openModal()">Ver todas las notificaciones</button>
            </div>
        </div>
    </div>
</li>

<!-- Modal para ver todas las notificaciones -->
<div class="fixed inset-0 hidden bg-gray-800 bg-opacity-75 flex items-center justify-center z-50" id="allNotificationsModal">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
        <div class="flex justify-between items-center p-4 border-b">
            <h5 class="text-lg font-semibold">Todas las Notificaciones</h5>
            <button class="text-gray-600 hover:text-gray-900" onclick="closeModal()">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto max-h-96">
            <ul class="space-y-4">
                <!-- Título para Notificaciones de Hoy -->
                @if(count($notificacionesHoy))
                    <h6 class="text-md font-semibold text-gray-700 mb-2">Notificaciones de Hoy</h6>
                    @foreach ($notificacionesHoy as $notificacion)
                        <a href="#" class="block px-4 py-2 text-gray-700 break-words">
                            <div class="text-sm">
                                {{ $notificacion->a_paterno }} debe devolver el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}) hoy.
                            </div>
                            <span class="text-xs text-gray-500 float-right">
                                @if (isset($notificacion->minutos_atraso))
                                    Debía devolver hace {{ $notificacion->minutos_atraso }} minutos.
                                @else
                                    Faltan {{ $notificacion->horas_restantes }} horas y {{ $notificacion->minutos_restantes }} minutos.
                                @endif
                            </span>
                        </a>
                    @endforeach
                @else
                    <li class="bg-gray-100 p-3 rounded-lg">No hay notificaciones para hoy.</li>
                @endif

                <!-- Título para Notificaciones Atrasadas -->
                @if(count($notificacionesAtrasadas))
                    <h6 class="text-md font-semibold text-gray-700 mb-2 mt-4">Notificaciones Atrasadas</h6>
                    @foreach ($notificacionesAtrasadas as $notificacion)
                        <a href="#" class="block px-4 py-2 text-gray-700 break-words">
                            <div class="text-sm">
                                {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}).
                            </div>
                            <span class="text-xs text-red-500 float-right">
                                Atrasado por {{ $notificacion->dias_atraso }} días.
                            </span>
                        </a>
                    @endforeach
                @else
                    <li class="bg-gray-100 p-3 rounded-lg">No hay notificaciones atrasadas.</li>
                @endif
            </ul>
        </div>
        <div class="p-4 border-t text-right">
            <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal()">Cerrar</button>
        </div>
    </div>
</div>



<script>
function mostrarResumen(mensaje) {
    Swal.fire({
        title: 'Resumen de Notificación',
        text: mensaje,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}
</script>


<!-- Script para el comportamiento del modal -->
<script>
// Abre o cierra el menú de notificaciones
document.getElementById('notificationDropdown').addEventListener('click', function() {
    const menu = document.getElementById('notificationMenu');
    menu.classList.toggle('hidden');
});

// Abre el modal de notificaciones
function openModal() {
    document.getElementById('allNotificationsModal').classList.remove('hidden');
    document.getElementById('notificationMenu').classList.add('hidden'); // Cierra el menú al abrir el modal
}

// Cierra el modal de notificaciones
function closeModal() {
    document.getElementById('allNotificationsModal').classList.add('hidden');
}

// Cerrar el menú de notificaciones si se hace clic fuera de él
window.addEventListener('click', function(e) {
    const menu = document.getElementById('notificationMenu');
    if (!document.getElementById('notificationDropdown').contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>


<script>
   function deleteNotification(notificationId) {
    $.ajax({
        url: `/notificaciones/${notificationId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Asegúrate de que la meta etiqueta del CSRF esté en tu layout
        },
        success: function(response) {
            if (response.success) {
                // Eliminamos el elemento visualmente
                $(`#notification-${notificationId}`).remove();
            } else {
                console.error(response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

</script>

@stop





@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    


    @viteReactRefresh
    @vite('resources/js/main.jsx')


    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

@stop






@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/react/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/prop-types/prop-types.min.js"></script>
    <script src="https://unpkg.com/recharts/umd/Recharts.js"></script>


<!--    // Calendario           -->
    <script src=" https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js "></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>




    <!--    ECHARTS  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.1-rc.1/echarts.min.js" integrity="sha512-RaatU6zYCjTIyGwrBVsUzvbkpZq/ECxa2oYoQ+16qhQDCX9xQUEAsm437n/6TNTes94flHU4cr+ur99FKM5Vog==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!--    mI sCRIPT PARA LOS GRAFICOS    -->
    @vite('resources/js/echartss.js')
    

    <script>
        $('#clockPicker').timepicker({
            'timeFormat':'H:i'
        });
    </script>
    


    <!--  CALENDARIOOOOOOOOOOOOOOOOOOOOOOOOOOOO           -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>

@stop