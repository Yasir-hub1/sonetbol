{{-- resources/views/admin/services/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalles de Solicitud #{{ $service->id }}</h1>
        <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <!-- Detalles del Servicio -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Servicio</h6>
                    <span class="badge bg-{{
                        $service->status === 'pending' ? 'warning' :
                        ($service->status === 'in_progress' ? 'info' : 'success')
                    }}">
                        {{ ucfirst($service->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tipo de Servicio:</strong><br>
                                {{ ucfirst($service->service_type) }}
                            </p>
                            <p><strong>Fecha de Solicitud:</strong><br>
                                {{ $service->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p><strong>Fecha Preferida:</strong><br>
                                {{ $service->preferred_date->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Cliente:</strong><br>
                                {{ $service->user->name }}
                            </p>
                            <p><strong>Email:</strong><br>
                                {{ $service->user->email }}
                            </p>
                            <p><strong>Teléfono:</strong><br>
                                {{ $service->user->phone ?? 'No especificado' }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Descripción del Servicio</h6>
                        <p>{{ $service->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Dirección</h6>
                        <p>{{ $service->address }}</p>
                    </div>

                    @if($service->notes)
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Notas Adicionales</h6>
                            <p>{{ $service->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Actualizar Estado -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actualizar Estado</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.services.update-status', $service) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Estado</label>
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

                        <div class="mb-3">
                            <label class="form-label">Notas</label>
                            <textarea name="notes" class="form-control" rows="4">{{ $service->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Actualizar Estado
                        </button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.services.destroy', $service) }}"
                          method="POST"
                          onsubmit="return confirm('¿Está seguro de eliminar este servicio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar Servicio
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
