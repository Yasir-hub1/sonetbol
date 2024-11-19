@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Nuestros Routers</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ Storage::url($product->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="card-text">
                            <strong>Precio: </strong>${{ number_format($product->price, 2) }}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Modelo: {{ $product->model }}</small>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-primary">Ver Detalles</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
