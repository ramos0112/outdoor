    <!-- Modal para asignar rol -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignRoleModalLabel">Asignar Rol a Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignRoleForm" method="POST" action="{{ route('roles.update', ':user_id') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id">

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Seleccionar Rol</label>
                            <select class="form-select" name="role_id" id="role_id" required>
                                <option value="" disabled selected>Selecciona un rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Asignar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>