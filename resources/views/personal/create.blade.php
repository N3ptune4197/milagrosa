@extends('adminlte::page')

@section('tittle','')

@section('conten-header')
    <h1> Dashboard </h1>
@stop
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Personal</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('personals.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('personal.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('css')
        
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@stop


@section('js')
@stop

