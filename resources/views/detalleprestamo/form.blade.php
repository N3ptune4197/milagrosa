<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="idprestamo" class="form-label">{{ __('Idprestamo') }}</label>
            <input type="text" name="idprestamo" class="form-control @error('idprestamo') is-invalid @enderror" value="{{ old('idprestamo', $detalleprestamo?->idprestamo) }}" id="idprestamo" placeholder="Idprestamo">
            {!! $errors->first('idprestamo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_recurso" class="form-label">{{ __('Id Recurso') }}</label>
            <input type="text" name="id_recurso" class="form-control @error('id_recurso') is-invalid @enderror" value="{{ old('id_recurso', $detalleprestamo?->id_recurso) }}" id="id_recurso" placeholder="Id Recurso">
            {!! $errors->first('id_recurso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>