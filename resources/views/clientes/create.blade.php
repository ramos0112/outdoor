<!-- Modal Crear Cliente -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('clientes.store') }}" 
              id="formCreate" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body row g-3">
                    <!-- Formulario de cliente -->
                    <div class="col-md-6">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apellido">Apellido</label>
                        <input type="text" name="apellido" class="form-control" id="apellido" value="{{ old('apellido') }}" required>
                        @error('apellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tipo_documento">Tipo de Documento</label>
                        <select name="tipo_documento" class="form-control" id="tipo_documento" required>
                            <option value="DNI" {{ old('tipo_documento') == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="Pasaporte" {{ old('tipo_documento') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="Otro" {{ old('tipo_documento') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('tipo_documento')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="numero_documento">N° Documento</label>
                        <input type="text" name="numero_documento" class="form-control" id="numero_documento" value="{{ old('numero_documento') }}" required>
                        @error('numero_documento')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                        @error('fecha_nacimiento')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="telefono">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" id="telefono" value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="pais">País</label>
                        <input type="text" name="pais" id="pais" class="form-control" value="{{ old('pais') }}">
                        @error('pais')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="region">Región</label>
                        <input type="text" name="region" id="region" class="form-control" value="{{ old('region') }}">
                        @error('region')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="ciudad">Ciudad</label>
                        <input type="text" name="ciudad" id="ciudad" class="form-control" value="{{ old('ciudad') }}">
                        @error('ciudad')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Agregar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Script para mantener el modal abierto si hay errores -->
<!-- Script para mantener el modal abierto si hay errores -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Verificar si existen errores de validación
        @if ($errors->any())
            // Crear el modal y abrirlo
            var modal = new bootstrap.Modal(document.getElementById('modalCreate'));
            modal.show();
        @endif
    });
</script>