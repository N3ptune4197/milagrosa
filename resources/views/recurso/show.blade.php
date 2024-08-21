@extends('layouts.app')

@section('template_title')
    {{ $recurso->name ?? __('Show') . " " . __('Recurso') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Recurso</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('recursos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $recurso->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Categoria:</strong>
                                    {{ $recurso->id_categoria }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $recurso->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Registro:</strong>
                                    {{ $recurso->fecha_registro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Modelo:</strong>
                                    {{ $recurso->modelo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nro Serie:</strong>
                                    {{ $recurso->nro_serie }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Marca:</strong>
                                    {{ $recurso->id_marca }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
