@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Solicitudes de Servicio</h2>
        @if(!auth()->user()->isAdmin())
            <a href="{{ route('services.create') }}" class="btn btn-primary">
                Nueva Solicitud de Servicio
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Tipo de Servicio</th>
                            <th>Fecha Solicitada</th>
                            <th>Fecha Preferida</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->user->name }}</td>
                                <td>
                                    @switch($request->service_type)
                                        @case('installation')
                                            Instalación
                                            @break
                                        @case('maintenance')
                                            Mantenimiento
                                            @break
                                        @case('repair')
                                            Reparación
                                            @break
                                        @case('configuration')
                                            Configuración
                                            @break
                                        @default
                                            {{ $request->service_type }}
                                    @endswitch
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $request->preferred_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-{{
                                        $request->status === 'pending' ? 'warning' :
                                        ($request->status === 'in_progress' ? 'primary' :
                                        ($request->status === 'completed' ? 'success' : 'secondary'))
                                    }}">
                                        @switch($request->status)
                                            @case('pending')
                                                Pendiente
                                                @break
                                            @case('in_progress')
                                                En Progreso
                                                @break
                                            @case('completed')
                                                Completado
                                                @break
                                            @default
                                                {{ $request->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info"
                                                data-toggle="modal"
                                                data-target="#serviceDetails{{ $request->id }}">
                                            Ver Detalles
                                        </button>
                                        @if(auth()->user()->isAdmin())
                                            <button type="button" class="btn btn-sm btn-warning"
                                                    data-toggle="modal"
                                                    data-target="#updateStatus{{ $request->id }}">
                                                Actualizar Estado
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Detalles -->
                            <div class="modal fade" id="serviceDetails{{ $request->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Detalles de la Solicitud #{{ $request->id }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold">Información del Cliente</h6>
                                                    <p><strong>Nombre:</strong> {{ $request->user->name }}</p>
                                                    <p><strong>Email:</strong> {{ $request->user->email }}</p>
                                                    <p><strong>Teléfono:</strong> {{ $request->user->phone ?? 'No especificado' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold">Información del Servicio</h6>
                                                    <p><strong>Tipo:</strong>
                                                        @switch($request->service_type)
                                                            @case('installation')
                                                                Instalación
                                                                @break
                                                            @case('maintenance')
                                                                Mantenimiento
                                                                @break
                                                            @case('repair')
                                                                Reparación
                                                                @break
                                                            @case('configuration')
                                                                Configuración
                                                                @break
                                                            @default
                                                                {{ $request->service_type }}
                                                        @endswitch
                                                    </p>
                                                    <p><strong>Estado:</strong>
                                                        @switch($request->status)
                                                            @case('pending')
                                                                Pendiente
                                                                @break
                                                            @case('in_progress')
                                                                En Progreso
                                                                @break
                                                            @case('completed')
                                                                Completado
                                                                @break
                                                            @default
                                                                {{ $request->status }}
                                                        @endswitch
                                                    </p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Descripción del Servicio</h6>
                                                    <p>{{ $request->description }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold">Dirección</h6>
                                                    <p>{{ $request->address }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold">Fechas</h6>
                                                    <p><strong>Solicitado:</strong> {{ $request->created_at->format('d/m/Y H:i') }}</p>
                                                    <p><strong>Fecha Preferida:</strong> {{ $request->preferred_date->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            @if($request->notes)
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="font-weight-bold">Notas Adicionales</h6>
                                                        <p>{{ $request->notes }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Actualizar Estado -->
                            @if(auth()->user()->isAdmin())
                                <div class="modal fade" id="updateStatus{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Actualizar Estado - Solicitud #{{ $request->id }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.services.update-status', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="status">Estado</label>
                                                        <select class="form-control" id="status" name="status" required>
                                                            <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>
                                                                Pendiente
                                                            </option>
                                                            <option value="in_progress" {{ $request->status === 'in_progress' ? 'selected' : '' }}>
                                                                En Progreso
                                                            </option>
                                                            <option value="completed" {{ $request->status === 'completed' ? 'selected' : '' }}>
                                                                Completado
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notes">Notas (Opcional)</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $request->notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        Actualizar Estado
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
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
