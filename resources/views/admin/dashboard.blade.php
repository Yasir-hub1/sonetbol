
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

        </div>

        <!-- Estadísticas Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Productos Totales
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['total_products'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Servicios Pendientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['pending_services'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Contenido Principal -->
        <div class="row">
            <!-- Productos Bajo Stock -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Productos con Bajo Stock</h6>
                    </div>
                    <div class="card-body">
                        @if ($lowStockProducts->isEmpty())
                            <p class="text-center">No hay productos con bajo stock</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowStockProducts as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        {{ $product->stock }} unidades
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.products.edit', $product) }}"
                                                        class="btn btn-sm btn-primary">
                                                        Editar
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Servicios Pendientes -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Últimos Servicios Pendientes</h6>
                    </div>
                    <div class="card-body">
                        @if ($pendingServices->isEmpty())
                            <p class="text-center">No hay servicios pendientes</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Tipo</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingServices as $service)
                                            <tr>
                                                <td>{{ $service->user->name }}</td>
                                                <td>{{ ucfirst($service->service_type) }}</td>
                                                <td>{{ $service->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.services.show', $service) }}"
                                                        class="btn btn-sm
                                                    <td>
                                                    <a href="{{ route('admin.services.show', $service) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->


        <!-- Últimas Actividades -->
        <div class="row">
            <!-- Últimos Usuarios Registrados -->


            <!-- Acciones Rápidas -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-plus me-2"></i>
                                    Nuevo Producto
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.services.index') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-tools me-2"></i>
                                    Gestionar Servicios
                                </a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('styles')
        <style>
            .btn-block {
                display: block;
                width: 100%;
            }

            .chart-area {
                height: 300px;
            }

            .chart-pie {
                height: 300px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Gráfico de Servicios por Estado
                const statusCtx = document.getElementById('servicesByStatusChart').getContext('2d');
                new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($servicesByStatus->pluck('status')) !!},
                        datasets: [{
                            data: {!! json_encode($servicesByStatus->pluck('total')) !!},
                            backgroundColor: [
                                '#4e73df',
                                '#1cc88a',
                                '#36b9cc',
                                '#f6c23e'
                            ]
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Gráfico de Servicios por Mes
                const monthCtx = document.getElementById('servicesByMonthChart').getContext('2d');
                new Chart(monthCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($servicesByMonth->pluck('month')) !!},
                        datasets: [{
                            label: 'Servicios',
                            data: {!! json_encode($servicesByMonth->pluck('total')) !!},
                            borderColor: '#4e73df',
                            tension: 0.3,
                            fill: true,
                            backgroundColor: 'rgba(78, 115, 223, 0.1)'
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
