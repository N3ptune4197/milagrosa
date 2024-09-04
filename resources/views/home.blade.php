<?php
    $conexion = new PDO("mysql:host=localhost;dbname=milagrosa","root","");
    $PDO = $conexion;
    $statement = $PDO->prepare("SELECT COUNT(*) FROM categorias");
    $statement->execute();
    $cantidadCategoria = $statement->fetch(PDO::FETCH_ASSOC);

    
    $statement2 = $PDO->prepare("SELECT COUNT(*) FROM personals");
    $statement2->execute();
    $cantidadPersonal = $statement2->fetch(PDO::FETCH_ASSOC);



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

        <div class="section-one row g-3 bg-white px-2 pt-3 pb-5 mb-3 border rounded-xl">
            <h2 class="text-2xl ml-3 md:ml-14 lg:text-3xl font-montserrat font-semibold underline underline-offset-2 mb-3">Inicio Rápido</h2>
            

            <a class="block w-full sm:w-1/2 md:1/3 lg:w-1/3 no-underline" href="{{ route('categorias.index') }}">
                <div class="rounded-lg border-l-[5px] py-2 border-blue-500 bg-blue-100 shadow-lg h-full flex items-center">
                    <span class="bi bi-tags-fill text-4xl m-4 flex-shrink-0 text-blue-900"></span>
                    <div class="flex-1 text-right p-4">
                        <span class="block text-gray-800">Categorias</span>
                        <span class="text-gray-800 text-3xl font-bold"><?php echo $cantidadCategoria['COUNT(*)'] ?></span>
                    </div>
                </div>
            </a>
            

            
            <a class="block w-full sm:w-1/2 md:1/3 lg:w-1/3 no-underline" href="{{ route('personals.index') }}">
                <div class="rounded-lg border-l-[5px] py-2 border-red-500 bg-red-100 shadow-lg h-full flex items-center">
                    <span class="bi bi-people-fill text-4xl m-4 flex-shrink-0 text-red-900 "></span>
                    <div class="flex-1 text-right p-4">
                        <span class="block text-gray-800">Personal</span>
                        <span class="text-gray-800 text-3xl font-bold"><?php echo $cantidadPersonal['COUNT(*)'] ?></span>
                    </div>
                </div>
            </a>



            <div class="block w-full sm:w-1/2 md:1/3 lg:w-1/3 no-underline">
                <div class="rounded-lg border-l-[5px] py-2 border-amber-500 bg-amber-100 shadow-lg h-full flex items-center">
                    <span class="bi bi-box-fill text-4xl m-4 flex-shrink-0 text-amber-900"></span>
                    <div class="flex-1 text-right p-4">
                        <span class="block text-gray-800">Recursos</span>
                        <span class="text-gray-800 text-3xl font-bold">Total 18.85</span>
                    </div>
                </div>
            </div>



            <div class="block w-full sm:w-1/2 md:1/3 lg:w-1/3 no-underline">
                <div class="rounded-lg border-l-[5px] py-2 border-green-500 bg-green-100 shadow-lg h-full flex items-center">
                    <span class="bi bi-clock-fill text-4xl m-4 flex-shrink-0 text-green-900"></span>
                    <div class="flex-1 text-right p-4">
                        <span class="block text-gray-800">Préstamos</span>
                        <span class="text-gray-800 text-3xl font-bold">Total 18.85</span>
                    </div>
                </div>
            </div>



            <div class="block w-full sm:w-1/2 md:1/3 lg:w-1/3 no-underline">
                <div class="rounded-lg border-l-[5px] py-2 border-amber-500 bg-amber-100 shadow-lg h-full flex items-center">
                    <span class="bi bi-box-fill text-4xl m-4 flex-shrink-0 text-amber-900"></span>
                    <div class="flex-1 text-right p-4">
                        <span class="block text-gray-800">Cantidad total de Préstamos</span>
                        <span class="text-gray-800 text-3xl font-bold">Total 18.85</span>
                    </div>
                </div>
            </div>




        </div>



        <div class="section-two">

            <div id="root" class="root"></div>
        </div>




        {{-- <div class="container mx-auto">
            <div class="relative">
                <input id="searchInput" type="text" placeholder="Search..." 
                       class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <ul id="suggestions" class="absolute w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto z-10 hidden">
                    <!-- Suggestions will be inserted here -->
                </ul>
            </div>
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('searchInput');
                const suggestionsBox = document.getElementById('suggestions');
                const options = ['Apple', 'Banana', 'Cherry', 'Date', 'Fig', 'Grape', 'Honeydew'];
    
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase();
                    suggestionsBox.innerHTML = '';
    
                    if (query) {
                        const filteredOptions = options.filter(option => 
                            option.toLowerCase().includes(query)
                        );
    
                        if (filteredOptions.length > 0) {
                            suggestionsBox.classList.remove('hidden');
                            filteredOptions.forEach(option => {
                                const li = document.createElement('li');
                                li.textContent = option;
                                li.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
                                li.addEventListener('click', () => {
                                    searchInput.value = option;
                                    suggestionsBox.classList.add('hidden');
                                });
                                suggestionsBox.appendChild(li);
                            });
                        } else {
                            suggestionsBox.classList.add('hidden');
                        }
                    } else {
                        suggestionsBox.classList.add('hidden');
                    }
                });
    
                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.classList.add('hidden');
                    }
                });
            });
        </script> --}}
        

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

@stop