@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="{{ Storage::url($product->image_path) }}"
                     class="img-fluid rounded"
                     alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            <p class="lead mb-4">{{ $product->description }}</p>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="h3 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                            {{ $product->stock > 0 ? 'En Stock' : 'Agotado' }}
                        </span>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Marca:</strong>
                            <p class="mb-0">{{ $product->brand }}</p>
                        </div>
                        <div class="col-6">
                            <strong>Modelo:</strong>
                            <p class="mb-0">{{ $product->model }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Stock Disponible:</strong>
                        <p class="mb-0">{{ $product->stock }} unidades</p>
                    </div>

                    @if($product->specifications)
                        <hr>
                        <h5>Especificaciones</h5>
                        <ul class="list-unstyled">
                            @foreach($product->specifications as $key => $value)
                                <li><i class="fas fa-check text-success me-2"></i><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @auth
                        @if(!auth()->user()->isAdmin() && $product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                                @csrf
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-auto">
                                        <label for="quantity" class="form-label">Cantidad:</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number"
                                               class="form-control"
                                               id="quantity"
                                               name="quantity"
                                               value="1"
                                               min="1"
                                               max="{{ $product->stock }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Agregar al Carrito
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Inicia sesión para comprar
                        </a>
                    @endauth
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-truck me-2"></i>
                        Información de Envío
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Envío a todo el país</li>
                        <li><i class="fas fa-check text-success me-2"></i>Entrega en 24-48 horas hábiles</li>
                        <li><i class="fas fa-check text-success me-2"></i>Garantía de 12 meses</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
            setTimeout(() => {
                window.location.href = "{{ route('cart.index') }}";
            }, 100);
        });
    }
});
</script>
@endpush
