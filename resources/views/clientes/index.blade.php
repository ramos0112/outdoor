@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>Lista de Clientes</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                @can('clientes.crear')
                    <button class="btn btn-success ms-auto mb-2" data-bs-toggle="modal" data-bs-target="#modalCreate"><i class="fas fa-plus"></i>
                        Agregar</button>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center w-100" id="clientesTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Tipo Documento</th>
                            <th>N° Documento</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>País</th>
                            <th>Región</th>
                            <th>Ciudad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id_cliente }}</td>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->apellido }}</td>
                                <td>{{ $cliente->tipo_documento }}</td>
                                <td>{{ $cliente->numero_documento }}</td>
                                <td>{{ $cliente->fecha_nacimiento }}</td>
                                <td>{{ $cliente->email ?? 'Sin email' }}</td>
                                <td>{{ $cliente->telefono ?? 'NULL' }}</td>
                                <td>{{ $cliente->pais ?? 'NULL' }}</td>
                                <td>{{ $cliente->region ?? 'NULL' }}</td>
                                <td>{{ $cliente->ciudad ?? 'NULL' }}</td>
                                <td class="d-flex justify-content-center" style="gap: 5px;">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow"
                                        data-cliente='@json($cliente)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @can('clientes.editar')
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit" data-cliente='@json($cliente)'>
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    @endcan
                                    @can('clientes.eliminar')
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalDelete" data-cliente='@json($cliente)'>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                            <!-- Incluye los modales para este cliente -->
                            @include('clientes.show')
                            @include('clientes.edit')
                            @include('clientes.delete')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('clientes.create')
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#clientesTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
    <script>
        const modalShow = document.getElementById('modalShow');
        modalShow.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cliente = JSON.parse(button.getAttribute('data-cliente'));
            document.getElementById('verNombre').innerText = cliente.nombre;
            document.getElementById('verApellido').innerText = cliente.apellido;
            document.getElementById('verDocumento').innerText = cliente.tipo_documento + ' - ' + cliente
                .numero_documento;
            document.getElementById('verFechaNacimiento').innerText = cliente.fecha_nacimiento;
            document.getElementById('verEmail').innerText = cliente.email;
            document.getElementById('verTelefono').innerText = cliente.telefono;
            document.getElementById('verUbicacion').innerText =
                `${cliente.pais}, ${cliente.region}, ${cliente.ciudad}`;
        });

        const modalEdit = document.getElementById('modalEdit');
        modalEdit.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cliente = JSON.parse(button.getAttribute('data-cliente'));
            document.getElementById('editForm').action = `/clientes/${cliente.id_cliente}`;
            document.getElementById('editNombre').value = cliente.nombre;
            document.getElementById('editApellido').value = cliente.apellido;
            document.getElementById('editTipoDocumento').value = cliente.tipo_documento;
            document.getElementById('editNumeroDocumento').value = cliente.numero_documento;
            document.getElementById('editFechaNacimiento').value = cliente.fecha_nacimiento;
            document.getElementById('editEmail').value = cliente.email;
            document.getElementById('editTelefono').value = cliente.telefono;
            document.getElementById('editPais').value = cliente.pais;
            document.getElementById('editRegion').value = cliente.region;
            document.getElementById('editCiudad').value = cliente.ciudad;
        });

        const modalDelete = document.getElementById('modalDelete');
        modalDelete.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cliente = JSON.parse(button.getAttribute('data-cliente'));
            document.getElementById('deleteForm').action = `/clientes/${cliente.id_cliente}`;
            document.getElementById('deleteNombre').innerText = cliente.nombre + ' ' + cliente.apellido;
            document.getElementById('deleteDocumento').innerText = cliente.numero_documento;
            document.getElementById('deleteEmail').innerText = cliente.email;
        });
    </script>

@stop
