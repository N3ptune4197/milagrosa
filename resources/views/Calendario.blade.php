
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
            <div class="calendario mb-5 lg:px-4 py-4 w-[100%] lg:w-[85%] xl:w-[70%] lg:shadow-2xl box-border mx-auto bg-blue-500/5 border-t-8 border-indigo-500/100  rounded-lg overflow-hidden">
                <class class="titulo flex justify-between px-2 flex-center">

                    <p></p>

                    <h2 class="text-center font-montserrat font-bold text-gray-500 my-2">
                        <i class="fa-solid fa-calendar-days fa-md"></i> <span class="underline underline-offset-2 text-md lg:text-2xl">Calendario de Préstamos Pendientes</span>
                    </h2>
                    


                    <i class="fa-solid fa-thumbtack fa-lg flex items-center"></i>
                </class>
                <div id="calendar" class="px-1 lg:px-4"></div>
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




    <!--  CALENDARIOOOOOOOOOOOOOOOOOOOOOOOOOOOO           -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
        });
        calendar.render();
      });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        editable: true,
        locale: 'ES', // Para español
        events: function(fetchInfo, successCallback, failureCallback) {
            // Hacer la petición AJAX para obtener los eventos
            fetch('/calendarioActivo')
                .then(response => response.json())
                .then(data => {
                    // Formatear los eventos que recibimos
                    var eventos = data.map(function(evento) {
                        return {
                            id: evento.id,
                            title: evento.title,
                            start: evento.start,
                            end: evento.end,
                            description: evento.description,
                            fechaInicio: evento.fechaInicio,
                            recursocategoria: evento.recursocategoria,
                        };
                    });
                    successCallback(eventos); // Pasar los eventos a FullCalendar
                })
                
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        },






        eventClick: function(info) {
            // Mostrar detalles del evento cuando se hace clic en un evento
            Swal.fire({
                title: "Préstamo Pendiente! <i class='fa-solid fa-hourglass-half'></i><hr class='mt-1 w-[50%] mx-auto'>",
                html: "<b>Personal:</b> " + info.event.title + "</br>" + "<b>Recurso:</b> " + info.event.extendedProps.description + "</br>" + "<b>Fecha del Préstamo:</b> " + info.event.extendedProps.fechaInicio + "",
                imageUrl: "https://i.ibb.co/JyRTwNg/reloj-de-arena.png",
                imageWidth: 150,
                imageHeight: 120,
                imageAlt: "Custom image"
            });

        }
    });

    // Renderizar el calendario
    calendar.render();
});
    </script>

@stop