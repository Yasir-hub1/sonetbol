@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestión de Servicios</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Solicitudes de Servicio</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha Solicitada</th>
                            <th>Fecha Preferida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->user->name }}</td>
                                <td>{{ ucfirst($service->service_type) }}</td>
                                <td>
                                    <span class="badge bg-{{
                                        $service->status === 'pending' ? 'warning' :
                                        ($service->status === 'in_progress' ? 'info' : 'success')
                                    }}">
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </td>
                                <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $service->preferred_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#serviceModal{{ $service->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $service->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de Detalles -->
                            <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalles del Servicio #{{ $service->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Cliente:</strong> {{ $service->user->name }}</p>
                                            <p><strong>Email:</strong> {{ $service->user->email }}</p>
                                            <p><strong>Tipo:</strong> {{ ucfirst($service->service_type) }}</p>
                                            <p><strong>Descripción:</strong></p>
                                            <p>{{ $service->description }}</p>
                                            <p><strong>Dirección:</strong></p>
                                            <p>{{ $service->address }}</p>
                                            @if($service->notes)
                                                <p><strong>Notas:</strong></p>
                                                <p>{{ $service->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Estado -->
                            <div class="modal fade" id="statusModal{{ $service->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.services.update-status', $service) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Actualizar Estado #{{ $service->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Estado</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" {{ $service->status === 'pending' ? 'selected' : '' }}>
                                                            Pendiente
                                                        </option>
                                                        <option value="in_progress" {{ $service->status === 'in_progress' ? 'selected' : '' }}>
                                                            En Progreso
                                                        </option>
                                                        <option value="completed" {{ $service->status === 'completed' ? 'selected' : '' }}>
                                                            Completado
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label>Notas</label>
                                                    <textarea name="notes" class="form-control" rows="3">{{ $service->notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    Actualizar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay solicitudes de servicio</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
