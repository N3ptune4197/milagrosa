<div class="row padding-1 p-1">
    <div class="col-md-12">

        
        <div class="form-group mb-2 mb20">
            <label for="tipodoc" class="form-label">{{ __('Tipo de Documento') }}</label>

            <select name="tipodoc" id="id_tipodoc" class="form-control @error('tipodoc') is-invalid @enderror">
                <option value="Documento Nacional de Identidad">
                    Documento Nacional de Identidad
                </option>
                <option value="Documento de Extranjeria">
                    Carnet de Extranjeria
                </option>
            </select>
            {!! $errors->first('tipodoc', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>




        <div class="form-group mb-2 mb20">
            <label for="nro_documento" class="form-label">{{ __('Ingresar Número de documento') }}</label>

            <div class="input-group">
                <input type="text" name="nro_documento" class="form-control @error('nro_documento') is-invalid @enderror" value="{{ old('nro_documento', $personal?->nro_documento) }}" id="nro_documento" placeholder="Nro Documento" onkeypress='return validaNumericos(event)'>
                {!! $errors->first('nro_documento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                
                <button class="btn btn-outline-secondary" type="button" id="buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="nombres" class="form-label">{{ __('Nombres') }}</label>
            <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres', $personal?->nombres) }}" id="nombres" placeholder="Nombres" oninput="this.value = this.value.toUpperCase()" pattern="[A-Z]{1, 15}">
            {!! $errors->first('nombres', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="a_paterno" class="form-label">{{ __('Apellido Paterno') }}</label>
            <input type="text" name="a_paterno" class="form-control @error('a_paterno') is-invalid @enderror" value="{{ old('a_paterno', $personal?->a_paterno) }}" id="a_paterno" placeholder="A Paterno" oninput="this.value = this.value.toUpperCase()" pattern="[A-Z]{1, 15}">
            {!! $errors->first('a_paterno', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="a_materno" class="form-label">{{ __('A Materno') }}</label>
            <input type="text" name="a_materno" class="form-control @error('a_materno') is-invalid @enderror" value="{{ old('a_materno', $personal?->a_materno) }}" id="a_materno" placeholder="A Materno" oninput="this.value = this.value.toUpperCase()" pattern="[A-Z]{1, 15}">
            {!! $errors->first('a_materno', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Telefono') }}</label>
            <input type="text" onkeypress='return validaNumericos(event)' maxlength="9" minlength="9" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $personal?->telefono) }}" id="telefono" placeholder="Telefono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="cargo" class="form-label">{{ __('Cargo') }}</label>
            <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" value="{{ old('cargo', $personal?->cargo) }}" id="cargo" placeholder="Cargo">
            {!! $errors->first('cargo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function validaNumericos(event) {
        return event.charCode >= 48 && event.charCode <= 57;
    }

    $(document).ready(function() {
        $("#id_tipodoc").change(function() {
            var selectedValue = $(this).val();

            if (selectedValue === "Documento Nacional de Identidad") { // ID para DNI
                $("#nro_documento").attr("maxlength", 8);
                $("#nro_documento").attr("minlength", 8);
                $("#nro_documento").attr("placeholder", "Ingresar DNI");
                $("#nro_documento").attr("readonly", false); // Limpiar la longitud máxima si no está seleccionado


            } else if (selectedValue === "Documento de Extranjeria") { // ID para extranjero
                $("#nro_documento").attr("maxlength", 9);
                $("#nro_documento").attr("minlength", 9);
                $("#nro_documento").attr("placeholder", "Ingresar Número de Extranjero");
                $("#nro_documento").attr("readonly", false); // Limpiar la longitud máxima si no está seleccionado
            } else {
                $("#nro_documento").attr("readonly", true); // Limpiar la longitud máxima si no está seleccionado
                $("#nro_documento").attr("placeholder", "Seleccionar Tipo de Documento");
            }
        });

        $("#buscar").click(function() {
            var tipoDoc = $("#id_tipodoc").val();
            var documento = $("#nro_documento").val();

            if (tipoDoc === "Documento Nacional de Identidad") { // DNI
                if (documento.length === 8) {
                    buscarDni(documento);
                } else {
                    alert("El DNI debe tener 8 dígitos.");
                }
            } else if (tipoDoc === "Documento de Extranjeria") { // Extranjero
                if (documento.length === 9) {
                    buscarExtranjero(documento);
                } else {
                    alert("El número de extranjero debe tener 9 dígitos.");
                }
            } else {
                alert("Seleccione un tipo de documento válido.");
            }
        });

        function buscarDni(dni) {
            $.ajax({
                url: `/buscar-dni/${dni}`, // Ruta que apunta a tu controlador
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $("#nombres").val(response.nombres).prop('readonly', true);
                        $("#a_paterno").val(response.apellidoPaterno).prop('readonly', true);
                        $("#a_materno").val(response.apellidoMaterno).prop('readonly', true);

                    } else {
                        alert("No se encontraron datos para el DNI ingresado.");
                    }
                },
                error: function() {
                    alert("Hubo un error al realizar la solicitud.");
                }
            });
        }

        function buscarExtranjero(cee) {
            $.ajax({
                url: `https://api.factiliza.com/pe/v1/cee/info/${cee}`,
                method: 'GET',
                headers: {
                    "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI3MzciLCJuYW1lIjoiQ2FybG9zIENoZXJvIE1lbmRvemEiLCJlbWFpbCI6ImNhcmxvc2NoZXJvMTM0QGdtYWlsLmNvbSIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.v4e3xsg6OEhF2L9NAELlydgMnHONlnKlejh7IPzz9nA"
                },
                success: function(response) {
                    if (response.status === 200) {
                        $("#nombres").val(response.data.nombres).prop('readonly', true);
                        $("#a_paterno").val(response.data.apellido_paterno).prop('readonly', true);
                        $("#a_materno").val(response.data.apellido_materno).prop('readonly', true);
                    } else {
                        alert("Número de extranjero no válido.");
                        $("#nombres").val('').prop('readonly', false);
                        $("#a_paterno").val('').prop('readonly', false);
                        $("#a_materno").val('').prop('readonly', false);
                    }
                },
                error: function() {
                    alert("Error al buscar número de extranjero.");
                }
            });
        }

        // Asegúrate de que la longitud máxima y el placeholder se establezcan correctamente al cargar la página
        $("#id_tipodoc").trigger("change");
    });
</script>


<script>
    $("#buscar").click(function() {
        let dni = $("#nro_documento").val();

        if (dni.length === 8) {
            $.ajax({
                url: `/buscar-dni/${dni}`, // Ruta que apunta a tu controlador
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $("#nombres").val(response.nombres);
                        $("#a_paterno").val(response.apellidoPaterno);
                        $("#a_materno").val(response.apellidoMaterno);
                    } else {
                        alert("No se encontraron datos para el DNI ingresado.");
                    }
                },
                error: function() {
                    alert("Hubo un error al realizar la solicitud.");
                }
            });
        }
    });
</script>
 