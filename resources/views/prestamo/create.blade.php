@extends('adminlte::page')

@section('template_title')
    {{ __('Create') }} Prestamo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Prestamo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('prestamos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('prestamo.form')

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