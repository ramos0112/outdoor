  <!-- resources/views/fechasdisponible/edit.blade.php--> <!-- Modal -->
  <div class="modal fade" id="edit{{ $fecha->id_fecha }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <!-- Formulario -->
          <form action="{{ route('fechas.update', $fecha->id_fecha) }}" method="POST"
              id="fechasdisponible-form-edit{{ $fecha->id_fecha }}">
              @csrf
              @method('PUT')
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editLabel{{ $fecha->id_fecha }}">Editar Fechas Disponibles</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <!-- Campos del formulario(SELECIONAR RUTA)-->
                      <div class="form-group">
                          <label for="id_ruta_edit{{ $fecha->id_fecha }}">Ruta:</label>
                          <select class="form-select" name="id_ruta" id="id_ruta_edit{{ $fecha->id_fecha }}" required>
                              <option value="" disabled>Seleccionar Ruta</option>
                              @foreach ($rutas as $ruta)
                                  <option value="{{ $ruta->id_ruta }}"
                                      {{ $fecha->id_ruta == $ruta->id_ruta ? 'selected' : '' }}>
                                      {{ $ruta->nombre_ruta }}
                                  </option>
                              @endforeach
                          </select>
                      </div>
                      <!-- Campos del formulario(FECHA) -->
                      <div class="form-group">
                          <label for="fecha_edit{{ $fecha->id_fecha }}">Fecha:</label>
                          <input type="date" class="form-control" name="fecha"
                              id="fecha_edit{{ $fecha->id_fecha }}" value="{{ $fecha->fecha }}" required>
                      </div>
                  </div>
                  <!-- Botones -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
