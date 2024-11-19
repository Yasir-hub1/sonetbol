<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Router Shop') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('styles')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
        }

        .hero-section {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            padding: 4rem 0;
            margin-bottom: 2rem;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
        }

        .btn-primary {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navbar Superior con Auth -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-wifi me-2"></i>
                    SONETBOL
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#productos">
                                <i class="fas fa-box me-1"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.login') }}" class="nav-link">
                                <i class="fas fa-user-shield me-1"></i>
                                Acceso Admin
                            </a>
                        </li>
                        @auth
                            @if(!auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('services.create') }}">
                                        <i class="fas fa-tools me-1"></i> Solicitar Servicio
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <div class="navbar-nav ms-auto">
                        @guest
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                            </a>
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Registrarse
                            </a>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-1"></i> Panel Admin
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{ route('services.index') }}">
                                                <i class="fas fa-clipboard-list me-1"></i> Mis Servicios
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-light mt-5 py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>SONETBOL</h5>
                        <p>Tu tienda de confianza para equipos de networking y servicios técnicos especializados.</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Enlaces Rápidos</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-light">Sobre Nosotros</a></li>
                            <li><a href="#" class="text-light">Productos</a></li>
                            <li><a href="#" class="text-light">Servicios</a></li>
                            <li><a href="#" class="text-light">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Contacto</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-phone me-2"></i> +123 456 789</li>
                            <li><i class="fas fa-envelope me-2"></i> info@routershop.com</li>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Calle Principal #123</li>
                        </ul>
                    </div>
                </div>
                <hr class="bg-light">
                <div class="text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} SONETBOL. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
