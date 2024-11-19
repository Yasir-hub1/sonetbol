{{-- resources/views/services/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Solicitar Servicio Técnico
                    </h4>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('services.store') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_type" class="form-label">Tipo de Servicio <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_type') is-invalid @enderror"
                                        id="service_type" name="service_type" required>
                                    <option value="">Seleccione un tipo de servicio</option>
                                    <option value="installation" {{ old('service_type') === 'installation' ? 'selected' : '' }}>
                                        📡 Instalación
                                    </option>
                                    <option value="maintenance" {{ old('service_type') === 'maintenance' ? 'selected' : '' }}>
                                        🔧 Mantenimiento
                                    </option>
                                    <option value="repair" {{ old('service_type') === 'repair' ? 'selected' : '' }}>
                                        🛠️ Reparación
                                    </option>
                                    <option value="configuration" {{ old('service_type') === 'configuration' ? 'selected' : '' }}>
                                        ⚙️ Configuración
                                    </option>
                                </select>
                                @error('service_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Seleccione el tipo de servicio que mejor se ajuste a sus necesidades
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="preferred_date" class="form-label">Fecha y Hora Preferida <span class="text-danger">*</span></label>
                                <input type="datetime-local"
                                       class="form-control @error('preferred_date') is-invalid @enderror"
                                       id="preferred_date"
                                       name="preferred_date"
                                       value="{{ old('preferred_date') }}"
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       required>
                                @error('preferred_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Seleccione la fecha y hora que prefiere para el servicio
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción del Problema <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      required
                                      placeholder="Describa detalladamente el problema o servicio que necesita...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                Proporcione todos los detalles relevantes para ayudarnos a entender mejor su solicitud
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección Completa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      required
                                      placeholder="Ingrese la dirección completa donde se realizará el servicio...">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                Incluya calle, número, departamento, referencias y cualquier detalle que ayude a ubicar el lugar
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Los campos marcados con <span class="text-danger">*</span> son obligatorios
                    </small>
                </div>
            </div>

            <!-- Card informativa -->
            <div class="card mt-4 shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Horarios de Atención</h6>
                            <ul class="list-unstyled">
                                <li><i class="far fa-clock me-2"></i>Lunes a Viernes: 8:00 AM - 6:00 PM</li>
                                <li><i class="far fa-clock me-2"></i>Sábados: 9:00 AM - 2:00 PM</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Tiempo de Respuesta</h6>
                            <p class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Respondemos a las solicitudes en un máximo de 24 horas hábiles
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 600;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }

    .card {
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0;
    }

    .btn {
        border-radius: 5px;
        padding: 8px 20px;
    }

    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
    // Script para validación del lado del cliente
    (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // Script para manejar la fecha mínima
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('preferred_date');
        const today = new Date();
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
        dateInput.min = today.toISOString().slice(0,16);
    });
</script>
@endpush
@endsection
