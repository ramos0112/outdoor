@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Rutas</h1>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create">
                    Agregar Ruta
                </button>

                {{-- Mensajes de éxito o error --}}
                @if ($message = Session::get('success'))
                    <div class="alert alert-success mt-2">
                        <p>{{ $message }}</p>
                    </div>
                @endif
            </div>
            <!-- Tabla de rutas -->
            <div class="table-responsive mt-3">
                <table id="tablaRutas" class="table table-bordered table-striped w-100">
                    <thead class="table-dark text-center">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">DESCRIPCION</th>
                            <th scope="col">TIPO</th>
                            <th scope="col">PRECIO REGULAR</th>
                            <th scope="col">DESCUENTO</th>
                            <th scope="col">PRECIO ACTUAL</th>
                            <th scope="col">DIFICULTAD</th>
                            <th scope="col">ESTADO</th>
                            <th scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($rutas as $ruta)
                            <tr class="table-light">
                                <td>{{ $ruta->id_ruta }}</td>
                                <td>{{ $ruta->nombre_ruta }}</td>
                                <td>{{ $ruta->descripcion_general }}</td>
                                <td>{{ $ruta->tipo }}</td>
                                <td>{{ $ruta->precio_regular }}</td>
                                <td>{{ $ruta->descuento }}</td>
                                <td>{{ $ruta->precio_actual }}</td>
                                <td>{{ $ruta->dificultad }}</td>
                                <td>{{ $ruta->estado }}</td>
                                <td>
                                    {{-- Ver --}}
                                    <button type="button" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#show{{ $ruta->id_ruta }}"
                                            title="Ver">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    {{-- Editar --}}
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#edit{{ $ruta->id_ruta }}"
                                            title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                            @include('rutas.show')
                            @include('rutas.edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Crear --}}
        @include('rutas.create')
    </div>
@stop

@section('css')
    <!-- Estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializar DataTable con idioma en español
            $('#tablaRutas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,  // Activar paginación
                ordering: true, // Permitir ordenar columnas
                searching: true, // Activar búsqueda
                lengthMenu: [10, 25, 50, 100], // Número de registros por página
                autoWidth: true, // Ajusta el ancho de las columnas automáticamente
                responsive: true, // Hace la tabla responsive para dispositivos móviles
            });
        });
    </script>
@stop
