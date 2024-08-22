@extends('layouts.app')

@section('template_title')
    {{ $prestamo->name ?? __('Show') . " " . __('Prestamo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Prestamo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('prestamos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Idpersonal:</strong>
                                    {{ $prestamo->idPersonal }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Prestamo:</strong>
                                    {{ $prestamo->fecha_prestamo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Devolucion:</strong>
                                    {{ $prestamo->fecha_devolucion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad Total:</strong>
                                    {{ $prestamo->cantidad_total }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observacion:</strong>
                                    {{ $prestamo->observacion }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
