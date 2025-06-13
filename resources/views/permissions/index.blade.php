@extends('adminlte::page')

@section('title', 'Asignar Permisos a Roles')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Asignar Permisos a Roles</h1>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tablaRoles" class="table table-bordered table-striped w-100 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalAsignarPermisos"
                                            onclick='setRoleId({{ $role->id }}, @json($role->permissions->pluck('name')) )'>
                                            <i class="fas fa-key"></i> Asignar Permisos
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('permissions.modal')
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop



@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializar DataTable
            $('#tablaRoles').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true,
                order: [[0, 'asc']]
            });

            // Manejo del check de grupo
            $('.check-grupo').on('change', function () {
                const grupoIndex = $(this).attr('id').replace('grupo_', '');
                const checkboxes = $('.grupo-' + grupoIndex);
                checkboxes.prop('checked', $(this).is(':checked'));
            });
        });

        function setRoleId(roleId, permisosJson) {
            $('#role_id').val(roleId);

            // Limpiar todos los checks
            $('.permiso-checkbox').prop('checked', false);

            let permisos = permisosJson;

            if (typeof permisos === 'string') {
                try {
                    permisos = JSON.parse(permisos);
                } catch (e) {
                    permisos = [];
                }
            }

            permisos.forEach(function (permiso) {
                // Escapa puntos en el ID
                const safeId = permiso.replace(/\./g, '\\.');
                $('#permiso_' + safeId).prop('checked', true);
            });

            // Activar o desactivar check-grupo según los permisos marcados
            $('.check-grupo').each(function (index) {
                const grupoChecks = $('.grupo-' + index);
                const total = grupoChecks.length;
                const marcados = grupoChecks.filter(':checked').length;
                $(this).prop('checked', total === marcados);
            });
        }
    </script>
@stop
