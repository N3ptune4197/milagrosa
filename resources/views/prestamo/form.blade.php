<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_personal" class="form-label">{{ __('Personal') }}</label>
            <select name="idPersonal" class="form-control @error('idPersonal') is-invalid @enderror" id="id_personal">
                <div id="root"></div>
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
            <label for="cantidad_total" class="form-label">{{ __('Cantidad Total') }}</label>
            <input type="number" name="cantidad_total" class="form-control @error('cantidad_total') is-invalid @enderror" value="{{ old('cantidad_total', $prestamo?->cantidad_total) }}" id="cantidad_total" placeholder="Cantidad Total" readonly>
            {!! $errors->first('cantidad_total', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="observacion" class="form-label">{{ __('Observacion') }}</label>
            <textarea name="observacion" class="form-control @error('observacion') is-invalid @enderror" id="observacion" placeholder="Observacion">{{ old('observacion', $prestamo?->observacion) }}</textarea>
            {!! $errors->first('observacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Selección de Recursos -->
        <div class="form-group">
            <label for="recursos">Recursos</label>
            <div id="recursos-container">
                <div class="resource-item row mb-3">
                    <div class="col-md-6">
                        <select name="idRecurso[]" class="form-control" required>
                            <option value="">Seleccione un recurso</option>
                            @foreach($recursos as $recurso)
                                @if($recurso->estado == 1)
                                    <option value="{{ $recurso->id }}" data-cantidad="{{ $recurso->cantidad }}">{{ $recurso->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
        
                    <div class="col-md-4">
                        <label for="fecha_devolucion[]" class="form-label">Fecha de devolución</label>
                        <input type="date" name="fecha_devolucion[]" class="form-control" min="{{ now()->format('Y-m-d') }}" placeholder="Fecha de devolución" required>
                    </div>
        
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-resource" style="display: none;">&times;</button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-resource" class="btn btn-success btn-sm mt-2">
                <i class="fas fa-plus"></i> Añadir Recurso
            </button>
        </div>
            @error('idRecurso')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

<!-- Botón de enviar -->
<div class="col-md-12 mt20 mt-2">
    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
</div>

<!-- SCRIPTS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recursosContainer = document.getElementById('recursos-container');
        const cantidadTotalInput = document.getElementById('cantidad_total');
        const addResourceButton = document.getElementById('add-resource');
        const maxRecursos = {{ $recursos->count() }}; // Número total de recursos disponibles
        let recursosAgregados = recursosContainer.querySelectorAll('select').length; // Contar los recursos inicialmente mostrados

        // Mostrar el botón de añadir recurso si hay más recursos disponibles
        if (recursosAgregados < maxRecursos) {
            addResourceButton.style.display = 'inline-block';
        }

        // Función para actualizar la cantidad total
        function updateCantidadTotal() {
            let cantidadTotal = 0;

            // Obtener todos los campos de selección
            const selects = recursosContainer.querySelectorAll('select');

            selects.forEach(select => {
                // Obtener las opciones seleccionadas en cada campo
                const opcionesSeleccionadas = Array.from(select.selectedOptions);

                // Sumar la cantidad de cada recurso seleccionado
                opcionesSeleccionadas.forEach(opcion => {
                    cantidadTotal += parseInt(opcion.getAttribute('data-cantidad')) || 0;
                });
            });

            // Actualizar el campo de cantidad total
            cantidadTotalInput.value = cantidadTotal;
        }

        // Agregar evento para actualizar la cantidad total cuando cambian las opciones
        recursosContainer.addEventListener('change', updateCantidadTotal);

        // Función para agregar un nuevo campo de recursos
        addResourceButton.addEventListener('click', function() {
            if (recursosAgregados < maxRecursos) {
                const newField = document.createElement('div');
                newField.classList.add('resource-item', 'd-flex', 'align-items-center', 'mb-3');
                newField.innerHTML = `
                    <select name="idRecurso[]" class="form-control" required>
                        <option value="">Seleccione un recurso</option>
                        @foreach($recursos as $recurso)
                            @if($recurso->estado == 1)
                                <option value="{{ $recurso->id }}" data-cantidad="{{ $recurso->cantidad }}">{{ $recurso->nombre }}</option>
                            @endif
                        @endforeach
                    </select>

                    <div class="ms-2">
                        <label for="fecha_devolucion[]" class="form-label">Fecha de devolución</label>
                        <input type="date" name="fecha_devolucion[]" class="form-control" min="{{ now()->format('Y-m-d') }}" placeholder="Fecha de devolución">
                    </div>

                    <button type="button" class="btn btn-danger btn-sm ms-2 remove-resource">&times;</button>
                `;
                recursosContainer.appendChild(newField);

                recursosAgregados++;

                // Ocultar el botón si se ha alcanzado el número máximo de recursos
                if (recursosAgregados >= maxRecursos) {
                    addResourceButton.style.display = 'none';
                }

                // Añadir evento para eliminar el campo al hacer clic en la X
                newField.querySelector('.remove-resource').addEventListener('click', function () {
                    newField.remove();
                    recursosAgregados--;

                    // Mostrar el botón de añadir recurso si se elimina un recurso y el límite no ha sido alcanzado
                    if (recursosAgregados < maxRecursos) {
                        addResourceButton.style.display = 'inline-block';
                    }

                    updateCantidadTotal();
                });

                updateCantidadTotal();
            }
        });

        // Evento para eliminar un recurso (para los campos ya existentes)
        recursosContainer.querySelectorAll('.remove-resource').forEach(function (button) {
            button.addEventListener('click', function () {
                this.parentElement.remove();
                recursosAgregados--;

                // Mostrar el botón de añadir recurso si se elimina un recurso y el límite no ha sido alcanzado
                if (recursosAgregados < maxRecursos) {
                    addResourceButton.style.display = 'inline-block';
                }

                updateCantidadTotal();
            });
        });

        // Actualizar la cantidad total al cargar la página (si ya hay recursos seleccionados)
        updateCantidadTotal();
    });
</script>
</script>
    <!-- CSS de Bootstrap Multiselect -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/css/bootstrap-multiselect.min.css">

<!-- JS de Bootstrap Multiselect -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/js/bootstrap-multiselect.min.js"></script>
    
