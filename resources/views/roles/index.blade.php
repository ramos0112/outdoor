@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="mb-4">Asignar Roles a Usuarios</h1>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    @can('roles.crear')
                        <button class="btn btn-success ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table id="userTable" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol Actual</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                    <td>
                                        @can('roles.asignar')
                                            <!-- Botón para abrir el modal (Solo el icono en pantallas pequeñas) -->
                                            <button class="btn btn-info btn-sm d-none d-sm-inline" data-bs-toggle="modal"
                                                data-bs-target="#assignRoleModal" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}">
                                                <i class="fas fa-user-shield"></i> Asignar Rol
                                            </button>
                                            <button class="btn btn-info btn-sm d-inline d-sm-none" data-bs-toggle="modal"
                                                data-bs-target="#assignRoleModal" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}">
                                                <i class="fas fa-user-shield"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('roles.asignar')
    @include('roles.create')
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#userTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true
            });

            // Mostrar modal de asignar rol
            var assignRoleModal = document.getElementById('assignRoleModal');
            assignRoleModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-user-id');
                var userName = button.getAttribute('data-user-name');
                var userIdInput = document.getElementById('user_id');
                userIdInput.value = userId;
                var formAction = document.getElementById('assignRoleForm').action;
                document.getElementById('assignRoleForm').action = formAction.replace(':user_id', userId);
            });
        });
    </script>
@stop
