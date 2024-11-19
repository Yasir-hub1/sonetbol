@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Producto: {{ $product->name }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nombre del Producto</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" id="description" name="description"
                              rows="3" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" class="form-control" id="price" name="price"
                           step="0.01" value="{{ old('price', $product->price) }}" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock"
                           value="{{ old('stock', $product->stock) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="brand">Marca</label>
                    <input type="text" class="form-control" id="brand" name="brand"
                           value="{{ old('brand', $product->brand) }}" required>
                </div>

                <div class="form-group">
                    <label for="model">Modelo</label>
                    <input type="text" class="form-control" id="model" name="model"
                           value="{{ old('model', $product->model) }}" required>
                </div>

                <div class="form-group">
                    <label for="image">Imagen Actual</label>
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                         class="img-thumbnail mb-2" style="max-height: 200px;">
                    <input type="file" class="form-control-file" id="image" name="image">
                    <small class="form-text text-muted">Deja vacío para mantener la imagen actual</small>
                </div>

                {{-- <div class="form-group">
                    <label>Especificaciones</label>
                    <div id="specifications-container">
                        @foreach($product->specifications as $key => $value)
                            <div class="specification-entry mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="specifications[key][]"
                                           value="{{ $key }}" placeholder="Característica">
                                    <input type="text" class="form-control" name="specifications[value][]"
                                           value="{{ $value }}" placeholder="Valor">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-specification">×</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-specification">
                        Agregar Especificación
                    </button>
                </div> --}}
            </div>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('add-specification').addEventListener('click', function() {
        const container = document.getElementById('specifications-container');
        const template = `
            <div class="specification-entry mb-2">
                <div class="input-group">
                    <input type="text" class="form-control" name="specifications[key][]" placeholder="Característica">
                    <input type="text" class="form-control" name="specifications[value][]" placeholder="Valor">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-specification">×</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-specification')) {
            e.target.closest('.specification-entry').remove();
        }
    });
</script>
@endpush
@endsection
