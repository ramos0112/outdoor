@extends('layouts.admin-base')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Panel de Control</h1>
@stop

@section('content')

    {{-- ====== SECCIÓN: RUTAS ====== --}}
    <div class="row justify-content-center">
        <div class="align-items-center mx-1 mb-3">
            <x-adminlte-info-box title="Rutas Activas" text="{{ $rutasActivas }}" icon="fas fa-check-circle" theme="success" />
        </div>
        <div class="align-items-center mx-1 mb-3">
            <x-adminlte-info-box title="Rutas Inactivas" text="{{ $rutasInactivas }}" icon="fas fa-times-circle"
                theme="danger" />
        </div>
        <div class="align-items-center mx-1 mb-3">
            <x-adminlte-info-box title="Precio Regular Promedio" text="S/ {{ number_format($precioRegularPromedio, 2) }}"
                icon="fas fa-tag" theme="info" />
        </div>
        <div class="align-items-center mx-1 mb-3">
            <x-adminlte-info-box title="Precio Actual Promedio" text="S/ {{ number_format($precioActualPromedio, 2) }}"
                icon="fas fa-percentage" theme="warning" />
        </div>
        <div class="align-items-center mx-1 mb-3">
            <x-adminlte-info-box title="Total Pagado" text="S/ {{ number_format($ingresosTotales, 2) }}"
                icon="fas fa-wallet" theme="info" />
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-sm-12 mb-4">
            <h5>Rutas más vendidas</h5>
            <canvas id="chartRutasVendidas"></canvas>
        </div>
        <div class="col-md-6 col-sm-12 mb-4">
            <h5>Rutas con más ingresos</h5>
            <canvas id="chartRutasIngresos"></canvas>
        </div>
    </div>


    {{-- ====== SECCIÓN: RESERVAS ====== --}}
    <div class="row mt-4">
        <div class="col-md-3">
            <x-adminlte-info-box title="Reservas Totales" text="{{ $reservasTotales }}" icon="fas fa-ticket-alt"
                theme="primary" />
        </div>
        <div class="col-md-3">
            <x-adminlte-info-box title="Reservas Pagadas" text="{{ $reservasPagadas }}" icon="fas fa-money-bill-wave"
                theme="success" />
        </div>
        <div class="col-md-3">
            <x-adminlte-info-box title="Pendientes" text="{{ $reservasPendientes }}" icon="fas fa-hourglass-half"
                theme="warning" />
        </div>
        <div class="col-md-3">
            <x-adminlte-info-box title="Canceladas" text="{{ $reservasCanceladas }}" icon="fas fa-ban" theme="danger" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 mb-4">
            <h5>Reservas por mes</h5>
            <canvas id="chartReservasMes"></canvas>
        </div>
        <div class="col-md-6 col-sm-12 mb-4">
            <h5>Ingresos por mes</h5>
            <canvas id="chartPagosMes"></canvas>
        </div>
    </div>

    {{-- ====== SECCIÓN: CLIENTES ====== --}}
<div class="row mt-4 justify-content-center">
    <div class="align-items-center mx-1 mb-3">
        <x-adminlte-info-box title="Clientes Totales" text="{{ $clientesTotales }}" icon="fas fa-users" theme="primary" />
    </div>
    <div class="align-items-center mx-1 mb-3">
        <x-adminlte-info-box title="Clientes con Reservas" text="{{ $clientesConReservas }}" icon="fas fa-user-check" theme="success" />
    </div>
    <div class="align-items-center mx-1 mb-3">
        <x-adminlte-info-box title="Movilidad Disponible" text="{{ $movilidadDisponible }}" icon="fas fa-shuttle-van" theme="info" />
    </div>
    <div class="align-items-center mx-1 mb-3">
        <x-adminlte-info-box title="Movilidad Ocupada" text="{{ $movilidadOcupada }}" icon="fas fa-bus" theme="danger" />
    </div>
    <div class="align-items-center mx-1 mb-3">
        <x-adminlte-info-box title="Guías Registrados" text="{{ $guiasTotales }}" icon="fas fa-user-tie" theme="purple" />
    </div>
</div>


    <div class="row">
        <div class="col-md-6 col-sm-12 mb-4">
            <h5>Clientes Nuevos por mes</h5>
            <canvas id="chartClientesMes"></canvas>
        </div>
        <div class="col-md-6 col-sm-12 mb-4">
            <h5>Clientes por región</h5>
            <canvas id="chartClientesRegion"></canvas>
        </div>
    </div>


     @php
        $temas = [
            'Yape' => ['color' => 'purple', 'icon' => 'fas fa-mobile-alt'],
            'Plin' => ['color' => 'info', 'icon' => 'fas fa-mobile'],
            'Efectivo' => ['color' => 'success', 'icon' => 'fas fa-money-bill-wave'],
            'Tarjeta' => ['color' => 'warning', 'icon' => 'fas fa-credit-card'],
            'Transferencia' => ['color' => 'primary', 'icon' => 'fas fa-university'],
        ];
    @endphp
    <h4 class="text-center">sumas de metodos de pago usados</h4>
    <div class="row mt-4">
        @foreach ($metodosDePagoTotales as $metodo)
            @php
                $tema = $temas[$metodo->metodo_pago] ?? ['color' => 'secondary', 'icon' => 'fas fa-cash-register'];
            @endphp
            <div class="align-items-center mx-1 mb-3">
                <x-adminlte-info-box title="{{ $metodo->metodo_pago }}" text="S/ {{ number_format($metodo->total, 2) }}"
                    icon="{{ $tema['icon'] }}" theme="{{ $tema['color'] }}" />
            </div>
        @endforeach
    </div>

    {{-- ====== SECCIÓN: MÉTODOS DE PAGO ====== --}}
    <div class="row mt-4 justify-content-center align-items-center ">
        <div class="col-md-6 col-sm-12 mb-4">
            <h4 class="text-center">Métodos de pago usados</h4>
            <canvas id="chartMetodosPago"></canvas>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-md-12">
            <h5>Total mensual por método de pago</h5>
            <canvas id="chartMetodoPagoMes"></canvas>
        </div>
    </div>



@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Rutas más vendidas
        new Chart(document.getElementById('chartRutasVendidas'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($rutasVendidas->pluck('nombre_ruta')) !!},
                datasets: [{
                    label: 'Reservas',
                    data: {!! json_encode($rutasVendidas->pluck('total_reservas')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.7)'
                }]
            }
        });

        // Rutas con más ingresos
        new Chart(document.getElementById('chartRutasIngresos'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($rutasConMasIngresos->pluck('nombre_ruta')) !!},
                datasets: [{
                    label: 'Ingresos (S/)',
                    data: {!! json_encode($rutasConMasIngresos->pluck('total_ingresos')) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.7)'
                }]
            }
        });


        // Reservas por mes
        new Chart(document.getElementById('chartReservasMes'), {
            type: 'line',
            data: {
                labels: {!! json_encode(
                    $reservasPorMes->pluck('mes')->map(function ($m) use ($meses) {
                        return $m ? $meses[$m - 1] : '-';
                    }),
                ) !!},

                datasets: [{
                    label: 'Reservas',
                    data: {!! json_encode($reservasPorMes->pluck('total')) !!},
                    borderColor: 'rgba(255, 99, 132, 0.7)',
                    fill: false
                }]
            }
        });

        // Pagos por mes
        new Chart(document.getElementById('chartPagosMes'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($pagosPorMes->pluck('mes')->map(fn($m) => $m ? $meses[$m - 1] : '-')) !!},
                datasets: [{
                    label: 'Pagos (S/)',
                    data: {!! json_encode($pagosPorMes->pluck('total')) !!},
                    backgroundColor: 'rgba(153, 102, 255, 0.7)'
                }]
            }
        });

        // Clientes nuevos por mes
        new Chart(document.getElementById('chartClientesMes'), {
            type: 'line',
            data: {
                labels: {!! json_encode($clientesPorMes->pluck('mes')->map(fn($m) => $m ? $meses[$m - 1] : '-')) !!},
                datasets: [{
                    label: 'Clientes Nuevos',
                    data: {!! json_encode($clientesPorMes->pluck('total')) !!},
                    borderColor: 'rgba(54, 162, 235, 0.8)',
                    fill: true
                }]
            }
        });

        // Clientes por región
        new Chart(document.getElementById('chartClientesRegion'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($clientesPorRegion->pluck('region')) !!},
                datasets: [{
                    label: 'Clientes',
                    data: {!! json_encode($clientesPorRegion->pluck('total')) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.7)'
                }]
            }
        });

        // Métodos de pago
        new Chart(document.getElementById('chartMetodosPago'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($metodosDePago->pluck('metodo_pago')) !!},
                datasets: [{
                    data: {!! json_encode($metodosDePago->pluck('total')) !!},
                    backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336', '#9c27b0']
                }]
            }
        });

        // Dataset para cada método de pago
        const metodoPagoMesData = {
            labels: {!! json_encode($meses) !!},
            datasets: [
                @foreach ($metodos as $metodo)
                    {
                        label: '{{ $metodo }}',
                        data: [
                            @foreach (range(1, 12) as $m)
                                {{ $pagosPorMetodoYMes->where('metodo_pago', $metodo)->firstWhere('mes', $m)->total ?? 0 }},
                            @endforeach
                        ],
                        fill: false,
                        borderColor: '{{ sprintf('#%06X', mt_rand(0, 0xffffff)) }}'
                    },
                @endforeach
            ]
        };

        new Chart(document.getElementById('chartMetodoPagoMes'), {
            type: 'line',
            data: metodoPagoMesData
        });
    </script>
@stop

