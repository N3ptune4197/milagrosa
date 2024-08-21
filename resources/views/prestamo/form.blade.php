<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="id_personal" class="form-label">{{ __('Personal') }}</label>
            <select name="idPersonal" class="form-control @error('idPersonal') is-invalid @enderror" id="id_personal">
                <option value="">Seleccione la persona</option>
                @foreach ($personals as $personal)
                    <option value="{{ $personal->id }}" {{ old('idPersonal', $prestamo?->idPersonal) == $personal->id ? 'selected' : '' }}>
                        {{ $personal->nombres }} {{ $personal->a_paterno }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('idPersonal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="fecha_prestamo" class="form-label">{{ __('Fecha Prestamo') }}</label>
            <input type="text" name="fecha_prestamo" class="form-control" value="{{ now()->format('d/m/Y') }}" id="fecha_prestamo" readonly>
        </div>

        <div class="form-group mb-2 mb20">
            <label for="fecha_devolucion" class="form-label">{{ __('Fecha Devolución') }}</label>
            <input type="date" name="fecha_devolucion" class="form-control @error('fecha_devolucion') is-invalid @enderror"
                   value="{{ old('fecha_devolucion', $prestamo->fecha_devolucion ? $prestamo->fecha_devolucion->format('Y-m-d') : '') }}"
                   id="fecha_devolucion" placeholder="Fecha Devolución" min="{{ now()->format('Y-m-d') }}">
            {!! $errors->first('fecha_devolucion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        

        <div class="form-group mb-2 mb20">
            <label for="cantidad_total" class="form-label">{{ __('Cantidad Total') }}</label>
            <input type="number" name="cantidad_total" class="form-control @error('cantidad_total') is-invalid @enderror" value="{{ old('cantidad_total', $prestamo?->cantidad_total) }}" id="cantidad_total" placeholder="Cantidad Total">
            {!! $errors->first('cantidad_total', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="observacion" class="form-label">{{ __('Observacion') }}</label>
            <textarea name="observacion" class="form-control @error('observacion') is-invalid @enderror" id="observacion" placeholder="Observacion">{{ old('observacion', $prestamo?->observacion) }}</textarea>
            {!! $errors->first('observacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
