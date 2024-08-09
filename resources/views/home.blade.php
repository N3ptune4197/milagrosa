<?php
    $conexion = new PDO("mysql:host=localhost;dbname=milagrosa","root","");
    $PDO = $conexion;
    $statement = $PDO->prepare("SELECT COUNT(*) FROM categorias");
    $statement->execute();
    $cantidadCategoria = $statement->fetch(PDO::FETCH_ASSOC);




?>





@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="row g-3">

            

            <a class="col-12 col-sm-6 col-lg-4 text-decoration-none" href="{{route('categorias.index')}}">
                <div class="rounded-3 border-start border-4 border-primary alert-primary shadow h-100">
                    <span class="bi bi-tags-fill fs-1 m-4 float-start"></span>
                    <div class="text-end p-4">
                        <span class="d-block text-dark">Categorias</span>
                        <span class="text-dark fs-3"> <?php echo $cantidadCategoria['COUNT(*)'] ?></span>
                    </div>
                </div>
            </a>


            
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="rounded-3 border-start border-4 border-danger alert-danger shadow h-100">
                    <span class="bi bi-people-fill fs-1 m-4 float-start"></span>
                    <div class="text-end p-4">
                        <span class="d-block text-dark">Profesores</span>
                        <span class="text-dark fs-3">Total 18.85</span>
                    </div>
                </div>
            </div>



            <div class="col-12 col-sm-6 col-lg-4">
                <div class="rounded-3 border-start border-4 border-warning alert-warning shadow h-100">
                    <span class="bi bi-box-fill fs-1 m-4 float-start"></span>
                    <div class="text-end p-4">
                        <span class="d-block text-dark">Recursos</span>
                        <span class="text-dark fs-3">Total 18.85</span>
                    </div>
                </div>
            </div>



            <div class="col-12 col-sm-6 col-lg-4">
                <div class="rounded-3 border-start border-4 border-success alert-success shadow h-100">
                    <span class="bi bi-clock-fill fs-1 m-4 float-start"></span>
                    <div class="text-end p-4">
                        <span class="d-block text-dark">Préstamos</span>
                        <span class="text-dark fs-3">Total 18.85</span>
                    </div>
                </div>
            </div>


        </div>
    </div>




    
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

@stop