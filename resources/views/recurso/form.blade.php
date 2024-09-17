<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $recurso?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_categoria" class="form-label">{{ __('Categoría') }}</label>
            <select name="id_categoria" id="id_categoria" class="form-control @error('id_categoria') is-invalid @enderror">
                <option value="">{{ __('Seleccione una categoría') }}</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ old('id_categoria', $recurso?->id_categoria) == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('id_categoria', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        @if(request()->routeIs('recursos.edit'))
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror">
                <option value="1" {{ old('estado', $recurso?->estado) == 1 ? 'selected' : '' }}>{{ __('Disponible') }}</option>
                <option value="2" {{ old('estado', $recurso?->estado) == 2 ? 'selected' : '' }}>{{ __('Prestado') }}</option>
                <option value="3" {{ old('estado', $recurso?->estado) == 3 ? 'selected' : '' }}>{{ __('En mantenimiento') }}</option>
                <option value="4" {{ old('estado', $recurso?->estado) == 4 ? 'selected' : '' }}>{{ __('Dañado') }}</option>
            </select>
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        @else
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control" value="{{ $recurso->estadoDescripcion }}" id="estado" readonly>
        </div>
        @endif

        <div class="form-group mb-2">
            <label for="fecha_registro" class="form-label">{{ __('Fecha de Registro') }}</label>
            <input type="text" id="fecha_registro" class="form-control" value="{{ $recurso->fecha_registro ? $recurso->fecha_registro->format('d/m/Y') : '' }}" readonly>
            {!! $errors->first('fecha_registro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="nro_serie" class="form-label">{{ __('Nro Serie') }}</label>
            <input type="text" name="nro_serie" class="form-control @error('nro_serie') is-invalid @enderror" value="{{ old('nro_serie', $recurso?->nro_serie) }}" id="nro_serie" placeholder="Nro Serie">
            {!! $errors->first('nro_serie', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_marca" class="form-label">{{ __('Marca') }}</label>
            <select name="id_marca" id="id_marca" class="form-control @error('id_marca') is-invalid @enderror">
                <option value="">{{ __('Seleccione una marca') }}</option>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}" {{ old('id_marca', $recurso?->id_marca) == $marca->id ? 'selected' : '' }}>
                        {{ $marca->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('id_marca', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
