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


            <div class="flex flex-col g-3 pl-3 mb-7 pt-3 lg:w-[40%] xl:w-[55%]">
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

            <div class="echarts w-[100%] lg:w-[60%] xl:w-[42%] border-t-4 pl-2 pt-4 lg:border-t-0 lg:border-l-4 lg:pt-3 ">
                <div class="graficos w-[100%] mb-3">
                    <div id="barras1" class="w-[100%] min-h-[430px] items-start"></div>
                </div>
            </div>

        </div>







        


        <H3>hola</H3>
        <input id="clockPicker" type="datetime-local" class="form-control" min="{{ now()->format('Y-m-d\TH:i') }} " />

    </div>







    
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

    <script src="https://unpkg.com/react/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/prop-types/prop-types.min.js"></script>
    <script src="https://unpkg.com/recharts/umd/Recharts.js"></script>






    <!--    ECHARTS  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.1-rc.1/echarts.min.js" integrity="sha512-RaatU6zYCjTIyGwrBVsUzvbkpZq/ECxa2oYoQ+16qhQDCX9xQUEAsm437n/6TNTes94flHU4cr+ur99FKM5Vog==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!--    mI sCRIPT PARA LOS GRAFICOS    -->
    @vite('resources/js/echartss.js')
    

    <script>
        $('#clockPicker').timepicker({
      'timeFormat':'H:i'
  });
    </script>
    
    @stop