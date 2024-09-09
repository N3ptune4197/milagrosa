<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_detalle_prestamo" class="form-label">{{ __('Id Detalle Prestamo') }}</label>
            <input type="text" name="id_detalle_prestamo" class="form-control @error('id_detalle_prestamo') is-invalid @enderror" value="{{ old('id_detalle_prestamo', $historial?->id_detalle_prestamo) }}" id="id_detalle_prestamo" placeholder="Id Detalle Prestamo">
            {!! $errors->first('id_detalle_prestamo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>