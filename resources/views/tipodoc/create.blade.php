@extends('adminlte::page')

@section('tittle', 'Dashboard')

@section('content-header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Tipodoc</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipodocs.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipodoc.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js')
 <script>console.console.log("Hi");
 
@endsection
