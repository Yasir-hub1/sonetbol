{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Mi Carrito</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count(session('cart', [])) > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('cart') as $id => $details)
                                <tr data-id="{{ $id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($details['image'])
                                                <img src="{{ Storage::url($details['image']) }}"
                                                     alt="{{ $details['name'] }}"
                                                     class="img-thumbnail me-3"
                                                     style="width: 50px;">
                                            @endif
                                            <span>{{ $details['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>${{ number_format($details['price'], 2) }}</td>
                                    <td>
                                        <input type="number"
                                               value="{{ $details['quantity'] }}"
                                               class="form-control quantity-input"
                                               min="1"
                                               style="width: 70px;">
                                    </td>
                                    <td>${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>${{ number_format($total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-trash-alt me-2"></i>
                            Vaciar Carrito
                        </button>
                    </form>
                    {{-- <a href="#" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Proceder al Pago
                    </a> --}}
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center">
                <h4>Tu carrito está vacío</h4>
                <p>¿Por qué no agregas algunos productos?</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Ver Productos
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const id = this.closest('tr').dataset.id;
        const quantity = this.value;

        fetch('{{ route('cart.update') }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: id,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        });
    });
});
</script>
@endpush
@endsection
