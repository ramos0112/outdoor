  <!-- resources/views/fechasdisponible/show.blade.php -->
  <!-- Modal -->
  <div class="modal fade" id="show{{ $fecha->id_fecha }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="showLabel{{ $fecha->id_fecha }}">Ver Fechas Disponibles</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p><strong>ID:</strong> {{ $fecha->id_fecha }}</p>
                  <p><strong>Ruta:</strong> {{ $fecha->ruta->nombre_ruta }}</p>
                  <p><strong>Fecha:</strong> {{ $fecha->fecha }}</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
