@extends('layouts.app')

@section('template_title')
    {{ $personal->name ?? __('Show') . " " . __('Personal') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Personal</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('personals.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombres:</strong>
                                    {{ $personal->nombres }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>A Paterno:</strong>
                                    {{ $personal->a_paterno }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>A Materno:</strong>
                                    {{ $personal->a_materno }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $personal->telefono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Tipodocs:</strong>
                                    {{ $personal->id_tipodocs }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nro Documento:</strong>
                                    {{ $personal->nro_documento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cargo:</strong>
                                    {{ $personal->cargo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
