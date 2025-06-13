    <!-- Modal -->
    <div class="modal fade" id="modalAsignarPermisos" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('permisos.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Asignar Permisos al Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($permisos as $grupo => $permisosGrupo)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>{{ ucfirst($grupo) }}</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-grupo" type="checkbox"
                                                    id="grupo_{{ $loop->index }}">
                                                <label class="form-check-label fw-bold"
                                                    for="grupo_{{ $loop->index }}">Seleccionar todo el grupo</label>
                                            </div>
                                            <div class="row">
                                                @foreach ($permisosGrupo as $permiso)
                                                    <div class="col-6 mb-2">
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input permiso-checkbox grupo-{{ $loop->parent->index }}"
                                                                type="checkbox" name="permissions[]"
                                                                value="{{ $permiso->name }}"
                                                                id="permiso_{{ $permiso->name }}">
                                                            <label class="form-check-label"
                                                                for="permiso_{{ $permiso->name }}">
                                                                {{ $permiso->description }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Permisos</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>