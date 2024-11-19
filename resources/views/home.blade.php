@extends('layouts.ecommerce')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Encuentra el Router Perfecto para Ti</h1>
                <p class="lead mb-4">Ofrecemos la mejor selección de routers y servicios técnicos especializados para garantizar una conexión óptima.</p>
                <a href="#productos" class="btn btn-light btn-lg">
                    Ver Productos
                </a>
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('services.create') }}" class="btn btn-outline-light btn-lg ms-2">
                            Solicitar Servicio
                        </a>
                    @endif
                @endauth
            </div>
            <div class="col-md-6">
                <img src="{{ asset('image/route.jpeg') }}" alt="Router" class="img-fluid">
            </div>
        </div>
    </div>
</section>



<!-- Lista de Productos -->
<section id="productos" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Nuestros Productos</h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="searchInput"
                           placeholder="Buscar productos..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-4">
                <select class="form-select" id="sortProducts">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                        Más Recientes
                    </option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                        Precio: Menor a Mayor
                    </option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                        Precio: Mayor a Menor
                    </option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                        Nombre: A-Z
                    </option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                        Nombre: Z-A
                    </option>
                </select>
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="row g-4" id="productsContainer">
            @foreach($products as $product)
            <div class="col-md-4 product-item"
                 data-category="{{ $product->category_id }}"
                 data-price="{{ $product->price }}"
                 data-name="{{ $product->name }}">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        @if($product->stock > 0)
                            <span class="badge bg-success category-badge">En Stock</span>
                        @else
                            <span class="badge bg-danger category-badge">Agotado</span>
                        @endif
                        <img src="{{ Storage::url($product->image_path) }}"
                             alt="{{ $product->name }}"
                             class="card-img-top product-img">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-tag me-1"></i> {{ $product->brand }}
                            <span class="ms-2">
                                <i class="fas fa-box me-1"></i> {{ $product->model }}
                            </span>
                        </p>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>

                        {{-- <!-- Especificaciones Rápidas -->
                        <div class="specs-preview mb-3">
                            @foreach(array_slice($product->specifications, 0, 3) as $key => $value)
                                <small class="d-block text-muted">
                                    <i class="fas fa-check me-1"></i>
                                    {{ $key }}: {{ $value }}
                                </small>
                            @endforeach
                        </div> --}}

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                            <div class="btn-group">
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-info-circle"></i> Detalles
                                </a>
                                @auth
                                    @if(!auth()->user()->isAdmin() && $product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-shopping-cart"></i> Comprar
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt"></i> Login para Comprar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</section>

<!-- Sección de Servicios -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Nuestros Servicios</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5>Instalación</h5>
                        <p>Instalación profesional de equipos de red</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-wrench fa-3x text-primary mb-3"></i>
                        <h5>Mantenimiento</h5>
                        <p>Servicio de mantenimiento preventivo y correctivo</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-network-wired fa-3x text-primary mb-3"></i>
                        <h5>Configuración</h5>
                        <p>Configuración especializada de redes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>Soporte</h5>
                        <p>Soporte técnico especializado</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
@guest
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">¿Listo para mejorar tu conexión?</h2>
        <p class="lead mb-4">Regístrate ahora y obtén acceso a nuestros productos y servicios</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
            Crear Cuenta
        </a>
    </div>
</section>
@endguest
@endsection

@push('styles')
<style>
    .category-card {
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
    }

    .product-img {
        height: 250px;
        object-fit: cover;
    }

    .specs-preview {
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.25rem;
    }

    .hero-section {
        background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: 50px 50px;
    }
    <style>
.input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.input-group-text {
    border: none;
    background-color: transparent;
}

.form-control, .form-select {
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.form-control:focus, .form-select:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
}

/* Estilos para los select */
.form-select {
    cursor: pointer;
    padding-right: 2rem;
}

/* Estilos para la paginación */
.pagination {
    gap: 0.5rem;
}

.page-link {
    border-radius: 0.375rem;
    border: none;
    padding: 0.5rem 1rem;
    color: #4a5568;
    background-color: #edf2f7;
}

.page-link:hover {
    background-color: #e2e8f0;
    color: #2d3748;
}

.page-item.active .page-link {
    background-color: #4299e1;
    color: white;
}
</style>
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortProducts = document.getElementById('sortProducts');
    const productsContainer = document.getElementById('productsContainer');
    const products = document.querySelectorAll('.product-item');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const sortOrder = sortProducts.value;

        // Convert products NodeList to Array for sorting
        let productArray = Array.from(products);

        // Sort products
        productArray.sort((a, b) => {
            switch(sortOrder) {
                case 'price_asc':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price_desc':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'name_asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'name_desc':
                    return b.dataset.name.localeCompare(a.dataset.name);
                default:
                    return 0;
            }
        });

        productArray.forEach(product => {
            const name = product.dataset.name.toLowerCase();
            const category = product.dataset.category;
            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            product.style.display = matchesSearch && matchesCategory ? '' : 'none';
        });

        // Reorder products in the DOM
        productArray.forEach(product => {
            productsContainer.appendChild(product);
        });
    }

    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    sortProducts.addEventListener('change', filterProducts);

    // Category cards filtering
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', () => {
            const category = card.dataset.category;
            categoryFilter.value = category;
            filterProducts();

            // Scroll to products section
            document.getElementById('productos').scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>
@endpush
